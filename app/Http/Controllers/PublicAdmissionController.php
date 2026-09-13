<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\FeeConfiguration;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicAdmissionController extends Controller
{
    /**
     * Display the public admission form.
     */
    public function create()
    {
        $activeSession = $this->activeAdmissionSession();

        if (!$activeSession) {
            return view('public.admission', [
                'activeSession' => null,
                'courses' => collect(),
            ]);
        }

        $courses = Course::query()
            ->where('status', true)
            ->orderBy('title')
            ->get();

        return view('public.admission', [
            'activeSession' => $activeSession,
            'courses' => $courses,
        ]);
    }

    /**
     * Store the public admission application
     * and generate the required vouchers.
     */
    public function store(Request $request)
    {
        $activeSession = $this->activeAdmissionSession();

        if (!$activeSession) {
            return redirect()
                ->route('public.admission')
                ->with('error', 'Admissions are currently closed.');
        }

        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'course_batch_id' => [
                'nullable',
                'exists:course_batches,id',
            ],

            'student_name' => [
                'required',
                'string',
                'max:150',
            ],

            'father_name' => [
                'required',
                'string',
                'max:150',
            ],

            'cnic' => [
                'required',
                'string',
                'max:30',
            ],

            'date_of_birth' => [
                'required',
                'date',
            ],

            'gender' => [
                'required',
                'in:Male,Female,Other',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            /*
             * Optional voucher selections.
             *
             * These checkboxes can be added to the public form later.
             * For now, regular is generated automatically.
             */
            'include_dit' => [
                'nullable',
                'boolean',
            ],

            'include_hostel' => [
                'nullable',
                'boolean',
            ],

            'include_private' => [
                'nullable',
                'boolean',
            ],
        ]);

        $course = Course::query()
            ->whereKey($validated['course_id'])
            ->where('status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Find the regular course fee configuration
        |--------------------------------------------------------------------------
        */

        $feeConfiguration = FeeConfiguration::query()
            ->where('course_id', $course->id)
            ->where('admission_session_id', $activeSession->id)
            ->where('status', true)
            ->first();

        if (!$feeConfiguration) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' => 'No active fee configuration exists for this course in the selected admission session.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate vouchers inside one database transaction
        |--------------------------------------------------------------------------
        */

        $vouchers = DB::transaction(function () use (
            $validated,
            $activeSession,
            $course,
            $feeConfiguration
        ) {
            return $this->generateVouchers(
                validated: $validated,
                activeSession: $activeSession,
                course: $course,
                regularFeeConfiguration: $feeConfiguration
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Redirect to the first voucher
        |--------------------------------------------------------------------------
        */

        $firstVoucher = $vouchers->first();

        return redirect()
            ->route('public.admission.voucher', $firstVoucher)
            ->with([
                'success' => 'Your fee vouchers have been generated successfully.',
                'voucher_ids' => $vouchers->pluck('id')->toArray(),
            ]);
    }

    /**
     * Generate separate vouchers for:
     *
     * 1. Regular Courses
     * 2. DIT / Second Shift
     * 3. Hostel Fees
     * 4. Private Courses
     *
     * Regular voucher is always generated.
     * Other vouchers are generated when their corresponding
     * checkbox is submitted.
     */
    private function generateVouchers(
        array $validated,
        AdmissionSession $activeSession,
        Course $course,
        FeeConfiguration $regularFeeConfiguration
    ) {
        $vouchers = collect();

        /*
        |--------------------------------------------------------------------------
        | Bank account purposes
        |--------------------------------------------------------------------------
        |
        | These values must match the "purpose" column in bank_accounts.
        | Change the values if your seeded records use different names.
        |
        */

        $bankPurposes = [
            'regular' => 'Regular Courses',
            'dit' => 'DIT / Second Shift',
            'hostel' => 'Hostel Fees',
            'private' => 'Private Courses',
        ];

        /*
        |--------------------------------------------------------------------------
        | 1. Regular Courses Voucher
        |--------------------------------------------------------------------------
        */

        $regularBankAccount = $this->findBankAccountByPurpose(
            $bankPurposes['regular']
        );

        $vouchers->push(
            $this->createVoucher([
                'voucher_category' => 'regular',
                'bank_account' => $regularBankAccount,

                'fee_configuration_id' => $regularFeeConfiguration->id,
                'admission_session_id' => $activeSession->id,
                'course_id' => $course->id,
                'course_batch_id' => $validated['course_batch_id'] ?? null,

                'applicant_name' => $validated['student_name'],
                'father_name' => $validated['father_name'],
                'cnic' => $validated['cnic'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],

                'amount' => $regularFeeConfiguration->amount,

                'fee_details' => [
                    [
                        'title' => 'Regular Course Fee',
                        'amount' => $regularFeeConfiguration->amount,
                    ],
                ],
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 2. DIT / Second Shift Voucher
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['include_dit'])) {
            $ditFeeConfiguration = $this->findOptionalFeeConfiguration(
                course: $course,
                activeSession: $activeSession,
                category: 'dit'
            );

            if ($ditFeeConfiguration) {
                $ditBankAccount = $this->findBankAccountByPurpose(
                    $bankPurposes['dit']
                );

                $vouchers->push(
                    $this->createVoucher([
                        'voucher_category' => 'dit',
                        'bank_account' => $ditBankAccount,

                        'fee_configuration_id' => $ditFeeConfiguration->id,
                        'admission_session_id' => $activeSession->id,
                        'course_id' => $course->id,
                        'course_batch_id' => $validated['course_batch_id'] ?? null,

                        'applicant_name' => $validated['student_name'],
                        'father_name' => $validated['father_name'],
                        'cnic' => $validated['cnic'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'gender' => $validated['gender'],
                        'phone' => $validated['phone'],
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'],

                        'amount' => $ditFeeConfiguration->amount,

                        'fee_details' => [
                            [
                                'title' => 'DIT / Second Shift Fee',
                                'amount' => $ditFeeConfiguration->amount,
                            ],
                        ],
                    ])
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Hostel Fees Voucher
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['include_hostel'])) {
            $hostelFeeConfiguration = $this->findOptionalFeeConfiguration(
                course: $course,
                activeSession: $activeSession,
                category: 'hostel'
            );

            if ($hostelFeeConfiguration) {
                $hostelBankAccount = $this->findBankAccountByPurpose(
                    $bankPurposes['hostel']
                );

                $vouchers->push(
                    $this->createVoucher([
                        'voucher_category' => 'hostel',
                        'bank_account' => $hostelBankAccount,

                        'fee_configuration_id' => $hostelFeeConfiguration->id,
                        'admission_session_id' => $activeSession->id,
                        'course_id' => $course->id,
                        'course_batch_id' => $validated['course_batch_id'] ?? null,

                        'applicant_name' => $validated['student_name'],
                        'father_name' => $validated['father_name'],
                        'cnic' => $validated['cnic'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'gender' => $validated['gender'],
                        'phone' => $validated['phone'],
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'],

                        'amount' => $hostelFeeConfiguration->amount,

                        'fee_details' => [
                            [
                                'title' => 'Hostel Fee',
                                'amount' => $hostelFeeConfiguration->amount,
                            ],
                        ],
                    ])
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Private Courses Voucher
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['include_private'])) {
            $privateFeeConfiguration = $this->findOptionalFeeConfiguration(
                course: $course,
                activeSession: $activeSession,
                category: 'private'
            );

            if ($privateFeeConfiguration) {
                $privateBankAccount = $this->findBankAccountByPurpose(
                    $bankPurposes['private']
                );

                $vouchers->push(
                    $this->createVoucher([
                        'voucher_category' => 'private',
                        'bank_account' => $privateBankAccount,

                        'fee_configuration_id' => $privateFeeConfiguration->id,
                        'admission_session_id' => $activeSession->id,
                        'course_id' => $course->id,
                        'course_batch_id' => $validated['course_batch_id'] ?? null,

                        'applicant_name' => $validated['student_name'],
                        'father_name' => $validated['father_name'],
                        'cnic' => $validated['cnic'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'gender' => $validated['gender'],
                        'phone' => $validated['phone'],
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'],

                        'amount' => $privateFeeConfiguration->amount,

                        'fee_details' => [
                            [
                                'title' => 'Private Course Fee',
                                'amount' => $privateFeeConfiguration->amount,
                            ],
                        ],
                    ])
                );
            }
        }

        return $vouchers;
    }

    /**
     * Create one voucher using a bank account from bank_accounts table.
     */
    private function createVoucher(array $data): Voucher
    {
        /** @var \App\Models\BankAccount $bankAccount */
        $bankAccount = $data['bank_account'];

        return Voucher::create([
            'voucher_no' => $this->generateVoucherNumber(),

            'admission_session_id' => $data['admission_session_id'],
            'fee_configuration_id' => $data['fee_configuration_id'] ?? null,

            /*
             * Admission is created only after payment verification.
             */
            'admission_id' => null,

            'course_id' => $data['course_id'] ?? null,
            'course_batch_id' => $data['course_batch_id'] ?? null,

            'bank_account_id' => $bankAccount->id,
            'voucher_category' => $data['voucher_category'],

            'applicant_name' => $data['applicant_name'],
            'father_name' => $data['father_name'] ?? null,
            'cnic' => $data['cnic'] ?? null,

            /*
             * These fields must exist in your vouchers table.
             * Remove them here only if your migration does not contain them.
             */
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,

            'amount' => $data['amount'],
            'fee_details' => $data['fee_details'] ?? [],

            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),

            'status' => 'generated',

            'remarks' => ucfirst($data['voucher_category']) .
                ' voucher generated through online admission.',

            /*
             * Store a snapshot of the bank account details.
             * This protects old vouchers if bank details change later.
             */
            'bank_name' => $bankAccount->bank_name,
            'account_title' => $bankAccount->account_title,
            'account_number' => $bankAccount->account_number,
            'iban' => $bankAccount->iban,
            'branch_name' => $bankAccount->branch_name,
        ]);
    }

    /**
     * Find an active bank account by its purpose.
     */
    private function findBankAccountByPurpose(string $purpose): BankAccount
    {
        return BankAccount::query()
            ->where('purpose', $purpose)
            ->where('status', true)
            ->firstOrFail();
    }

    /**
     * Find an optional fee configuration.
     *
     * This assumes your fee_configurations table has a "category"
     * column containing regular, dit, hostel, or private.
     *
     * If your table does not have a category column, replace this
     * query with your own fee-type relationship.
     */
    private function findOptionalFeeConfiguration(
        Course $course,
        AdmissionSession $activeSession,
        string $category
    ): ?FeeConfiguration {
        $query = FeeConfiguration::query()
            ->where('course_id', $course->id)
            ->where('admission_session_id', $activeSession->id)
            ->where('status', true);

        if (method_exists(FeeConfiguration::class, 'scopeCategory')) {
            return $query
                ->where('category', $category)
                ->first();
        }

        return null;
    }

    /**
     * Check the currently open admission session.
     */
    private function activeAdmissionSession(): ?AdmissionSession
    {
        return AdmissionSession::query()
            ->where('is_open', true)
            ->whereDate('opening_date', '<=', today())
            ->whereDate('closing_date', '>=', today())
            ->latest('id')
            ->first();
    }

    /**
     * Generate a unique voucher number.
     */
    private function generateVoucherNumber(): string
    {
        do {
            $voucherNo = 'GATTC-' .
                now()->format('Ym') .
                '-' .
                strtoupper(Str::random(6));
        } while (
            Voucher::query()
                ->where('voucher_no', $voucherNo)
                ->exists()
        );

        return $voucherNo;
    }

    /**
     * Display a voucher.
     */
    public function voucher(Voucher $voucher)
    {
        $voucher->load([
            'bankAccount',
            'course',
            'admissionSession',
        ]);

        return view('public.voucher', [
            'voucher' => $voucher,
        ]);
    }

    
}