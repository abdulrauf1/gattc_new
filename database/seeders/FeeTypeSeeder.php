<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Regular Admission',
                'code' => 'REGULAR_ADMISSION',
                'description' => 'Regular admission fee.',
                'status' => true,
            ],

            [
                'name' => 'Second Shift DIT',
                'code' => 'SECOND_SHIFT_DIT',
                'description' => 'Second shift DIT admission/course fee.',
                'status' => true,
            ],

            [
                'name' => 'Hostel Fee',
                'code' => 'HOSTEL',
                'description' => 'Hostel accommodation fee.',
                'status' => true,
            ],
        ];

        foreach ($types as $type) {
            FeeType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}