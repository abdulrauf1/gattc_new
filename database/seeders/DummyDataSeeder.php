<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /*
    |--------------------------------------------------------------------------
    | Helper: Insert only columns that exist in the table
    |--------------------------------------------------------------------------
    */
    private function insertExisting(string $table, array $data): int
    {
        $filtered = [];

        foreach ($data as $column => $value) {
            if (Schema::hasColumn($table, $column)) {
                $filtered[$column] = $value;
            }
        }

        return DB::table($table)->insertGetId($filtered);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Insert or retrieve record by unique field
    |--------------------------------------------------------------------------
    */
    private function firstOrCreate(
        string $table,
        string $uniqueColumn,
        string $uniqueValue,
        array $data
    ): int {
        $existing = DB::table($table)
            ->where($uniqueColumn, $uniqueValue)
            ->first();

        if ($existing) {
            return $existing->id;
        }

        return $this->insertExisting($table, $data);
    }

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        /*
        |--------------------------------------------------------------------------
        | 1. Course Categories
        |--------------------------------------------------------------------------
        */
        $categories = [];

        $categoryData = [
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'description' => 'Computer, software, networking and digital technology courses.',
                'status' => true,
            ],
            [
                'name' => 'Electrical Technology',
                'slug' => 'electrical-technology',
                'description' => 'Electrical installation, maintenance and industrial electrical courses.',
                'status' => true,
            ],
            [
                'name' => 'Mechanical Technology',
                'slug' => 'mechanical-technology',
                'description' => 'Mechanical, welding, fabrication and industrial maintenance courses.',
                'status' => true,
            ],
            [
                'name' => 'Renewable Energy',
                'slug' => 'renewable-energy',
                'description' => 'Solar PV, energy systems and green technology courses.',
                'status' => true,
            ],
            [
                'name' => 'Hospitality and Fashion',
                'slug' => 'hospitality-and-fashion',
                'description' => 'Tailoring, fashion designing and hospitality-related training.',
                'status' => true,
            ],
        ];

        foreach ($categoryData as $data) {
            $categories[$data['slug']] = $this->firstOrCreate(
                'course_categories',
                'slug',
                $data['slug'],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Courses
        |--------------------------------------------------------------------------
        */
        $courses = [];

        $courseData = [
            [
                'course_category_id' => $categories['information-technology'],
                'title' => 'Computer Operator',
                'slug' => 'computer-operator',
                'code' => 'CO-001',
                'short_description' => 'Professional computer operation and office automation training.',
                'description' => 'Complete training in computer fundamentals, MS Office, internet, email and office productivity.',
                'duration' => '6 Months',
                'qualification' => 'Middle / Matric',
                'fee' => 15000,
                'featured' => true,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['information-technology'],
                'title' => 'Diploma in Information Technology',
                'slug' => 'diploma-in-information-technology',
                'code' => 'DIT-001',
                'short_description' => 'Second shift DIT program for computer and information technology skills.',
                'description' => 'Comprehensive DIT program covering programming, databases, networking and office applications.',
                'duration' => '1 Year',
                'qualification' => 'Matric',
                'fee' => 25000,
                'featured' => true,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['information-technology'],
                'title' => 'Graphic Designing',
                'slug' => 'graphic-designing',
                'code' => 'GD-001',
                'short_description' => 'Creative graphic design training using modern design software.',
                'description' => 'Training in graphic design principles, branding, Photoshop, Illustrator and digital design.',
                'duration' => '3 Months',
                'qualification' => 'Middle / Matric',
                'fee' => 12000,
                'featured' => true,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['electrical-technology'],
                'title' => 'General Electrician',
                'slug' => 'general-electrician',
                'code' => 'GE-001',
                'short_description' => 'Domestic and commercial electrical installation training.',
                'description' => 'Electrical wiring, safety, troubleshooting, protection systems and maintenance.',
                'duration' => '6 Months',
                'qualification' => 'Middle',
                'fee' => 18000,
                'featured' => false,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['renewable-energy'],
                'title' => 'Solar PV Technician',
                'slug' => 'solar-pv-technician',
                'code' => 'SPV-001',
                'short_description' => 'Solar panel installation, maintenance and troubleshooting.',
                'description' => 'Solar PV system design, installation, batteries, charge controllers and inverters.',
                'duration' => '6 Months',
                'qualification' => 'Middle / Matric',
                'fee' => 20000,
                'featured' => true,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['mechanical-technology'],
                'title' => 'Welding',
                'slug' => 'welding',
                'code' => 'WELD-001',
                'short_description' => 'Industrial and general welding skills.',
                'description' => 'Arc welding, gas welding, safety procedures, fabrication and workshop practice.',
                'duration' => '6 Months',
                'qualification' => 'Middle',
                'fee' => 17000,
                'featured' => false,
                'status' => true,
            ],
            [
                'course_category_id' => $categories['hospitality-and-fashion'],
                'title' => 'Tailoring and Fashion Designing',
                'slug' => 'tailoring-fashion-designing',
                'code' => 'TFD-001',
                'short_description' => 'Dressmaking, tailoring and fashion design skills.',
                'description' => 'Cutting, stitching, measurement, dress designing and finishing techniques.',
                'duration' => '6 Months',
                'qualification' => 'Primary / Middle',
                'fee' => 10000,
                'featured' => false,
                'status' => true,
            ],
        ];

        foreach ($courseData as $data) {
            $courses[$data['slug']] = $this->firstOrCreate(
                'courses',
                'slug',
                $data['slug'],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Course Batches
        |--------------------------------------------------------------------------
        */
        $batchIds = [];

        $batchData = [
            [
                'course_id' => $courses['computer-operator'],
                'batch_name' => 'Computer Operator Morning Batch 2026',
                'start_date' => '2026-10-01',
                'end_date' => '2027-03-31',
                'capacity' => 40,
                'status' => 'open',
            ],
            [
                'course_id' => $courses['diploma-in-information-technology'],
                'batch_name' => 'DIT Second Shift Batch 2026',
                'start_date' => '2026-10-15',
                'end_date' => '2027-10-14',
                'capacity' => 50,
                'status' => 'upcoming',
            ],
            [
                'course_id' => $courses['graphic-designing'],
                'batch_name' => 'Graphic Designing Evening Batch',
                'start_date' => '2026-11-01',
                'end_date' => '2027-01-31',
                'capacity' => 30,
                'status' => 'upcoming',
            ],
            [
                'course_id' => $courses['solar-pv-technician'],
                'batch_name' => 'Solar PV Technician Batch',
                'start_date' => '2026-10-05',
                'end_date' => '2027-04-04',
                'capacity' => 35,
                'status' => 'open',
            ],
            [
                'course_id' => $courses['general-electrician'],
                'batch_name' => 'General Electrician Batch A',
                'start_date' => '2026-09-20',
                'end_date' => '2027-03-19',
                'capacity' => 35,
                'status' => 'ongoing',
            ],
        ];

        foreach ($batchData as $data) {
            $existing = DB::table('course_batches')
                ->where('course_id', $data['course_id'])
                ->where('batch_name', $data['batch_name'])
                ->first();

            if ($existing) {
                $batchIds[] = $existing->id;
            } else {
                $batchIds[] = $this->insertExisting('course_batches', $data);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Admission Sessions
        |--------------------------------------------------------------------------
        */
        $sessionId = $this->firstOrCreate(
            'admission_sessions',
            'session_code',
            'GATTC-2026-FALL',
            [
                'name' => 'Fall Admissions 2026',
                'session_code' => 'GATTC-2026-FALL',
                'opening_date' => '2026-09-01 09:00:00',
                'closing_date' => '2026-10-15 23:59:59',
                'is_open' => true,
                'description' => 'Fall 2026 admission session for selected GATTC technical and vocational courses.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 5. Session-Course Pivot
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('admission_session_course')) {
            foreach ($courses as $courseId) {
                $exists = DB::table('admission_session_course')
                    ->where('admission_session_id', $sessionId)
                    ->where('course_id', $courseId)
                    ->exists();

                if (!$exists) {
                    DB::table('admission_session_course')->insert([
                        'admission_session_id' => $sessionId,
                        'course_id' => $courseId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Announcements
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('announcements')) {
            $announcements = [

                [
                    'title' => 'Fall 2026 Admissions Are Open',
                    'slug' => 'fall-2026-admissions-are-open',
                    'content' => 'Admissions for Fall 2026 technical and vocational training courses are now open.',
                    'status' => true,
                    'published_at' => now(),
                ], 
                [
                    'title' => 'Orientation Session for New Students',
                    'slug' => 'orientation-session-for-new-students',
                    'content' => 'All newly admitted students must attend the orientation session at GATTC Hayatabad.',
                    'status' => true,
                    'published_at' => now(),
                ],
                [
                    'title' => 'Computer Operator Course Registration',
                    'slug' => 'computer-operator-course-registration',
                    'content' => 'Registration is available for the Computer Operator course. Contact the admission office for further details.',
                    'status' => true,
                    'published_at' => now(),
                ]
                ,
                
            ];

            foreach ($announcements as $announcement) {
                $this->firstOrCreate(
                    'announcements',
                    'slug',
                    $announcement['slug'],
                    $announcement
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Events
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('events')) {
            $events = [
                [
                    'title' => 'GATTC Annual Skills Exhibition',
                    'slug' => 'gattc-annual-skills-exhibition',
                    'short_description' => 'Students will display their technical projects and practical skills.',
                    'description' => 'Annual exhibition featuring student projects, prototypes and practical demonstrations.',
                    'event_date' => '2026-11-20',
                    'location' => 'GATTC Hayatabad, Peshawar',
                    'status' => true,
                ],
                [
                    'title' => 'Technical Career Awareness Seminar',
                    'slug' => 'technical-career-awareness-seminar',
                    'short_description' => 'A seminar on technical education and employment opportunities.',
                    'description' => 'Industry experts will discuss technical careers, freelancing and entrepreneurship.',
                    'event_date' => '2026-12-05',
                    'location' => 'GATTC Main Hall',
                    'status' => true,
                ],
            ];

            foreach ($events as $event) {
                $this->firstOrCreate(
                    'events',
                    'slug',
                    $event['slug'],
                    $event
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Alumni
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('alumni')) {
            $alumni = [
                [
                    'name' => 'Muhammad Bilal',
                    'course' => 'Computer Operator',
                    'passing_year' => 2023,
                    'occupation' => 'IT Support Officer',
                    'company' => 'Private Technology Company',
                    'description' => 'Successfully started a professional career after completing training at GATTC.',
                    'status' => true,
                ],
                [
                    'name' => 'Ayesha Khan',
                    'course' => 'Graphic Designing',
                    'passing_year' => 2024,
                    'occupation' => 'Freelance Graphic Designer',
                    'company' => 'Self Employed',
                    'description' => 'Working with local and international clients as a freelance designer.',
                    'status' => true,
                ],
                [
                    'name' => 'Sajid Ahmad',
                    'course' => 'Solar PV Technician',
                    'passing_year' => 2022,
                    'occupation' => 'Solar Technician',
                    'company' => 'Renewable Energy Services',
                    'description' => 'Providing solar installation and maintenance services.',
                    'status' => true,
                ],
            ];

            foreach ($alumni as $record) {
                $this->insertExisting('alumni', $record);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Gallery
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('galleries')) {
            $galleryId = $this->firstOrCreate(
                'galleries',
                'slug',
                'skills-exhibition-2026',
                [
                    'title' => 'Skills Exhibition 2026',
                    'slug' => 'skills-exhibition-2026',
                    'description' => 'Highlights from the GATTC skills exhibition.',
                    'status' => true,
                ]
            );

            if (Schema::hasTable('gallery_images')) {
                $galleryImages = [
                    [
                        'gallery_id' => $galleryId,
                        'title' => 'Student Project Display',
                        'image' => 'gallery/demo-project.jpg',
                        'sort_order' => 1,
                        'status' => true,
                    ],
                    [
                        'gallery_id' => $galleryId,
                        'title' => 'Electrical Workshop',
                        'image' => 'gallery/electrical-workshop.jpg',
                        'sort_order' => 2,
                        'status' => true,
                    ],
                    [
                        'gallery_id' => $galleryId,
                        'title' => 'Computer Lab',
                        'image' => 'gallery/computer-lab.jpg',
                        'sort_order' => 3,
                        'status' => true,
                    ],
                ];

                foreach ($galleryImages as $image) {
                    $this->insertExisting('gallery_images', $image);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Bank Accounts
        |--------------------------------------------------------------------------
        */
        $bankAccounts = [];

        $bankData = [
            [
                'account_title' => 'GATTC Regular Admission Account',
                'account_number' => '000000000001',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Hayatabad Branch',
                'branch_code' => '0001',
                'iban' => 'PK00BAKH0000000000000001',
                'account_type' => 'regular_admission',
                'purpose' => 'Regular admissions',
                'status' => true,
            ],
            [
                'account_title' => 'GATTC Second Shift DIT Account',
                'account_number' => '000000000002',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Hayatabad Branch',
                'branch_code' => '0001',
                'iban' => 'PK00BAKH0000000000000002',
                'account_type' => 'second_shift_dit',
                'purpose' => 'Second shift DIT courses',
                'status' => true,
            ],
            [
                'account_title' => 'GATTC Hostel Fee Account',
                'account_number' => '000000000003',
                'bank_name' => 'Bank of Khyber',
                'branch_name' => 'Hayatabad Branch',
                'branch_code' => '0001',
                'iban' => 'PK00BAKH0000000000000003',
                'account_type' => 'hostel',
                'purpose' => 'Hostel fee payments',
                'status' => true,
            ],
        ];

        foreach ($bankData as $data) {
            $bankAccounts[$data['account_type']] = $this->firstOrCreate(
                'bank_accounts',
                'account_number',
                $data['account_number'],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. Fee Types
        |--------------------------------------------------------------------------
        */
        $feeTypes = [];

        $feeTypeData = [
            [
                'name' => 'Regular Admission',
                'code' => 'REGULAR_ADMISSION',
                'description' => 'Regular admission fee.',
                'status' => true,
            ],
            [
                'name' => 'Second Shift DIT',
                'code' => 'SECOND_SHIFT_DIT',
                'description' => 'Second shift DIT admission fee.',
                'status' => true,
            ],
            [
                'name' => 'Hostel Fee',
                'code' => 'HOSTEL',
                'description' => 'Hostel fee for eligible students.',
                'status' => true,
            ],
        ];

        foreach ($feeTypeData as $data) {
            $feeTypes[$data['code']] = $this->firstOrCreate(
                'fee_types',
                'code',
                $data['code'],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Fee Configurations
        |--------------------------------------------------------------------------
        */
        $feeConfigurationId = null;

        if (Schema::hasTable('fee_configurations')) {
            $feeConfigurationId = $this->insertExisting(
                'fee_configurations',
                [
                    'fee_type_id' => $feeTypes['REGULAR_ADMISSION'],
                    'bank_account_id' => $bankAccounts['regular_admission'],
                    'admission_session_id' => $sessionId,
                    'course_id' => $courses['computer-operator'],
                    'title' => 'Computer Operator Admission Fee',
                    'amount' => 15000,
                    'effective_from' => '2026-09-01',
                    'effective_until' => '2026-10-15',
                    'mandatory' => true,
                    'status' => true,
                ]
            );

            $this->insertExisting(
                'fee_configurations',
                [
                    'fee_type_id' => $feeTypes['SECOND_SHIFT_DIT'],
                    'bank_account_id' => $bankAccounts['second_shift_dit'],
                    'admission_session_id' => $sessionId,
                    'course_id' => $courses['diploma-in-information-technology'],
                    'title' => 'Second Shift DIT Admission Fee',
                    'amount' => 25000,
                    'effective_from' => '2026-09-01',
                    'effective_until' => '2026-10-15',
                    'mandatory' => true,
                    'status' => true,
                ]
            );

            $this->insertExisting(
                'fee_configurations',
                [
                    'fee_type_id' => $feeTypes['HOSTEL'],
                    'bank_account_id' => $bankAccounts['hostel'],
                    'admission_session_id' => $sessionId,
                    'course_id' => $courses['computer-operator'],
                    'title' => 'Hostel Fee',
                    'amount' => 12000,
                    'effective_from' => '2026-09-01',
                    'effective_until' => '2026-10-15',
                    'mandatory' => false,
                    'status' => true,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. Dummy Admissions
        |--------------------------------------------------------------------------
        */
        $admissionIds = [];

        $admissionData = [
            [
                'application_no' => 'GATTC-A-2026-000001',
                'admission_no' => 'GATTC-2026-000001',
                'admission_session_id' => $sessionId,
                'course_id' => $courses['computer-operator'],
                'course_batch_id' => $batchIds[0],
                'full_name' => 'Ahmad Ali',
                'father_name' => 'Muhammad Ali',
                'date_of_birth' => '2005-04-12',
                'gender' => 'male',
                'cnic' => '16101-1234567-1',
                'phone' => '03001234567',
                'email' => 'ahmad.ali@example.com',
                'address' => 'Hayatabad, Peshawar',
                'district' => 'Peshawar',
                'tehsil' => 'Peshawar',
                'qualification' => 'Matric',
                'institute' => 'Government High School Peshawar',
                'passing_year' => 2023,
                'admission_status' => 'admitted',
                'submitted_at' => now()->subDays(10),
                'fee_verified_at' => now()->subDays(8),
                'admitted_at' => now()->subDays(8),
                'remarks' => 'Dummy admitted applicant.',
            ],
            [
                'application_no' => 'GATTC-A-2026-000002',
                'admission_no' => null,
                'admission_session_id' => $sessionId,
                'course_id' => $courses['graphic-designing'],
                'course_batch_id' => $batchIds[2],
                'full_name' => 'Fatima Bibi',
                'father_name' => 'Abdul Rahman',
                'date_of_birth' => '2006-08-21',
                'gender' => 'female',
                'cnic' => '16101-2345678-2',
                'phone' => '03111234567',
                'email' => 'fatima.bibi@example.com',
                'address' => 'University Town, Peshawar',
                'district' => 'Peshawar',
                'tehsil' => 'Peshawar',
                'qualification' => 'Matric',
                'institute' => 'Private School Peshawar',
                'passing_year' => 2024,
                'admission_status' => 'fee_pending',
                'submitted_at' => now()->subDays(3),
                'remarks' => 'Waiting for fee payment.',
            ],
            [
                'application_no' => 'GATTC-A-2026-000003',
                'admission_no' => null,
                'admission_session_id' => $sessionId,
                'course_id' => $courses['solar-pv-technician'],
                'course_batch_id' => $batchIds[3],
                'full_name' => 'Hassan Khan',
                'father_name' => 'Samiullah Khan',
                'date_of_birth' => '2004-11-03',
                'gender' => 'male',
                'cnic' => '16101-3456789-3',
                'phone' => '03221234567',
                'email' => 'hassan.khan@example.com',
                'address' => 'Board Bazaar, Peshawar',
                'district' => 'Peshawar',
                'tehsil' => 'Peshawar',
                'qualification' => 'Middle',
                'institute' => 'Government School Peshawar',
                'passing_year' => 2022,
                'admission_status' => 'application',
                'submitted_at' => now()->subDay(),
                'remarks' => 'New dummy application.',
            ],
        ];

        foreach ($admissionData as $data) {
            $admissionIds[] = $this->firstOrCreate(
                'admissions',
                'application_no',
                $data['application_no'],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 14. Dummy Voucher
        |--------------------------------------------------------------------------
        */
        $voucherId = null;

        if (Schema::hasTable('vouchers') && $feeConfigurationId) {
            $voucherId = $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-V-2026-000001',
                [
                    'voucher_no' => 'GATTC-V-2026-000001',
                    'voucher_category' => 'admission_fee',  
                    'admission_session_id' => $sessionId,
                    'fee_configuration_id' => $feeConfigurationId,
                    'admission_id' => $admissionIds[0],
                    'course_id' => $courses['computer-operator'],
                    'course_batch_id' => $batchIds[0],
                    'applicant_name' => 'Ahmad Ali',
                    'father_name' => 'Muhammad Ali',
                    'cnic' => '16101-1234567-1',
                    'phone' => '03001234567',
                    'amount' => 15000,
                    'issue_date' => now()->subDays(10)->toDateString(),
                    'due_date' => now()->subDays(3)->toDateString(),
                    'status' => 'paid',
                    'remarks' => 'Dummy paid voucher.',
                    'bank_name' => 'Bank of Khyber',
                    'account_title' => 'GATTC Regular Admission Account',
                    'account_number' => '000000000001',
                    'iban' => 'PK00BAKH0000000000000001',
                    'branch_name' => 'Hayatabad Branch',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 15. Dummy Fee Payment
        |--------------------------------------------------------------------------
        */
        $paymentId = null;

        if (Schema::hasTable('fee_payments') && $voucherId) {
            $paymentId = $this->insertExisting(
                'fee_payments',
                [
                    'voucher_id' => $voucherId,
                    'bank_transaction_no' => 'BANK-TXN-2026-000001',
                    'deposit_slip_no' => 'SLIP-2026-000001',
                    'payment_date' => now()->subDays(9)->toDateString(),
                    'amount' => 15000,
                    'payment_method' => 'bank',
                    'status' => 'verified',
                    'proof_document' => null,
                    'verified_at' => now()->subDays(8),
                    'remarks' => 'Dummy verified payment.',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 16. Fee Payment History
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('fee_payment_histories') && $voucherId) {
            $this->insertExisting(
                'fee_payment_histories',
                [
                    'voucher_id' => $voucherId,
                    'fee_payment_id' => $paymentId,
                    'action' => 'voucher_generated',
                    'old_status' => null,
                    'new_status' => 'generated',
                    'amount' => 15000,
                    'remarks' => 'Dummy voucher generated.',
                ]
            );

            if ($paymentId) {
                $this->insertExisting(
                    'fee_payment_histories',
                    [
                        'voucher_id' => $voucherId,
                        'fee_payment_id' => $paymentId,
                        'action' => 'payment_verified',
                        'old_status' => 'pending',
                        'new_status' => 'verified',
                        'amount' => 15000,
                        'remarks' => 'Dummy payment verified.',
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 17. Fee Receipt
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('fee_receipts') && $paymentId) {
            $this->firstOrCreate(
                'fee_receipts',
                'receipt_no',
                'GATTC-R-2026-000001',
                [
                    'fee_payment_id' => $paymentId,
                    'receipt_no' => 'GATTC-R-2026-000001',
                    'issued_at' => now()->subDays(8),
                    'remarks' => 'Dummy fee receipt.',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 18. Website Settings
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('website_settings')) {
            $settings = [
                'site_name' => 'Government Advance Technical Training Centre',
                'short_name' => 'GATTC',
                'address' => '16-A Industrial Estate, Opposite BRT TEVTA Stop, Hayatabad, Peshawar',
                'phone' => '091-5881389',
                'email' => 'info@gattc.edu.pk',
                'website' => 'https://gattc.edu.pk',
                'facebook' => 'GATTC Peshawar',
                'footer_text' => 'Empowering youth through technical and vocational education.',
            ];

            foreach ($settings as $key => $value) {
                $exists = DB::table('website_settings')
                    ->where('key', $key)
                    ->exists();

                if (!$exists) {
                    $this->insertExisting('website_settings', [
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('GATTC dummy data has been seeded successfully.');
    }
}