<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\FeePayment;
use App\Models\FeePaymentHistory;
use App\Models\FeeReceipt;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FeePaymentService
{
    public function __construct(
        protected NumberService $numberService
    ) {
    }

    /**
     * Submit payment against a voucher.
     */
    public function submit(
        Voucher $voucher,
        array $data,
        ?UploadedFile $proof = null
    ): FeePayment {

        return DB::transaction(function () use (
            $voucher,
            $data,
            $proof
        ) {

            $voucher->refresh();

            if (in_array($voucher->status, [
                'paid',
                'expired',
                'cancelled',
            ])) {
                throw ValidationException::withMessages([
                    'voucher' => 'This voucher cannot receive payment.',
                ]);
            }

            if ((float) $data['amount'] !== (float) $voucher->amount) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount does not match voucher amount.',
                ]);
            }

            $proofPath = null;

            if ($proof) {
                $proofPath = $proof->store(
                    'payment-proofs',
                    'public'
                );
            }

            $payment = FeePayment::create([
                'voucher_id' => $voucher->id,

                'bank_transaction_no' =>
                    $data['bank_transaction_no'] ?? null,

                'deposit_slip_no' =>
                    $data['deposit_slip_no'] ?? null,

                'payment_date' =>
                    $data['payment_date'] ?? now()->toDateString(),

                'amount' => $data['amount'],

                'payment_method' =>
                    $data['payment_method'] ?? 'bank',

                'status' => 'pending',

                'proof_document' => $proofPath,

                'remarks' =>
                    $data['remarks'] ?? null,
            ]);

            $voucher->update([
                'status' => 'submitted',
            ]);

            FeePaymentHistory::create([
                'voucher_id' => $voucher->id,
                'fee_payment_id' => $payment->id,
                'action' => 'payment_submitted',
                'old_status' => 'generated',
                'new_status' => 'pending',
                'amount' => $payment->amount,
                'performed_by' => auth()->id(),
                'remarks' => 'Payment submitted for verification.',
            ]);

            return $payment;
        });
    }

    /**
     * Verify payment and finalize admission.
     */
    public function verify(
        FeePayment $payment,
        User $verifier
    ): FeePayment {

        return DB::transaction(function () use (
            $payment,
            $verifier
        ) {

            $payment->refresh();
            $payment->load('voucher.admission');

            if ($payment->status !== 'pending') {
                throw ValidationException::withMessages([
                    'payment' => 'Only pending payments can be verified.',
                ]);
            }

            $voucher = $payment->voucher;

            if (!$voucher) {
                throw ValidationException::withMessages([
                    'payment' => 'Voucher not found.',
                ]);
            }

            if ((float) $payment->amount !== (float) $voucher->amount) {
                throw ValidationException::withMessages([
                    'payment' => 'Payment amount does not match voucher.',
                ]);
            }

            $payment->update([
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => now(),
            ]);

            $voucher->update([
                'status' => 'paid',
            ]);

            FeePaymentHistory::create([
                'voucher_id' => $voucher->id,
                'fee_payment_id' => $payment->id,
                'action' => 'payment_verified',
                'old_status' => 'pending',
                'new_status' => 'verified',
                'amount' => $payment->amount,
                'performed_by' => $verifier->id,
                'remarks' => 'Payment verified successfully.',
            ]);

            /*
             * Generate official receipt.
             */
            $receiptNo = $this->numberService->generate(
                'receipt',
                'GATTC-R'
            );

            FeeReceipt::create([
                'fee_payment_id' => $payment->id,
                'receipt_no' => $receiptNo,
                'issued_at' => now(),
                'issued_by' => $verifier->id,
            ]);

            /*
             * Finalize admission.
             */
            $this->finalizeAdmission(
                $voucher->admission,
                $payment,
                $verifier
            );

            return $payment->fresh();
        });
    }

    /**
     * Reject payment.
     */
    public function reject(
        FeePayment $payment,
        User $verifier,
        ?string $remarks = null
    ): FeePayment {

        return DB::transaction(function () use (
            $payment,
            $verifier,
            $remarks
        ) {

            $payment->refresh();

            if ($payment->status !== 'pending') {
                throw ValidationException::withMessages([
                    'payment' => 'Only pending payments can be rejected.',
                ]);
            }

            $oldStatus = $payment->status;

            $payment->update([
                'status' => 'rejected',
                'verified_by' => $verifier->id,
                'verified_at' => now(),
                'remarks' => $remarks,
            ]);

            $voucher = $payment->voucher;

            if ($voucher) {
                $voucher->update([
                    'status' => 'generated',
                ]);
            }

            FeePaymentHistory::create([
                'voucher_id' => $voucher?->id,
                'fee_payment_id' => $payment->id,
                'action' => 'payment_rejected',
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
                'amount' => $payment->amount,
                'performed_by' => $verifier->id,
                'remarks' => $remarks,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Finalize admission after successful payment.
     */
    protected function finalizeAdmission(
        Admission $admission,
        FeePayment $payment,
        User $user
    ): void {

        if (!$admission) {
            throw ValidationException::withMessages([
                'admission' => 'Admission application was not found.',
            ]);
        }

        if ($payment->status !== 'verified') {
            throw ValidationException::withMessages([
                'admission' => 'Payment has not been verified.',
            ]);
        }

        /*
         * Generate admission number only now.
         */
        if (!$admission->admission_no) {

            $admission->admission_no =
                $this->numberService->generate(
                    'admission',
                    'GATTC'
                );
        }

        $admission->update([
            'admission_no' => $admission->admission_no,

            'admission_status' => 'admitted',

            'fee_verified_at' => now(),

            'fee_verified_by' => $user->id,

            'admitted_at' => now(),

            'admitted_by' => $user->id,

           
        ]);

        FeePaymentHistory::create([
            'voucher_id' => $payment->voucher_id,

            'fee_payment_id' => $payment->id,

            'action' => 'admission_created',

            'old_status' => 'fee_verified',

            'new_status' => 'admitted',

            'amount' => $payment->amount,

            'performed_by' => $user->id,

            'remarks' =>
                'Admission finalized after successful fee verification.',
        ]);
    }
}