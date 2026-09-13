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
                'account_title' => 'PRINCIPAL GOVT ADV TECH TRG CENT',
                'account_number' => '003000924945',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK35KHYB0101003000924945',
                'purpose' => 'Regular Admission Fees',
                'status' => true,
            ]
        );

        BankAccount::updateOrCreate(
            [
                'account_type' => 'second_shift_dit',
            ],
            [
                'account_title' => 'PRINCIPAL ADVANCE TECHNICAL TRAININ',
                'account_number' => '003001038884',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK37KHYBB0101003001038884',
                'purpose' => 'Second Shift DIT Fees',
                'status' => true,
            ]
        );

        BankAccount::updateOrCreate(
            [
                'account_type' => 'hostel',
            ],
            [
                'account_title' => 'SUPRENTENDENT HOSTEL ATTC',
                'account_number' => '003000927828',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK85KHYB0101003000927828',
                'purpose' => 'Hostel Fees',
                'status' => true,
            ]
        );

            BankAccount::updateOrCreate(
            [
                'account_type' => 'imc',
            ],
            [
                'account_title' => 'GOVERNMENT ADVANCE TECHNICAL TRAINING CE',
                'account_number' => '003003929328',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK78KHYB0101003003929328',
                'purpose' => 'Payment Through IMC',
                'status' => true,
            ]
        );
    }
}