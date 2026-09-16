<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\FeePayment;
use App\Models\FeePaymentHistory;
use App\Models\FeeReceipt;
use App\Models\StudentCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Verification List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = FeePayment::query()
            ->with([
                'voucher.course',
                'voucher.session',
                'voucher.admission',
            ])
            ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'deposit_slip_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'bank_transaction_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'voucher',
                    function ($voucher) use ($search) {

                        $voucher
                            ->where(
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
                                'cnic',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Voucher Type Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('type') &&
            in_array(
                $request->type,
                [
                    'admission',
                    'hostel',
                    'readmission',
                ],
                true
            )
        ) {
            $query->whereHas(
                'voucher',
                function ($voucher) use ($request) {

                    $voucher->where(
                        'voucher_type',
                        $request->type
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $payments = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalPayments = FeePayment::count();

        $pendingPayments = FeePayment::where(
            'status',
            'pending'
        )->count();

        $approvedPayments = FeePayment::where(
            'status',
            'approved'
        )->count();

        $rejectedPayments = FeePayment::where(
            'status',
            'rejected'
        )->count();

        return view(
            'admin.fee-payments.index',
            compact(
                'payments',
                'totalPayments',
                'pendingPayments',
                'approvedPayments',
                'rejectedPayments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Payment Verification
    |--------------------------------------------------------------------------
    */

    public function show(FeePayment $feePayment)
    {
        $feePayment->load([
            'voucher.course.category',
            'voucher.session',
            'voucher.admission.studentCards',
            'voucher.bankAccount',
            'histories.user',
            'receipt',
        ]);

        return view(
            'admin.fee-payments.show',
            compact('feePayment')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve Payment
    |--------------------------------------------------------------------------
    |
    | This verifies the bank payment/slip.
    |
    | After approval:
    | - Payment becomes approved
    | - Voucher becomes paid
    | - Payment history is recorded
    | - Fee receipt is generated
    |
    | Admission/student-card finalization remains a separate step.
    |
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        FeePayment $feePayment
    ) {
        $validated = $request->validate([
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Already approved
        |--------------------------------------------------------------------------
        */

        if ($feePayment->status === 'approved') {
            return redirect()
                ->route(
                    'admin.fee-payments.show',
                    $feePayment
                )
                ->with(
                    'success',
                    'This payment has already been approved.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Voucher Required
        |--------------------------------------------------------------------------
        */

        $voucher = $feePayment->voucher;

        if (!$voucher) {
            return back()->withErrors([
                'payment' =>
                    'This payment is not linked to a voucher.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Amount Check
        |--------------------------------------------------------------------------
        |
        | Prevent approving a payment that does not match the
        | voucher amount.
        |
        */

        if (
            (float) $feePayment->amount !==
            (float) $voucher->amount
        ) {
            return back()->withErrors([
                'amount' =>
                    'The deposited amount does not match the voucher amount.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $feePayment,
            $voucher
        ) {

            $oldStatus =
                $feePayment->status;

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $feePayment->update([
                'status' => 'approved',

                'verified_at' => now(),

                'remarks' =>
                    $request->input('remarks')
                    ?: 'Payment slip physically verified and approved.',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Voucher Status
            |--------------------------------------------------------------------------
            */

            $voucher->update([
                'status' => 'paid',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Payment History
            |--------------------------------------------------------------------------
            */

            $this->recordHistory(
                $feePayment,
                $oldStatus,
                'approved',
                $request->input('remarks')
                    ?: 'Payment slip physically verified and approved.'
            );

            /*
            |--------------------------------------------------------------------------
            | Generate Fee Receipt
            |--------------------------------------------------------------------------
            |
            | A receipt is generated for every approved payment,
            | including hostel payments.
            |
            */

            $this->createFeeReceipt(
                $feePayment,
                $voucher->voucher_type
            );
        });

        return redirect()
            ->route(
                'admin.fee-payments.show',
                $feePayment
            )
            ->with(
                'success',
                'Payment approved successfully. The voucher is now marked as paid.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        FeePayment $feePayment
    ) {
        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Do not reject already-approved payment
        |--------------------------------------------------------------------------
        */

        if ($feePayment->status === 'approved') {

            return back()->withErrors([
                'payment' =>
                    'An approved payment cannot be rejected.',
            ]);
        }

        $voucher =
            $feePayment->voucher;

        DB::transaction(function () use (
            $feePayment,
            $voucher,
            $validated
        ) {

            $oldStatus =
                $feePayment->status;

            /*
            |--------------------------------------------------------------------------
            | Payment Rejected
            |--------------------------------------------------------------------------
            */

            $feePayment->update([
                'status' =>
                    'rejected',

                'verified_at' =>
                    null,

                'remarks' =>
                    $validated['remarks'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Voucher remains/generated again for correction
            |--------------------------------------------------------------------------
            */

            if ($voucher) {
                $voucher->update([
                    'status' => 'generated',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | History
            |--------------------------------------------------------------------------
            */

            $this->recordHistory(
                $feePayment,
                $oldStatus,
                'rejected',
                $validated['remarks']
            );
        });

        return redirect()
            ->route(
                'admin.fee-payments.show',
                $feePayment
            )
            ->with(
                'success',
                'Payment has been rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Finalize Admission
    |--------------------------------------------------------------------------
    |
    | This is the second step after payment verification.
    |
    | Admission:
    |   - creates admission if needed
    |   - generates admission number if missing
    |   - requires photo
    |   - approves admission
    |   - generates student card
    |
    | Readmission:
    |   - existing admission is retained
    |   - existing admission number is retained
    |   - requires a fresh student photo
    |   - creates a new student card
    |
    | Hostel:
    |   - no admission number
    |   - no student card
    |
    |--------------------------------------------------------------------------
    */

    public function finalizeAdmission(
        Request $request,
        FeePayment $feePayment
    ) {
        /*
        |--------------------------------------------------------------------------
        | Load required relationships
        |--------------------------------------------------------------------------
        */

        $feePayment->load([
            'voucher.course',
            'voucher.session',
            'voucher.admission',
        ]);

        $voucher =
            $feePayment->voucher;

        /*
        |--------------------------------------------------------------------------
        | Voucher check
        |--------------------------------------------------------------------------
        */

        if (!$voucher) {

            return back()->withErrors([
                'payment' =>
                    'No voucher is attached to this payment.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment must be approved
        |--------------------------------------------------------------------------
        */

        if ($feePayment->status !== 'approved') {

            return back()->withErrors([
                'payment' =>
                    'Payment must be approved before finalizing the admission.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Voucher must be paid
        |--------------------------------------------------------------------------
        */

        if ($voucher->status !== 'paid') {

            return back()->withErrors([
                'payment' =>
                    'Voucher is not marked as paid.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Voucher Type
        |--------------------------------------------------------------------------
        */

        $voucherType =
            $voucher->voucher_type;

        /*
        |--------------------------------------------------------------------------
        | HOSTEL
        |--------------------------------------------------------------------------
        |
        | Hostel is already complete after payment approval.
        |
        */

        if ($voucherType === 'hostel') {

            return redirect()
                ->route(
                    'admin.fee-payments.show',
                    $feePayment
                )
                ->with(
                    'success',
                    'Hostel payment has been verified. No admission number or student card is generated for a hostel-only voucher.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Student Photo Required
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'student_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'admission_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Process
        |--------------------------------------------------------------------------
        */

        $result = DB::transaction(function () use (
            $request,
            $feePayment,
            $voucher,
            $voucherType
        ) {

            /*
            |--------------------------------------------------------------------------
            | Existing or New Admission
            |--------------------------------------------------------------------------
            */

            $admission =
                $voucher->admission;


            /*
            |--------------------------------------------------------------------------
            | Create admission if missing
            |--------------------------------------------------------------------------
            |
            | This supports public/legacy vouchers which were
            | created without an admission record.
            |
            */

            if (
                $voucherType === 'admission'
                && !$admission
            ) {

                $admission =
                    Admission::create([

                        /*
                        | Temporarily generated immediately.
                        */

                        'admission_no' =>
                            $this->generateAdmissionNo(),

                        'admission_session_id' =>
                            $voucher->admission_session_id,

                        'course_id' =>
                            $voucher->course_id,

                        'student_name' =>
                            $voucher->applicant_name,

                        'father_name' =>
                            $voucher->father_name,

                        'cnic' =>
                            $voucher->cnic,

                        'date_of_birth' =>
                            $voucher->date_of_birth,

                        'gender' =>
                            $voucher->gender,

                        'phone' =>
                            $voucher->phone,

                        'email' =>
                            $voucher->email,

                        'address' =>
                            $voucher->address,

                        'status' =>
                            'pending',

                        'remarks' =>
                            'Created after payment verification.',
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Link admission to voucher
                |--------------------------------------------------------------------------
                */

                $voucher->update([
                    'admission_id' =>
                        $admission->id,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Admission is required
            |--------------------------------------------------------------------------
            */

            if (!$admission) {

                throw new \RuntimeException(
                    'No admission record is available for this voucher.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Ensure Admission Number
            |--------------------------------------------------------------------------
            |
            | This is important for admissions created by the
            | previous voucher workflow without an admission number.
            |
            */

            if (
                empty($admission->admission_no)
            ) {

                $admission->update([
                    'admission_no' =>
                        $this->generateAdmissionNo(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Student Photo
            |--------------------------------------------------------------------------
            */

            $photoPath =
                $request
                    ->file('student_photo')
                    ->store(
                        'students/photos',
                        'public'
                    );


            /*
            |--------------------------------------------------------------------------
            | Determine Remarks
            |--------------------------------------------------------------------------
            */

            $remarks =
                $request->input(
                    'admission_remarks'
                );


            if (!$remarks) {

                $remarks =
                    $voucherType === 'readmission'

                    ? 'Readmission payment, application and student photo physically verified. Student card reissued.'

                    : 'Payment, deposited slip, application and student photo physically verified. Admission finalized.';
            }


            /*
            |--------------------------------------------------------------------------
            | Finalize Admission
            |--------------------------------------------------------------------------
            */

            $admission->update([

                'student_photo' =>
                    $photoPath,

                'status' =>
                    'approved',

                'remarks' =>
                    $remarks,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Disable Previous Active Student Card
            |--------------------------------------------------------------------------
            |
            | Particularly important for readmission.
            |
            */

            StudentCard::query()
                ->where(
                    'admission_id',
                    $admission->id
                )
                ->where(
                    'status',
                    true
                )
                ->update([
                    'status' => false,
                    'remarks' =>
                        'Superseded by newly issued student card.',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Generate Card Number
            |--------------------------------------------------------------------------
            */

            $cardNo =
                $this->generateStudentCardNo();


            /*
            |--------------------------------------------------------------------------
            | Create Student Card
            |--------------------------------------------------------------------------
            */

            $studentCard =
                StudentCard::create([

                    'admission_id' =>
                        $admission->id,

                    'card_no' =>
                        $cardNo,

                    'photo' =>
                        $photoPath,

                    'issued_at' =>
                        now()->toDateString(),

                    'issued_by' =>
                        auth()->id(),

                    'status' =>
                        true,

                    'remarks' =>
                        $voucherType === 'readmission'

                            ? 'Student card reissued after readmission verification.'

                            : 'Student card issued after admission verification.',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Ensure Fee Receipt
            |--------------------------------------------------------------------------
            |
            | approve() normally already creates it.
            | This check makes finalization safe for older payments.
            |
            */

            $this->createFeeReceipt(
                $feePayment,
                $voucherType
            );


            return [
                'admission' =>
                    $admission,

                'studentCard' =>
                    $studentCard,
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Go directly to Student Card
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.student-cards.print',
                $result['studentCard']
            )
            ->with(
                'success',
                'Admission finalized successfully. Admission number and student card have been generated.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Slip
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
    | Print Student Card
    |--------------------------------------------------------------------------
    */

    public function printStudentCard(
        StudentCard $studentCard
    ) {
        $studentCard->load([
            'admission.course',
            'admission.session',
        ]);

        return view(
            'admin.student-cards.print',
            compact('studentCard')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Record Payment History
    |--------------------------------------------------------------------------
    */

    private function recordHistory(
        FeePayment $feePayment,
        ?string $oldStatus,
        string $newStatus,
        string $remarks
    ): void {
        FeePaymentHistory::create([

            'fee_payment_id' =>
                $feePayment->id,

            'user_id' =>
                auth()->id(),

            'old_status' =>
                $oldStatus,

            'new_status' =>
                $newStatus,

            'remarks' =>
                $remarks,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Admission Number
    |--------------------------------------------------------------------------
    */

    private function generateAdmissionNo(): string
    {
        $year =
            now()->format('Y');


        $lastAdmission =
            Admission::query()
                ->where(
                    'admission_no',
                    'like',
                    'ADM-' . $year . '-%'
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if ($lastAdmission) {

            $number =
                ((int) substr(
                    $lastAdmission->admission_no,
                    -5
                )) + 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Extra duplicate protection
        |--------------------------------------------------------------------------
        */

        do {

            $admissionNo =
                'ADM-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            $exists =
                Admission::where(
                    'admission_no',
                    $admissionNo
                )->exists();

            if ($exists) {
                $number++;
            }

        } while ($exists);


        return $admissionNo;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Student Card Number
    |--------------------------------------------------------------------------
    */

    private function generateStudentCardNo(): string
    {
        $year =
            now()->format('Y');


        $lastCard =
            StudentCard::query()
                ->where(
                    'card_no',
                    'like',
                    'STC-' . $year . '-%'
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if ($lastCard) {

            $number =
                ((int) substr(
                    $lastCard->card_no,
                    -5
                )) + 1;
        }


        do {

            $cardNo =
                'STC-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            $exists =
                StudentCard::where(
                    'card_no',
                    $cardNo
                )->exists();

            if ($exists) {
                $number++;
            }

        } while ($exists);


        return $cardNo;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Fee Receipt
    |--------------------------------------------------------------------------
    */

    private function createFeeReceipt(
        FeePayment $feePayment,
        string $voucherType
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Receipt
        |--------------------------------------------------------------------------
        */

        if (
            FeeReceipt::where(
                'fee_payment_id',
                $feePayment->id
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
                $feePayment->id,

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