<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::updateOrCreate(
            [
                'account_type' => 'regular_admission',
            ],
            [
                'account_title' => 'GATTC Regular Admission Account',
                'account_number' => 'ENTER_ACCOUNT_NUMBER',
                'bank_name' => 'ENTER_BANK_NAME',
                'branch_name' => null,
                'branch_code' => null,
                'iban' => null,
                'purpose' => 'Regular Admission Fees',
                'status' => true,
            ]
        );

        BankAccount::updateOrCreate(
            [
                'account_type' => 'second_shift_dit',
            ],
            [
                'account_title' => 'GATTC Second Shift DIT Account',
                'account_number' => 'ENTER_ACCOUNT_NUMBER',
                'bank_name' => 'ENTER_BANK_NAME',
                'branch_name' => null,
                'branch_code' => null,
                'iban' => null,
                'purpose' => 'Second Shift DIT Fees',
                'status' => true,
            ]
        );

        BankAccount::updateOrCreate(
            [
                'account_type' => 'hostel',
            ],
            [
                'account_title' => 'GATTC Hostel Fee Account',
                'account_number' => 'ENTER_ACCOUNT_NUMBER',
                'bank_name' => 'ENTER_BANK_NAME',
                'branch_name' => null,
                'branch_code' => null,
                'iban' => null,
                'purpose' => 'Hostel Fees',
                'status' => true,
            ]
        );
    }
}