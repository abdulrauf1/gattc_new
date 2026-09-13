<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherService
{
    public function createVoucher(array $data): Voucher
    {
        return DB::transaction(function () use ($data) {
            $bankAccount = BankAccount::query()
                ->active()
                ->findOrFail($data['bank_account_id']);

            $voucherNo = $this->generateVoucherNumber();

            $voucher = Voucher::create([
                'voucher_no' => $voucherNo,

                'fee_configuration_id' => $data['fee_configuration_id'] ?? null,
                'admission_session_id' => $data['admission_session_id'] ?? null,
                'admission_id' => $data['admission_id'] ?? null,
                'course_id' => $data['course_id'] ?? null,
                'course_batch_id' => $data['course_batch_id'] ?? null,

                'bank_account_id' => $bankAccount->id,
                'voucher_category' => $data['voucher_category'],

                'applicant_name' => $data['applicant_name'],
                'father_name' => $data['father_name'] ?? null,
                'cnic' => $data['cnic'] ?? null,
                'phone' => $data['phone'] ?? null,

                'amount' => $data['amount'],
                'fee_details' => $data['fee_details'] ?? [],

                'issue_date' => $data['issue_date'] ?? now()->toDateString(),
                'due_date' => $data['due_date'] ?? now()->addDays(7)->toDateString(),

                'status' => 'generated',
                'remarks' => $data['remarks'] ?? null,

                // Snapshot bank information
                'bank_name' => $bankAccount->bank_name,
                'account_title' => $bankAccount->account_title,
                'account_number' => $bankAccount->account_number,
                'iban' => $bankAccount->iban,
                'branch_name' => $bankAccount->branch_name,
            ]);

            return $voucher;
        });
    }

    private function generateVoucherNumber(): string
    {
        do {
            $number = 'GATTC-' . now()->format('Ym') . '-' .
                strtoupper(Str::random(6));
        } while (Voucher::where('voucher_no', $number)->exists());

        return $number;
    }
}