<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeePaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = FeePayment::query()
            ->with([
                'voucher.course',
                'voucher.bankAccount',
                'verifier',
            ])
            ->when(
                $request->filled('status'),
                fn ($q) =>
                    $q->where(
                        'status',
                        $request->status
                    )
            )
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->whereHas(
                        'voucher',
                        function ($q) use ($search) {
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
                                'cnic',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.fee-payments.index',
            compact('payments')
        );
    }

    public function show(FeePayment $feePayment)
    {
        $feePayment->load([
            'voucher.course',
            'voucher.bankAccount',
            'voucher.session',
            'verifier',
        ]);

        return view(
            'admin.fee-payments.show',
            compact('feePayment')
        );
    }

    /**
     * Admin manually approves payment.
     */
    public function approve(
        FeePayment $feePayment
    ) {
        if ($feePayment->status === 'approved') {
            return back()->with(
                'info',
                'This payment has already been approved.'
            );
        }

        DB::transaction(function () use (
            $feePayment
        ) {
            $oldStatus = $feePayment->status;

            $feePayment->update([
                'status' => 'approved',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $voucher = $feePayment->voucher;

            $voucher->update([
                'status' => 'paid',
            ]);

            /*
             * Payment history.
             */
            $feePayment->histories()->create([
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'approved',
                'remarks' => 'Payment approved by administration.',
            ]);
        });

        return back()->with(
            'success',
            'Payment approved successfully.'
        );
    }

    /**
     * Admin rejects payment.
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

        DB::transaction(function () use (
            $feePayment,
            $validated
        ) {
            $oldStatus = $feePayment->status;

            $feePayment->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'remarks' => $validated['remarks'],
            ]);

            $feePayment->voucher->update([
                'status' => 'generated',
            ]);

            $feePayment->histories()->create([
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
                'remarks' => $validated['remarks'],
            ]);
        });

        return back()->with(
            'success',
            'Payment rejected.'
        );
    }

    /**
     * Student/public submission of paid challan.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'voucher_no' => [
                'required',
                'string',
                'exists:vouchers,voucher_no',
            ],

            'payment_slip' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'payment_date' => [
                'nullable',
                'date',
            ],
        ]);

        $voucher = Voucher::where(
            'voucher_no',
            $validated['voucher_no']
        )->firstOrFail();

        if ($voucher->status === 'paid') {
            return back()->with(
                'error',
                'This voucher has already been verified.'
            );
        }

        /*
         * Prevent duplicate pending submissions.
         */
        if (
            $voucher->payment()
                ->where('status', 'pending')
                ->exists()
        ) {
            return back()->with(
                'error',
                'A payment submission is already pending verification.'
            );
        }

        $path = $request
            ->file('payment_slip')
            ->store('payment-slips', 'public');

        DB::transaction(function () use (
            $voucher,
            $validated,
            $path
        ) {
            $voucher->payment()->create([
                'payment_slip' => $path,
                'payment_date' =>
                    $validated['payment_date']
                    ?? today(),
                'status' => 'pending',
            ]);
        });

        return back()->with(
            'success',
            'Paid challan submitted successfully. It is now pending verification.'
        );
    }

    /**
     * Download/view uploaded payment slip.
     */
    public function slip(FeePayment $feePayment)
    {
        if (
            !Storage::disk('public')->exists(
                $feePayment->payment_slip
            )
        ) {
            abort(404);
        }

        return Storage::disk('public')->response(
            $feePayment->payment_slip
        );
    }
}