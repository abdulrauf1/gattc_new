<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\FeeConfiguration;
use App\Models\FeePaymentHistory;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    public function __construct(
        protected NumberService $numberService
    ) {
    }

    public function generateForAdmission(
        Admission $admission,
        FeeConfiguration $configuration
    ): Voucher {

        return DB::transaction(function () use ($admission, $configuration) {

            $session = $admission->session;

            if (!$session) {
                throw ValidationException::withMessages([
                    'admission' => 'Admission session was not found.',
                ]);
            }

            if (!$session->isCurrentlyOpen()) {
                throw ValidationException::withMessages([
                    'admission' => 'Admissions are currently closed.',
                ]);
            }

            if (!$configuration->status) {
                throw ValidationException::withMessages([
                    'fee' => 'This fee configuration is inactive.',
                ]);
            }

            /*
             * Prevent duplicate active vouchers for
             * the same admission and fee configuration.
             */
            $existing = Voucher::where('admission_id', $admission->id)
                ->where('fee_configuration_id', $configuration->id)
                ->whereIn('status', [
                    'generated',
                    'submitted',
                ])
                ->latest()
                ->first();

            if ($existing) {
                return $existing;
            }

            $bankAccount = $configuration->bankAccount;

            $voucherNo = $this->numberService->generate(
                'voucher',
                'GATTC-V'
            );

            $voucher = Voucher::create([
                'voucher_no' => $voucherNo,

                'fee_configuration_id' => $configuration->id,

                'admission_id' => $admission->id,

                'course_id' => $admission->course_id,

                'course_batch_id' => $admission->course_batch_id,

                'applicant_name' => $admission->full_name,

                'father_name' => $admission->father_name,

                'cnic' => $admission->cnic,

                'phone' => $admission->phone,

                'amount' => $configuration->amount,

                /*
                 * Bank snapshot
                 */
                'bank_name' => $bankAccount->bank_name,
                'account_title' => $bankAccount->account_title,
                'account_number' => $bankAccount->account_number,
                'iban' => $bankAccount->iban,
                'branch_name' => $bankAccount->branch_name,

                'issue_date' => now()->toDateString(),

                'due_date' => now()
                    ->addDays(7)
                    ->toDateString(),

                'status' => 'generated',
            ]);

            FeePaymentHistory::create([
                'voucher_id' => $voucher->id,
                'action' => 'voucher_generated',
                'new_status' => 'generated',
                'amount' => $voucher->amount,
                'performed_by' => auth()->id(),
                'remarks' => 'Fee voucher generated.',
            ]);

            $admission->update([
                'admission_status' => 'fee_pending',
            ]);

            return $voucher;
        });
    }
}