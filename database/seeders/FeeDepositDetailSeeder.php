<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\FeeDepositDetail;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class FeeDepositDetailSeeder extends Seeder
{
    /*
    |--------------------------------------------------------------------------
    | Standard Deposit Lines
    |--------------------------------------------------------------------------
    */

    private array $depositLines = [

        [
            'fee_code' => 'admission_fee',
            'fee_name' => 'Admission Fee',
            'sort_order' => 1,
        ],

        [
            'fee_code' => 'tuition_fee',
            'fee_name' => 'Tuition Fee',
            'sort_order' => 2,
        ],

        [
            'fee_code' => 'board_registration_fee',
            'fee_name' => 'University/Board Registration Fee',
            'sort_order' => 3,
        ],

        [
            'fee_code' => 'certificate_fee',
            'fee_name' => 'Degree/Diploma/Certificate Fee',
            'sort_order' => 4,
        ],

        [
            'fee_code' => 'dmc_fee',
            'fee_name' => 'DMC Fee',
            'sort_order' => 5,
        ],

        [
            'fee_code' => 'dmc_verification_fee',
            'fee_name' => 'DMC Verification Fee',
            'sort_order' => 6,
        ],

        [
            'fee_code' => 'examination_fee',
            'fee_name' => 'Examination Fee',
            'sort_order' => 7,
        ],

        [
            'fee_code' => 'fine_fee',
            'fee_name' => 'Fine/Struck Off, if any',
            'sort_order' => 8,
        ],

        [
            'fee_code' => 'identity_card_fee',
            'fee_name' => 'Identity Card Fee',
            'sort_order' => 9,
        ],

        [
            'fee_code' => 'late_fee',
            'fee_name' => 'Late Fee',
            'sort_order' => 10,
        ],

        [
            'fee_code' => 'miscellaneous_charges',
            'fee_name' => 'Miscellaneous Charges',
            'sort_order' => 11,
        ],

        [
            'fee_code' => 're_admission_fee',
            'fee_name' => 'Re-Admission Fee',
            'sort_order' => 12,
        ],

        [
            'fee_code' => 'sports_fee',
            'fee_name' => 'Sports Fee',
            'sort_order' => 13,
        ],

        [
            'fee_code' => 'masjid_fee',
            'fee_name' => 'Masjid Fee',
            'sort_order' => 14,
        ],

        [
            'fee_code' => 'security_fee',
            'fee_name' => 'Security, if any',
            'sort_order' => 15,
        ],

        [
            'fee_code' => 'other_fee',
            'fee_name' => 'Any Other',
            'sort_order' => 16,
        ],
    ];


    public function run(): void
    {
        /*
         * --------------------------------------------------------------
         * Course fee details
         * --------------------------------------------------------------
         *
         * Bootstrap only:
         * the existing courses.fee_amount is initially placed under
         * Admission Fee so that totals remain consistent.
         *
         * The administration should edit the individual lines with
         * the official GATTC fee breakdown.
         */
        $courses = Course::query()
            ->where('status', true)
            ->get();

        foreach ($courses as $course) {

            foreach ($this->depositLines as $line) {

                $amount =
                    $line['fee_code'] === 'admission_fee'
                        ? (float) $course->fee_amount
                        : 0;

                FeeDepositDetail::updateOrCreate(
                    [
                        'course_id' =>
                            $course->id,

                        'fee_category' =>
                            $course->course_type,

                        'fee_code' =>
                            $line['fee_code'],
                    ],
                    [
                        'fee_name' =>
                            $line['fee_name'],

                        'amount' =>
                            $amount,

                        'mandatory' =>
                            $line['fee_code'] === 'admission_fee',

                        'sort_order' =>
                            $line['sort_order'],

                        'status' =>
                            true,
                    ]
                );
            }
        }


        /*
         * --------------------------------------------------------------
         * Hostel
         * --------------------------------------------------------------
         */
        $hostelFeeValue = WebsiteSetting::query()
            ->where('key', 'hostel_fee')
            ->value('value');

        $hostelFee = is_numeric($hostelFeeValue)
            ? (float) $hostelFeeValue
            : 0;

        foreach ($this->depositLines as $line) {

            $amount =
                $line['fee_code'] === 'admission_fee'
                    ? $hostelFee
                    : 0;

            FeeDepositDetail::updateOrCreate(
                [
                    'course_id' =>
                        null,

                    'fee_category' =>
                        'hostel',

                    'fee_code' =>
                        $line['fee_code'],
                ],
                [
                    'fee_name' =>
                        $line['fee_name'],

                    'amount' =>
                        $amount,

                    'mandatory' =>
                        $line['fee_code'] === 'admission_fee',

                    'sort_order' =>
                        $line['sort_order'],

                    'status' =>
                        true,
                ]
            );
        }
    }
}