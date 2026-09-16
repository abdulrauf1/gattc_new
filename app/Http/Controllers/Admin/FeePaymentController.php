<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\FeePaymentHistory;
use App\Models\FeeReceipt;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Verification
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Start from VOUCHERS, not fee_payments.
    |
    | This ensures every generated voucher appears:
    | - Not Submitted
    | - Pending
    | - Approved
    | - Rejected
    |
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query =
            Voucher::query()
                ->with([
                    'course',
                    'session',
                    'admission',
                    'payment',
                ])
                ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                trim($request->search);


            $query->where(function ($q) use ($search) {

                $q->where(
                    'voucher_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'applicant_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'father_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'cnic',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Payment status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $status =
                $request->payment_status;


            if ($status === 'not_submitted') {

                $query->whereDoesntHave(
                    'payment'
                );

            } else {

                $query->whereHas(
                    'payment',
                    function ($payment) use ($status) {

                        $payment->where(
                            'status',
                            $status
                        );
                    }
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Voucher type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where(
                'voucher_type',
                $request->type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Paginate
        |--------------------------------------------------------------------------
        */

        $vouchers =
            $query
                ->paginate(15)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalVouchers =
            Voucher::count();


        $notSubmitted =
            Voucher::whereDoesntHave(
                'payment'
            )->count();


        $pendingVerification =
            Voucher::whereHas(
                'payment',
                function ($payment) {

                    $payment->where(
                        'status',
                        'pending'
                    );
                }
            )->count();


        $approvedPayments =
            Voucher::whereHas(
                'payment',
                function ($payment) {

                    $payment->where(
                        'status',
                        'approved'
                    );
                }
            )->count();


        $rejectedPayments =
            Voucher::whereHas(
                'payment',
                function ($payment) {

                    $payment->where(
                        'status',
                        'rejected'
                    );
                }
            )->count();


        return view(
            'admin.fee-payments.index',
            compact(
                'vouchers',
                'totalVouchers',
                'notSubmitted',
                'pendingVerification',
                'approvedPayments',
                'rejectedPayments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Voucher Payment Verification
    |--------------------------------------------------------------------------
    */

    public function showVoucher(
        Voucher $voucher
    ) {
        $voucher->load([
            'course.category',
            'session',
            'admission',
            'bankAccount',
            'payment.histories.user',
            'payment.receipt',
        ]);


        $payment =
            $voucher->payment;


        $isHostel =
            $voucher->voucher_type === 'hostel';


        $isReadmission =
            $voucher->voucher_type === 'readmission';


        return view(
            'admin.fee-payments.show',
            compact(
                'voucher',
                'payment',
                'isHostel',
                'isReadmission'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Enter / Update Payment Details
    |--------------------------------------------------------------------------
    */

    public function updateDetails(
        Request $request,
        Voucher $voucher
    ) {
        /*
        |--------------------------------------------------------------------------
        | Load existing payment
        |--------------------------------------------------------------------------
        */

        $payment =
            $voucher->payment;


        $rules = [

            'deposit_slip_no' => [
                'required',
                'string',
                'max:100',
            ],

            'bank_transaction_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:50',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Payment slip
        |--------------------------------------------------------------------------
        |
        | Required for first submission.
        |
        */

        if (!$payment) {

            $rules['payment_slip'] = [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,webp',
                'max:5120',
            ];

        } else {

            $rules['payment_slip'] = [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,webp',
                'max:5120',
            ];
        }


        $validated =
            $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Amount must match voucher
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['amount']
            !==
            (float) $voucher->amount
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'amount' =>
                        'The deposited amount must match the voucher amount of Rs. ' .
                        number_format(
                            $voucher->amount,
                            2
                        ),

                ]);
        }


        DB::transaction(function () use (
            $request,
            $validated,
            $voucher,
            $payment
        ) {

            /*
            |--------------------------------------------------------------------------
            | Existing slip
            |--------------------------------------------------------------------------
            */

            $slipPath =
                $payment?->payment_slip;


            /*
            |--------------------------------------------------------------------------
            | New uploaded slip
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'payment_slip'
                )
            ) {

                if (
                    $slipPath &&
                    Storage::disk('public')
                        ->exists($slipPath)
                ) {

                    Storage::disk('public')
                        ->delete($slipPath);
                }


                $slipPath =
                    $request
                        ->file(
                            'payment_slip'
                        )
                        ->store(
                            'payments/slips',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Create payment if none exists
            |--------------------------------------------------------------------------
            */

            if (!$payment) {

                $payment =
                    FeePayment::create([

                        'voucher_id' =>
                            $voucher->id,

                        'deposit_slip_no' =>
                            $validated['deposit_slip_no'],

                        'bank_transaction_no' =>
                            $validated['bank_transaction_no']
                            ?? null,

                        'payment_date' =>
                            $validated['payment_date'],

                        'amount' =>
                            $validated['amount'],

                        'payment_method' =>
                            $validated['payment_method']
                            ?? 'bank',

                        'payment_slip' =>
                            $slipPath,

                        'status' =>
                            'pending',

                        'verified_at' =>
                            null,

                        'remarks' =>
                            $validated['remarks']
                            ?? 'Payment details submitted for verification.',
                    ]);


                /*
                |--------------------------------------------------------------------------
                | History
                |--------------------------------------------------------------------------
                */

                FeePaymentHistory::create([

                    'fee_payment_id' =>
                        $payment->id,

                    'user_id' =>
                        auth()->id(),

                    'old_status' =>
                        null,

                    'new_status' =>
                        'pending',

                    'remarks' =>
                        'Payment details entered/submitted for verification.',
                ]);

            } else {

                /*
                |--------------------------------------------------------------------------
                | Existing payment update
                |--------------------------------------------------------------------------
                */

                $oldStatus =
                    $payment->status;


                $payment->update([

                    'deposit_slip_no' =>
                        $validated['deposit_slip_no'],

                    'bank_transaction_no' =>
                        $validated['bank_transaction_no']
                        ?? null,

                    'payment_date' =>
                        $validated['payment_date'],

                    'amount' =>
                        $validated['amount'],

                    'payment_method' =>
                        $validated['payment_method']
                        ?? $payment->payment_method,

                    'payment_slip' =>
                        $slipPath,

                    /*
                    | Any correction requires
                    | another verification.
                    */

                    'status' =>
                        'pending',

                    'verified_at' =>
                        null,

                    'remarks' =>
                        $validated['remarks']
                        ?? 'Payment details updated and returned for verification.',
                ]);


                FeePaymentHistory::create([

                    'fee_payment_id' =>
                        $payment->id,

                    'user_id' =>
                        auth()->id(),

                    'old_status' =>
                        $oldStatus,

                    'new_status' =>
                        'pending',

                    'remarks' =>
                        'Payment details updated and submitted for verification.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Voucher remains generated until payment approved
            |--------------------------------------------------------------------------
            */

            $voucher->update([
                'status' => 'generated',
            ]);
        });


        return redirect()
            ->route(
                'admin.fee-payments.voucher',
                $voucher
            )
            ->with(
                'success',
                'Payment details saved. Please physically verify the deposited slip before approving the payment.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve Payment
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        Voucher $voucher
    ) {
        $payment =
            $voucher->payment;


        if (!$payment) {

            return back()->withErrors([
                'payment' =>
                    'No payment has been submitted for this voucher.',
            ]);
        }


        if (
            !$payment->deposit_slip_no
        ) {

            return back()->withErrors([
                'payment' =>
                    'Deposit slip number is required.',
            ]);
        }


        if (
            !$payment->payment_date
        ) {

            return back()->withErrors([
                'payment' =>
                    'Payment date is required.',
            ]);
        }


        if (
            (float) $payment->amount <= 0
        ) {

            return back()->withErrors([
                'payment' =>
                    'A valid deposited amount is required.',
            ]);
        }


        if (
            !$payment->payment_slip
        ) {

            return back()->withErrors([
                'payment' =>
                    'Paid bank slip must be uploaded before approval.',
            ]);
        }


        if (
            (float) $payment->amount
            !==
            (float) $voucher->amount
        ) {

            return back()->withErrors([
                'payment' =>
                    'Deposited amount does not match the voucher amount.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Approve
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $payment,
            $voucher
        ) {

            $oldStatus =
                $payment->status;


            $payment->update([

                'status' =>
                    'approved',

                'verified_at' =>
                    now(),

                'remarks' =>
                    $request->input(
                        'remarks'
                    )
                    ?: 'Paid bank slip physically verified and approved.',
            ]);


            $voucher->update([
                'status' => 'paid',
            ]);


            FeePaymentHistory::create([

                'fee_payment_id' =>
                    $payment->id,

                'user_id' =>
                    auth()->id(),

                'old_status' =>
                    $oldStatus,

                'new_status' =>
                    'approved',

                'remarks' =>
                    $request->input(
                        'remarks'
                    )
                    ?: 'Paid bank slip physically verified and approved.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate receipt
            |--------------------------------------------------------------------------
            */

            $this->createFeeReceipt(
                $payment,
                $voucher->voucher_type
            );
        });


        return redirect()
            ->route(
                'admin.fee-payments.voucher',
                $voucher
            )
            ->with(
                'success',
                'Payment approved successfully. Continue to the Admissions page for admission verification and student-card generation.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Voucher $voucher
    ) {
        $payment =
            $voucher->payment;


        if (!$payment) {

            return back()->withErrors([
                'payment' =>
                    'No payment record exists for this voucher.',
            ]);
        }


        $validated =
            $request->validate([
                'remarks' => [
                    'required',
                    'string',
                    'max:1000',
                ],
            ]);


        DB::transaction(function () use (
            $payment,
            $voucher,
            $validated
        ) {

            $oldStatus =
                $payment->status;


            $payment->update([

                'status' =>
                    'rejected',

                'verified_at' =>
                    null,

                'remarks' =>
                    $validated['remarks'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Voucher becomes available for another submission
            |--------------------------------------------------------------------------
            */

            $voucher->update([
                'status' => 'generated',
            ]);


            FeePaymentHistory::create([

                'fee_payment_id' =>
                    $payment->id,

                'user_id' =>
                    auth()->id(),

                'old_status' =>
                    $oldStatus,

                'new_status' =>
                    'rejected',

                'remarks' =>
                    $validated['remarks'],
            ]);
        });


        return redirect()
            ->route(
                'admin.fee-payments.voucher',
                $voucher
            )
            ->with(
                'success',
                'Payment has been rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | View Uploaded Slip
    |--------------------------------------------------------------------------
    */

    public function slip(
        FeePayment $feePayment
    ) {
        $path =
            $feePayment->payment_slip;


        if (!$path) {
            abort(404);
        }


        $disk =
            Storage::disk('public');


        if (!$disk->exists($path)) {
            abort(404);
        }


        return $disk->response(
            $path
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Receipt
    |--------------------------------------------------------------------------
    */

    private function createFeeReceipt(
        FeePayment $payment,
        string $voucherType
    ): void {

        /*
        | Prevent duplicate receipt
        */

        if (
            FeeReceipt::where(
                'fee_payment_id',
                $payment->id
            )->exists()
        ) {
            return;
        }


        $year =
            now()->format('Y');


        $lastReceipt =
            FeeReceipt::query()
                ->where(
                    'receipt_no',
                    'like',
                    'RCT-' . $year . '-%'
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if ($lastReceipt) {

            $number =
                ((int) substr(
                    $lastReceipt->receipt_no,
                    -5
                )) + 1;
        }


        do {

            $receiptNo =
                'RCT-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                FeeReceipt::where(
                    'receipt_no',
                    $receiptNo
                )->exists();


            if ($exists) {
                $number++;
            }

        } while ($exists);


        FeeReceipt::create([

            'fee_payment_id' =>
                $payment->id,

            'receipt_no' =>
                $receiptNo,

            'issued_at' =>
                now(),

            'issued_by' =>
                auth()->id(),

            'remarks' =>
                ucfirst(
                    $voucherType
                ) .
                ' payment receipt generated after payment verification.',
        ]);
    }
}