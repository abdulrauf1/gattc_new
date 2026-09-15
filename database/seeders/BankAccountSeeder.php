<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Regular Courses
        |--------------------------------------------------------------------------
        */
        DB::table('bank_accounts')->updateOrInsert(
            [
                'account_number' => '003000924945',
            ],
            [
                'account_title' => 'PRINCIPAL GOVT ADV TECH TRG CENT',
                'account_number' => '003000924945',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK35KHYB0101003000924945',
                'purpose' => 'Regular Course Fees',
                'status' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. DIT / Second Shift
        |--------------------------------------------------------------------------
        */
        DB::table('bank_accounts')->updateOrInsert(
            [
                'account_number' => '003001038884',
            ],
            [
                'account_title' => 'PRINCIPAL ADVANCE TECHNICAL TRAININ',
                'account_number' => '003001038884',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK37KHYBB0101003001038884',
                'purpose' => 'DIT / Second Shift Course Fees',
                'status' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. Hostel
        |--------------------------------------------------------------------------
        */
        DB::table('bank_accounts')->updateOrInsert(
            [
                'account_number' => '003000927828',
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
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Private / IMC
        |--------------------------------------------------------------------------
        */
        DB::table('bank_accounts')->updateOrInsert(
            [
                'account_number' => '003003929328',
            ],
            [
                'account_title' => 'GOVERNMENT ADVANCE TECHNICAL TRAINING CE',
                'account_number' => '003003929328',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Industrial Area, Peshawar',
                'branch_code' => '0101',
                'iban' => 'PK78KHYB0101003003929328',
                'purpose' => 'Private Courses / Payment Through IMC',
                'status' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->command->info('4 GATTC bank accounts seeded successfully.');
    }
}