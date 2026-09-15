<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class DummyDataSeeder extends Seeder
{
    /**
     * Insert only columns that actually exist in the table.
     */
    private function insertExisting(string $table, array $data): int
    {
        $filtered = [];

        foreach ($data as $column => $value) {
            if (Schema::hasColumn($table, $column)) {
                $filtered[$column] = $value;
            }
        }

        if (Schema::hasColumn($table, 'created_at')
            && !array_key_exists('created_at', $filtered)) {
            $filtered['created_at'] = now();
        }

        if (Schema::hasColumn($table, 'updated_at')
            && !array_key_exists('updated_at', $filtered)) {
            $filtered['updated_at'] = now();
        }

        return DB::table($table)->insertGetId($filtered);
    }

    /**
     * Find record by a unique column or create it.
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

    /**
     * Find a record by a specific column/value.
     */
    private function findRecord(
        string $table,
        string $column,
        string $value,
        string $description
    ): object {
        $record = DB::table($table)
            ->where($column, $value)
            ->first();

        if (!$record) {
            throw new RuntimeException(
                "{$description} was not found in table '{$table}'."
            );
        }

        return $record;
    }

    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 1. BANK ACCOUNTS
            |--------------------------------------------------------------------------
            |
            | BankAccountSeeder must run before this seeder.
            |
            */
            $regularBank = $this->findRecord(
                'bank_accounts',
                'account_number',
                '003000924945',
                'Regular course bank account'
            );

            $ditBank = $this->findRecord(
                'bank_accounts',
                'account_number',
                '003001038884',
                'DIT / Second Shift bank account'
            );

            $hostelBank = $this->findRecord(
                'bank_accounts',
                'account_number',
                '003000927828',
                'Hostel bank account'
            );

            $privateBank = $this->findRecord(
                'bank_accounts',
                'account_number',
                '003003929328',
                'Private / IMC bank account'
            );


            /*
            |--------------------------------------------------------------------------
            | 2. COURSE CATEGORIES
            |--------------------------------------------------------------------------
            */
            $categories = [];

            $categoryData = [

                [
                    'name' => 'Information Technology',
                    'slug' => 'information-technology',
                    'description' =>
                        'Computer, software, networking and digital technology courses.',
                    'status' => true,
                ],

                [
                    'name' => 'Electrical Technology',
                    'slug' => 'electrical-technology',
                    'description' =>
                        'Electrical installation, maintenance and industrial electrical courses.',
                    'status' => true,
                ],

                [
                    'name' => 'Mechanical Technology',
                    'slug' => 'mechanical-technology',
                    'description' =>
                        'Mechanical, welding, fabrication and industrial maintenance courses.',
                    'status' => true,
                ],

                [
                    'name' => 'Renewable Energy',
                    'slug' => 'renewable-energy',
                    'description' =>
                        'Solar PV, energy systems and green technology courses.',
                    'status' => true,
                ],

                [
                    'name' => 'Hospitality and Fashion',
                    'slug' => 'hospitality-and-fashion',
                    'description' =>
                        'Tailoring, fashion designing and vocational training.',
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
            | 3. COURSES
            |--------------------------------------------------------------------------
            |
            | New simplified course structure:
            |
            | course_category_id
            | bank_account_id
            | title
            | slug
            | course_type
            | description
            | duration
            | eligibility
            | fee_amount
            | image
            | sort_order
            | status
            |
            */
            $courses = [];

            $courseData = [

                // Regular
                [
                    'course_category_id' => $categories['information-technology'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'Computer Operator',
                    'slug' => 'computer-operator',
                    'course_type' => 'regular',
                    'description' =>
                        'Professional computer operation and office automation training.',
                    'duration' => '6 Months',
                    'eligibility' => 'Middle / Matric',
                    'fee_amount' => 15000,
                    'image' => null,
                    'sort_order' => 1,
                    'status' => true,
                ],

                // DIT
                [
                    'course_category_id' => $categories['information-technology'],
                    'bank_account_id' => $ditBank->id,
                    'title' => 'Diploma in Information Technology',
                    'slug' => 'diploma-in-information-technology',
                    'course_type' => 'dit',
                    'description' =>
                        'Second shift DIT program covering programming, databases, networking and office applications.',
                    'duration' => '1 Year',
                    'eligibility' => 'Matric',
                    'fee_amount' => 25000,
                    'image' => null,
                    'sort_order' => 2,
                    'status' => true,
                ],

                // Regular
                [
                    'course_category_id' => $categories['information-technology'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'Graphic Designing',
                    'slug' => 'graphic-designing',
                    'course_type' => 'regular',
                    'description' =>
                        'Creative graphic design training using modern design software.',
                    'duration' => '3 Months',
                    'eligibility' => 'Middle / Matric',
                    'fee_amount' => 12000,
                    'image' => null,
                    'sort_order' => 3,
                    'status' => true,
                ],

                // Regular
                [
                    'course_category_id' => $categories['electrical-technology'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'General Electrician',
                    'slug' => 'general-electrician',
                    'course_type' => 'regular',
                    'description' =>
                        'Domestic and commercial electrical installation and maintenance.',
                    'duration' => '6 Months',
                    'eligibility' => 'Middle',
                    'fee_amount' => 18000,
                    'image' => null,
                    'sort_order' => 4,
                    'status' => true,
                ],

                // Regular
                [
                    'course_category_id' => $categories['renewable-energy'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'Solar PV Technician',
                    'slug' => 'solar-pv-technician',
                    'course_type' => 'regular',
                    'description' =>
                        'Solar PV installation, maintenance, batteries, inverters and troubleshooting.',
                    'duration' => '6 Months',
                    'eligibility' => 'Middle / Matric',
                    'fee_amount' => 20000,
                    'image' => null,
                    'sort_order' => 5,
                    'status' => true,
                ],

                // Regular
                [
                    'course_category_id' => $categories['mechanical-technology'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'Welding',
                    'slug' => 'welding',
                    'course_type' => 'regular',
                    'description' =>
                        'Arc welding, gas welding, fabrication, safety and workshop practice.',
                    'duration' => '6 Months',
                    'eligibility' => 'Middle',
                    'fee_amount' => 17000,
                    'image' => null,
                    'sort_order' => 6,
                    'status' => true,
                ],

                // Regular
                [
                    'course_category_id' => $categories['hospitality-and-fashion'],
                    'bank_account_id' => $regularBank->id,
                    'title' => 'Tailoring and Fashion Designing',
                    'slug' => 'tailoring-fashion-designing',
                    'course_type' => 'regular',
                    'description' =>
                        'Cutting, stitching, measurement and fashion designing techniques.',
                    'duration' => '6 Months',
                    'eligibility' => 'Primary / Middle',
                    'fee_amount' => 10000,
                    'image' => null,
                    'sort_order' => 7,
                    'status' => true,
                ],

                // Private / IMC
                [
                    'course_category_id' => $categories['information-technology'],
                    'bank_account_id' => $privateBank->id,
                    'title' => 'AutoCAD and Technical Drawing',
                    'slug' => 'autocad-technical-drawing',
                    'course_type' => 'private',
                    'description' =>
                        'Private / IMC technical drawing and AutoCAD training.',
                    'duration' => '3 Months',
                    'eligibility' => 'Matric / DAE',
                    'fee_amount' => 22000,
                    'image' => null,
                    'sort_order' => 8,
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
            | 4. ADMISSION SESSION
            |--------------------------------------------------------------------------
            |
            | ACTUAL TABLE COLUMNS:
            |
            | title
            | opening_date
            | closing_date
            | is_open
            | description
            |
            */
            $session = DB::table('admission_sessions')
                ->where('title', 'Fall Admissions 2026')
                ->first();

            if ($session) {

                $sessionId = $session->id;

            } else {

                $sessionId = $this->insertExisting(
                    'admission_sessions',
                    [
                        'title' => 'Fall Admissions 2026',
                        'opening_date' => '2026-09-01 09:00:00',
                        'closing_date' => '2026-10-15 23:59:59',
                        'is_open' => true,
                        'description' =>
                            'Fall 2026 admission session for GATTC technical and vocational courses.',
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | 5. ATTACH COURSES TO ADMISSION SESSION
            |--------------------------------------------------------------------------
            */
            $sessionCourses = [

                'computer-operator',
                'diploma-in-information-technology',
                'graphic-designing',
                'general-electrician',
                'solar-pv-technician',
                'welding',
                'tailoring-fashion-designing',
                'autocad-technical-drawing',

            ];

            foreach ($sessionCourses as $courseSlug) {

                $courseId = $courses[$courseSlug];

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


            /*
            |--------------------------------------------------------------------------
            | 6. ANNOUNCEMENTS
            |--------------------------------------------------------------------------
            */
            if (Schema::hasTable('announcements')) {

                $announcements = [

                    [
                        'title' => 'Fall 2026 Admissions Are Open',
                        'slug' => 'fall-2026-admissions-are-open',
                        'content' =>
                            'Admissions for Fall 2026 technical and vocational courses are now open.',
                        'status' => true,
                        'published_at' => now(),
                    ],

                    [
                        'title' => 'Orientation Session for New Students',
                        'slug' => 'orientation-session-for-new-students',
                        'content' =>
                            'All newly admitted students must attend the orientation session at GATTC Hayatabad.',
                        'status' => true,
                        'published_at' => now(),
                    ],

                    [
                        'title' => 'Computer Operator Course Registration',
                        'slug' => 'computer-operator-course-registration',
                        'content' =>
                            'Registration is available for the Computer Operator course.',
                        'status' => true,
                        'published_at' => now(),
                    ],

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
            | 7. EVENTS
            |--------------------------------------------------------------------------
            */
            if (Schema::hasTable('events')) {

                $events = [

                    [
                        'title' => 'GATTC Annual Skills Exhibition',
                        'slug' => 'gattc-annual-skills-exhibition',
                        'short_description' =>
                            'Students will display their technical projects and practical skills.',
                        'description' =>
                            'Annual exhibition featuring student projects, prototypes and practical demonstrations.',
                        'event_date' => '2026-11-20',
                        'location' => 'GATTC Hayatabad, Peshawar',
                        'status' => true,
                    ],

                    [
                        'title' => 'Technical Career Awareness Seminar',
                        'slug' => 'technical-career-awareness-seminar',
                        'short_description' =>
                            'A seminar on technical education and employment opportunities.',
                        'description' =>
                            'Industry experts will discuss technical careers, freelancing and entrepreneurship.',
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
            | 8. ALUMNI
            |--------------------------------------------------------------------------

            */
            if (Schema::hasTable('alumnis')) {

                $alumni = [

                    [
                        'name' => 'Muhammad Bilal',
                        'email' => 'bilal.alumni@gattc.edu.pk',
                        'phone' => '03001234567',
                        'course' => 'Computer Operator',
                        'graduation_year' => 2023,
                        'organization' => 'Private Technology Company',
                        'designation' => 'IT Support Officer',
                        'bio' => 'Successfully started a professional IT career after completing training at GATTC.',
                        'photo' => null,
                        'status' => true,
                    ],

                    [
                        'name' => 'Ayesha Khan',
                        'email' => 'ayesha.alumni@gattc.edu.pk',
                        'phone' => '03111234567',
                        'course' => 'Graphic Designing',
                        'graduation_year' => 2024,
                        'organization' => 'Self Employed',
                        'designation' => 'Freelance Graphic Designer',
                        'bio' => 'Working with local and international clients as a freelance graphic designer.',
                        'photo' => null,
                        'status' => true,
                    ],

                    [
                        'name' => 'Sajid Ahmad',
                        'email' => 'sajid.alumni@gattc.edu.pk',
                        'phone' => '03221234567',
                        'course' => 'Solar PV Technician',
                        'graduation_year' => 2022,
                        'organization' => 'Renewable Energy Services',
                        'designation' => 'Solar Technician',
                        'bio' => 'Providing solar installation and maintenance services.',
                        'photo' => null,
                        'status' => true,
                    ],

                ];

                foreach ($alumni as $record) {

                    $exists = DB::table('alumnis')
                        ->where('email', $record['email'])
                        ->exists();

                    if (!$exists) {
                        $this->insertExisting(
                            'alumnis',
                            $record
                        );
                    }
                }
            }


           /*
            |--------------------------------------------------------------------------
            | 9. GALLERY
            |--------------------------------------------------------------------------
            */
            if (Schema::hasTable('galleries')) {

                /*
                |--------------------------------------------------------------------------
                | Gallery
                |--------------------------------------------------------------------------
                */
                $gallery = DB::table('galleries')
                    ->where('slug', 'skills-exhibition-2026')
                    ->first();

                if ($gallery) {

                    $galleryId = $gallery->id;

                } else {

                    $galleryId = $this->insertExisting(
                        'galleries',
                        [
                            'title' => 'Skills Exhibition 2026',
                            'slug' => 'skills-exhibition-2026',
                            'description' => 'Highlights from the GATTC skills exhibition.',
                            'status' => true,
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Gallery Images
                |--------------------------------------------------------------------------
                */
                if (Schema::hasTable('gallery_images')) {

                    $galleryImages = [

                        [
                            'gallery_id' => $galleryId,
                            'image' => 'gallery/demo-project.jpg',
                            'caption' => 'Student Project Display',
                            'sort_order' => 1,
                        ],

                        [
                            'gallery_id' => $galleryId,
                            'image' => 'gallery/electrical-workshop.jpg',
                            'caption' => 'Electrical Workshop',
                            'sort_order' => 2,
                        ],

                        [
                            'gallery_id' => $galleryId,
                            'image' => 'gallery/computer-lab.jpg',
                            'caption' => 'Computer Lab',
                            'sort_order' => 3,
                        ],

                    ];

                    foreach ($galleryImages as $image) {

                        $exists = DB::table('gallery_images')
                            ->where('gallery_id', $galleryId)
                            ->where('image', $image['image'])
                            ->exists();

                        if (!$exists) {

                            $this->insertExisting(
                                'gallery_images',
                                $image
                            );
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 10. DUMMY APPROVED ADMISSION
            |--------------------------------------------------------------------------
            |
            | ACTUAL admissions columns:
            |
            | admission_no
            | admission_session_id
            | course_id
            | student_name
            | father_name
            | cnic
            | date_of_birth
            | gender
            | phone
            | email
            | address
            | status
            | remarks
            |
            */
            $admissionId = $this->firstOrCreate(
                'admissions',
                'admission_no',
                'GATTC-2026-000001',
                [

                    'admission_no' => 'GATTC-2026-000001',

                    'admission_session_id' => $sessionId,

                    'course_id' => $courses['computer-operator'],

                    'student_name' => 'Ahmad Ali',

                    'father_name' => 'Muhammad Ali',

                    'cnic' => '16101-1234567-1',

                    'date_of_birth' => '2005-04-12',

                    'gender' => 'male',

                    'phone' => '03001234567',

                    'email' => 'ahmad.ali@example.com',

                    'address' => 'Hayatabad, Peshawar',

                    'status' => 'approved',

                    'remarks' => 'Dummy approved admission.',

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 11. PAID ADMISSION VOUCHER
            |--------------------------------------------------------------------------
            */
            $paidVoucherId = $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-V-2026-000001',
                [

                    'voucher_no' => 'GATTC-V-2026-000001',

                    'admission_session_id' => $sessionId,

                    'course_id' => $courses['computer-operator'],

                    'admission_id' => $admissionId,

                    'bank_account_id' => $regularBank->id,

                    'voucher_type' => 'admission',

                    'applicant_name' => 'Ahmad Ali',

                    'father_name' => 'Muhammad Ali',

                    'cnic' => '16101-1234567-1',

                    'date_of_birth' => '2005-04-12',

                    'gender' => 'male',

                    'phone' => '03001234567',

                    'email' => 'ahmad.ali@example.com',

                    'address' => 'Hayatabad, Peshawar',

                    'amount' => 15000,

                    'issue_date' => now()->subDays(10)->toDateString(),

                    'due_date' => now()->subDays(3)->toDateString(),

                    'status' => 'paid',

                    'remarks' => 'Dummy paid admission voucher.',

                    // Bank snapshot
                    'bank_name' => $regularBank->bank_name,
                    'account_title' => $regularBank->account_title,
                    'account_number' => $regularBank->account_number,
                    'iban' => $regularBank->iban,
                    'branch_name' => $regularBank->branch_name,
                    'branch_code' => $regularBank->branch_code,

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 12. PENDING REGULAR COURSE VOUCHER
            |--------------------------------------------------------------------------
            */
            $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-V-2026-000002',
                [

                    'voucher_no' => 'GATTC-V-2026-000002',

                    'admission_session_id' => $sessionId,

                    'course_id' => $courses['graphic-designing'],

                    'admission_id' => null,

                    'bank_account_id' => $regularBank->id,

                    'voucher_type' => 'admission',

                    'applicant_name' => 'Fatima Bibi',

                    'father_name' => 'Abdul Rahman',

                    'cnic' => '16101-2345678-2',

                    'date_of_birth' => '2006-08-21',

                    'gender' => 'female',

                    'phone' => '03111234567',

                    'email' => 'fatima.bibi@example.com',

                    'address' => 'University Town, Peshawar',

                    'amount' => 12000,

                    'issue_date' => now()->subDays(2)->toDateString(),

                    'due_date' => now()->addDays(8)->toDateString(),

                    'status' => 'generated',

                    'remarks' => 'Dummy pending regular course voucher.',

                    // Bank snapshot
                    'bank_name' => $regularBank->bank_name,
                    'account_title' => $regularBank->account_title,
                    'account_number' => $regularBank->account_number,
                    'iban' => $regularBank->iban,
                    'branch_name' => $regularBank->branch_name,
                    'branch_code' => $regularBank->branch_code,

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 13. DIT VOUCHER
            |--------------------------------------------------------------------------
            */
            $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-D-2026-000001',
                [

                    'voucher_no' => 'GATTC-D-2026-000001',

                    'admission_session_id' => $sessionId,

                    'course_id' =>
                        $courses['diploma-in-information-technology'],

                    'admission_id' => null,

                    'bank_account_id' => $ditBank->id,

                    'voucher_type' => 'admission',

                    'applicant_name' => 'Usman Khan',

                    'father_name' => 'Samiullah Khan',

                    'cnic' => '16101-4567890-4',

                    'date_of_birth' => '2004-06-15',

                    'gender' => 'male',

                    'phone' => '03331234567',

                    'email' => 'usman.khan@example.com',

                    'address' => 'Peshawar',

                    'amount' => 25000,

                    'issue_date' => now()->subDay()->toDateString(),

                    'due_date' => now()->addDays(9)->toDateString(),

                    'status' => 'generated',

                    'remarks' => 'Dummy DIT / Second Shift voucher.',

                    // Bank snapshot
                    'bank_name' => $ditBank->bank_name,
                    'account_title' => $ditBank->account_title,
                    'account_number' => $ditBank->account_number,
                    'iban' => $ditBank->iban,
                    'branch_name' => $ditBank->branch_name,
                    'branch_code' => $ditBank->branch_code,

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 14. HOSTEL VOUCHER
            |--------------------------------------------------------------------------
            */
            $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-H-2026-000001',
                [

                    'voucher_no' => 'GATTC-H-2026-000001',

                    'admission_session_id' => $sessionId,

                    'course_id' =>
                        $courses['computer-operator'],

                    'admission_id' => $admissionId,

                    'bank_account_id' => $hostelBank->id,

                    'voucher_type' => 'hostel',

                    'applicant_name' => 'Ahmad Ali',

                    'father_name' => 'Muhammad Ali',

                    'cnic' => '16101-1234567-1',

                    'date_of_birth' => '2005-04-12',

                    'gender' => 'male',

                    'phone' => '03001234567',

                    'email' => 'ahmad.ali@example.com',

                    'address' => 'Hayatabad, Peshawar',

                    'amount' => 12000,

                    'issue_date' => now()->subDays(5)->toDateString(),

                    'due_date' => now()->addDays(5)->toDateString(),

                    'status' => 'generated',

                    'remarks' => 'Dummy hostel voucher.',

                    // Bank snapshot
                    'bank_name' => $hostelBank->bank_name,
                    'account_title' => $hostelBank->account_title,
                    'account_number' => $hostelBank->account_number,
                    'iban' => $hostelBank->iban,
                    'branch_name' => $hostelBank->branch_name,
                    'branch_code' => $hostelBank->branch_code,

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 15. PRIVATE / IMC VOUCHER
            |--------------------------------------------------------------------------
            */
            $this->firstOrCreate(
                'vouchers',
                'voucher_no',
                'GATTC-P-2026-000001',
                [

                    'voucher_no' => 'GATTC-P-2026-000001',

                    'admission_session_id' => $sessionId,

                    'course_id' =>
                        $courses['autocad-technical-drawing'],

                    'admission_id' => null,

                    'bank_account_id' => $privateBank->id,

                    'voucher_type' => 'admission',

                    'applicant_name' => 'Hassan Khan',

                    'father_name' => 'Samiullah Khan',

                    'cnic' => '16101-3456789-3',

                    'date_of_birth' => '2004-11-03',

                    'gender' => 'male',

                    'phone' => '03221234567',

                    'email' => 'hassan.khan@example.com',

                    'address' => 'Board Bazaar, Peshawar',

                    'amount' => 22000,

                    'issue_date' => now()->toDateString(),

                    'due_date' => now()->addDays(10)->toDateString(),

                    'status' => 'generated',

                    'remarks' =>
                        'Dummy private / IMC course voucher.',

                    // Bank snapshot
                    'bank_name' => $privateBank->bank_name,
                    'account_title' => $privateBank->account_title,
                    'account_number' => $privateBank->account_number,
                    'iban' => $privateBank->iban,
                    'branch_name' => $privateBank->branch_name,
                    'branch_code' => $privateBank->branch_code,

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | 16. FEE PAYMENT
            |--------------------------------------------------------------------------
            */
            $paymentId = null;

            if (Schema::hasTable('fee_payments')) {

                $existingPayment = DB::table('fee_payments')
                    ->where('voucher_id', $paidVoucherId)
                    ->first();

                if ($existingPayment) {

                    $paymentId = $existingPayment->id;

                } else {

                    $paymentId = $this->insertExisting(
                        'fee_payments',
                        [
                            'voucher_id' => $paidVoucherId,

                            /*
                            |--------------------------------------------------------------------------
                            | Dummy payment slip
                            |--------------------------------------------------------------------------
                            |
                            | payment_slip is NOT NULL in the current database.
                            | This is only a seed/demo value.
                            |
                            */
                            'payment_slip' => 'payments/dummy-bank-slip.pdf',

                            'payment_date' =>
                                now()->subDays(9)->toDateString(),

                            'status' => 'approved',

                            'verified_by' => null,

                            'verified_at' =>
                                now()->subDays(8),

                            'remarks' =>
                                'Dummy approved bank payment.',
                        ]
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 17. PAYMENT HISTORY
            |--------------------------------------------------------------------------
            */
            if (
                Schema::hasTable('fee_payment_histories')
                && $paymentId
            ) {

                $historyExists = DB::table('fee_payment_histories')
                    ->where('fee_payment_id', $paymentId)
                    ->where('new_status', 'approved')
                    ->exists();

                if (!$historyExists) {

                    $this->insertExisting(
                        'fee_payment_histories',
                        [
                            'fee_payment_id' => $paymentId,

                            'user_id' => null,

                            'old_status' => 'pending',

                            'new_status' => 'approved',

                            'remarks' => 'Dummy payment approved by administration.',
                        ]
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 18. FEE RECEIPT
            |--------------------------------------------------------------------------
            */
            if (
                Schema::hasTable('fee_receipts')
                && $paymentId
            ) {

                $receiptExists = DB::table('fee_receipts')
                    ->where('fee_payment_id', $paymentId)
                    ->exists();

                if (!$receiptExists) {

                    $this->insertExisting(
                        'fee_receipts',
                        [
                            'fee_payment_id' => $paymentId,

                            'receipt_no' => 'GATTC-R-2026-000001',

                            'issued_at' => now()->subDays(8),

                            'issued_by' => null,

                            'remarks' => 'Dummy fee receipt.',
                        ]
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 19. WEBSITE SETTINGS
            |--------------------------------------------------------------------------
            */
            if (Schema::hasTable('website_settings')) {

                $settings = [

                    'site_name' =>
                        'Government Advance Technical Training Centre',

                    'short_name' => 'GATTC',

                    'address' =>
                        '16-A Industrial Estate, Opposite BRT TEVTA Stop, Hayatabad, Peshawar',

                    'phone' => '091-5881389',

                    'email' => 'info@gattc.edu.pk',

                    'website' => 'https://gattc.edu.pk',

                    'facebook' => 'GATTC Peshawar',

                    'footer_text' =>
                        'Empowering youth through technical and vocational education.',

                ];

                foreach ($settings as $key => $value) {

                    $exists = DB::table('website_settings')
                        ->where('key', $key)
                        ->exists();

                    if (!$exists) {

                        $this->insertExisting(
                            'website_settings',
                            [
                                'key' => $key,
                                'value' => $value,
                            ]
                        );
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | COMPLETE
            |--------------------------------------------------------------------------
            */
            $this->command->info(
                'GATTC dummy data seeded successfully.'
            );
        });
    }
}