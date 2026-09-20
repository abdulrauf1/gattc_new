<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\FeeDepositDetail;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PublicAdmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Public Admission Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $activeSession = $this->activeAdmissionSession();

        if (!$activeSession) {

            return view('public.admission', [
                'activeSession'  => null,
                'regularCourses' => collect(),
                'ditCourses'     => collect(),
                'privateCourses' => collect(),
                'hostelFee'      => null,
            ]);
        }


        /*
         * ==============================================================
         * Get Courses Available in Current Admission Session
         * ==============================================================
         */
        $availableCourses = DB::table('courses as c')
            ->join(
                'admission_session_course as asc',
                'asc.course_id',
                '=',
                'c.id'
            )
            ->where(
                'asc.admission_session_id',
                $activeSession->id
            )
            ->where('c.status', true)
            ->select([
                'c.id',
                'c.title',
                'c.slug',
                'c.course_type',
                'c.description',
                'c.duration',
                'c.eligibility',
                'c.fee_amount',
                'c.image',
                'c.sort_order',
                'c.bank_account_id',
            ])
            ->distinct()
            ->orderBy('c.sort_order')
            ->orderBy('c.title')
            ->get();


        /*
         * ==============================================================
         * Morning
         * ==============================================================
         */
        $regularCourses = $availableCourses
            ->where(
                'course_type',
                'regular'
            )
            ->values();


        /*
         * ==============================================================
         * Evening - DIT
         * ==============================================================
         */
        $ditCourses = $availableCourses
            ->where(
                'course_type',
                'dit'
            )
            ->values();


        /*
         * ==============================================================
         * Evening - Private / IMC
         * ==============================================================
         */
        $privateCourses = $availableCourses
            ->where(
                'course_type',
                'private'
            )
            ->values();


        /*
         * ==============================================================
         * Hostel
         * ==============================================================
         */
        $hostelDetails = $this->getGlobalDepositDetails(
            'hostel'
        );

        $hostelFee = $this->calculateFeeTotal(
            $hostelDetails
        );

        $hostelFee =
            $hostelFee > 0
                ? $hostelFee
                : null;


        return view('public.admission', [
            'activeSession'  => $activeSession,
            'regularCourses' => $regularCourses,
            'ditCourses'     => $ditCourses,
            'privateCourses' => $privateCourses,
            'hostelFee'      => $hostelFee,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Public Admission Application
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $activeSession = $this->activeAdmissionSession();

        if (!$activeSession) {

            return redirect()
                ->route('public.admission')
                ->with(
                    'error',
                    'Online admissions are currently closed.'
                );
        }


        /*
         * ==============================================================
         * Validate Applicant
         * ==============================================================
         */
        $validated = $request->validate([

            'regular_course_id' => [
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            'dit_course_id' => [
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            'private_course_id' => [
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            'apply_hostel' => [
                'nullable',
                'boolean',
            ],

            'student_name' => [
                'required',
                'string',
                'max:255',
            ],

            'father_name' => [
                'required',
                'string',
                'max:255',
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
                'string',
                'max:30',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        /*
         * ==============================================================
         * Selected Programme IDs
         * ==============================================================
         */
        $regularCourseId =
            !empty($validated['regular_course_id'])
                ? (int) $validated['regular_course_id']
                : null;

        $ditCourseId =
            !empty($validated['dit_course_id'])
                ? (int) $validated['dit_course_id']
                : null;

        $privateCourseId =
            !empty($validated['private_course_id'])
                ? (int) $validated['private_course_id']
                : null;


        /*
         * ==============================================================
         * HARD CONFLICT CHECK
         * ==============================================================
         *
         * DIT and Private / IMC are both Evening programmes.
         *
         * Only ONE Evening programme is allowed.
         */
        if (
            $ditCourseId &&
            $privateCourseId
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'You cannot select both DIT / Second Shift and Private / IMC. Only one Evening programme may be selected.'
                );
        }


        /*
         * ==============================================================
         * Build programme list
         * ==============================================================
         */
        $selectedPrograms = collect();


        if ($regularCourseId) {

            $selectedPrograms->push([
                'id'   => $regularCourseId,
                'type' => 'regular',
            ]);
        }


        if ($ditCourseId) {

            $selectedPrograms->push([
                'id'   => $ditCourseId,
                'type' => 'dit',
            ]);
        }


        if ($privateCourseId) {

            $selectedPrograms->push([
                'id'   => $privateCourseId,
                'type' => 'private',
            ]);
        }


        /*
         * At least one programme.
         */
        if ($selectedPrograms->isEmpty()) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please select at least one training programme.'
                );
        }


        /*
         * Maximum two programmes:
         *
         * 1 Morning
         * +
         * 1 Evening
         */
        if ($selectedPrograms->count() > 2) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'You can select only one Morning programme and one Evening programme.'
                );
        }


        /*
         * ==============================================================
         * Validate Every Selected Programme
         * ==============================================================
         */
        $selectedCourses = collect();


        foreach ($selectedPrograms as $selected) {

            $course = DB::table('courses as c')
                ->join(
                    'admission_session_course as asc',
                    'asc.course_id',
                    '=',
                    'c.id'
                )
                ->where(
                    'c.id',
                    $selected['id']
                )
                ->where(
                    'asc.admission_session_id',
                    $activeSession->id
                )
                ->where(
                    'c.course_type',
                    $selected['type']
                )
                ->where(
                    'c.status',
                    true
                )
                ->select([
                    'c.id',
                    'c.title',
                    'c.course_type',
                    'c.duration',
                    'c.eligibility',
                    'c.fee_amount',
                    'c.bank_account_id',
                ])
                ->first();


            if (!$course) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'One of the selected programmes is not available in the current admission session.'
                    );
            }


            /*
             * ==========================================================
             * Bank Account
             * ==========================================================
             */
            if (!$course->bank_account_id) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "No bank account is assigned to {$course->title}."
                    );
            }


            $bankAccount = BankAccount::query()
                ->where(
                    'id',
                    $course->bank_account_id
                )
                ->where(
                    'status',
                    true
                )
                ->first();


            if (!$bankAccount) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "The bank account assigned to {$course->title} is not active."
                    );
            }


            /*
             * ==========================================================
             * Deposit Details
             * ==========================================================
             */
            $depositDetails =
                $this->getCourseDepositDetails(
                    $course->id,
                    $course->course_type
                );


            if ($depositDetails->isEmpty()) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "Fee deposit details have not been configured for {$course->title}."
                    );
            }


            $calculatedTotal =
                $this->calculateFeeTotal(
                    $depositDetails
                );


            /*
             * The database breakdown must equal
             * courses.fee_amount.
             */
            $courseFee =
                (float) $course->fee_amount;


            if (
                abs(
                    $calculatedTotal -
                    $courseFee
                ) > 0.01
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "The fee breakdown for {$course->title} does not match the course's configured total fee. Please contact GATTC administration."
                    );
            }


            $course->bankAccount =
                $bankAccount;


            $course->depositDetails =
                $depositDetails;


            $course->depositTotal =
                $calculatedTotal;


            $selectedCourses->push(
                $course
            );
        }


        /*
         * ==============================================================
         * Hostel
         * ==============================================================
         */
        $hostelFee = 0;
        $hostelAccount = null;
        $hostelDetails = collect();


        if (
            $request->boolean(
                'apply_hostel'
            )
        ) {

            $hostelDetails =
                $this->getGlobalDepositDetails(
                    'hostel'
                );


            if ($hostelDetails->isEmpty()) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Hostel fee deposit details have not been configured by the administration.'
                    );
            }


            $hostelFee =
                $this->calculateFeeTotal(
                    $hostelDetails
                );


            if ($hostelFee <= 0) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The configured Hostel fee is invalid.'
                    );
            }


            $hostelAccount =
                BankAccount::query()
                    ->where(
                        'purpose',
                        'like',
                        '%Hostel%'
                    )
                    ->where(
                        'status',
                        true
                    )
                    ->first();


            if (!$hostelAccount) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The active Hostel bank account was not found.'
                    );
            }
        }


        /*
         * ==============================================================
         * CREATE ALL VOUCHERS
         * ==============================================================
         *
         * IMPORTANT:
         * All validation above happens before this transaction.
         *
         * Therefore invalid programme combinations create ZERO vouchers.
         */
        $vouchers = DB::transaction(
            function () use (
                $validated,
                $activeSession,
                $selectedCourses,
                $hostelFee,
                $hostelAccount,
                $hostelDetails
            ) {

                $vouchers = collect();


                /*
                 * ------------------------------------------------------
                 * Course Voucher(s)
                 * ------------------------------------------------------
                 */
                foreach (
                    $selectedCourses as $course
                ) {

                    $vouchers->push(
                        $this->createVoucher([
                            'session' =>
                                $activeSession,

                            'course' =>
                                $course,

                            'bank_account' =>
                                $course->bankAccount,

                            'voucher_type' =>
                                'admission',

                            'student_name' =>
                                $validated['student_name'],

                            'father_name' =>
                                $validated['father_name'],

                            'cnic' =>
                                $validated['cnic'],

                            'date_of_birth' =>
                                $validated['date_of_birth'],

                            'gender' =>
                                $validated['gender'],

                            'phone' =>
                                $validated['phone'],

                            'email' =>
                                $validated['email'] ?? null,

                            'address' =>
                                $validated['address'],

                            'amount' =>
                                $course->depositTotal,

                            'remarks' =>
                                ucfirst(
                                    $course->course_type
                                ) .
                                ' admission voucher generated through online admission.',
                        ])
                    );
                }


                /*
                 * ------------------------------------------------------
                 * Hostel Voucher
                 * ------------------------------------------------------
                 */
                if (
                    $hostelFee > 0 &&
                    $hostelAccount
                ) {

                    $vouchers->push(
                        $this->createVoucher([
                            'session' =>
                                $activeSession,

                            'course' =>
                                null,

                            'bank_account' =>
                                $hostelAccount,

                            'voucher_type' =>
                                'hostel',

                            'student_name' =>
                                $validated['student_name'],

                            'father_name' =>
                                $validated['father_name'],

                            'cnic' =>
                                $validated['cnic'],

                            'date_of_birth' =>
                                $validated['date_of_birth'],

                            'gender' =>
                                $validated['gender'],

                            'phone' =>
                                $validated['phone'],

                            'email' =>
                                $validated['email'] ?? null,

                            'address' =>
                                $validated['address'],

                            'amount' =>
                                $hostelFee,

                            'remarks' =>
                                'Hostel fee voucher generated through online admission.',
                        ])
                    );
                }


                return $vouchers;
            }
        );


        /*
         * ==============================================================
         * Save ALL generated voucher IDs
         * ==============================================================
         */
        session()->put(
            'public_voucher_ids',
            $vouchers
                ->pluck('id')
                ->values()
                ->all()
        );


        /*
         * Redirect to voucher list,
         * not directly to the first voucher.
         */
        return redirect()
            ->route(
                'public.admission.vouchers'
            )
            ->with(
                'success',
                $vouchers->count() > 1
                    ? 'All required vouchers have been generated successfully.'
                    : 'Voucher generated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Voucher List
    |--------------------------------------------------------------------------
    |
    | Shows every voucher generated by this application.
    |
    */

    public function vouchers()
    {
        $allowedIds = session(
            'public_voucher_ids',
            []
        );


        if (empty($allowedIds)) {

            return redirect()
                ->route('public.admission');
        }


        $vouchers = Voucher::query()
            ->with([
                'course',
                'bankAccount',
                'admissionSession',
            ])
            ->whereIn(
                'id',
                $allowedIds
            )
            ->orderBy('id')
            ->get();


        abort_if(
            $vouchers->isEmpty(),
            404
        );


        return view(
            'public.admission-vouchers',
            [
                'vouchers' =>
                    $vouchers,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show / Print One Voucher
    |--------------------------------------------------------------------------
    */

    public function voucher(
        string $voucherNo
    ) {
        $allowedIds = session(
            'public_voucher_ids',
            []
        );


        $voucher = Voucher::query()
            ->with([
                'course',
                'bankAccount',
                'admissionSession',
            ])
            ->where(
                'voucher_no',
                $voucherNo
            )
            ->firstOrFail();


        abort_unless(
            in_array(
                $voucher->id,
                $allowedIds,
                true
            ),
            403
        );


        /*
         * Get database-driven deposit details.
         */
        if (
            $voucher->voucher_type ===
            'hostel'
        ) {

            $depositDetails =
                $this->getGlobalDepositDetails(
                    'hostel'
                );

        } else {

            $courseType =
                $voucher->course?->course_type ??
                'regular';


            $depositDetails =
                $this->getCourseDepositDetails(
                    $voucher->course_id,
                    $courseType
                );
        }


        return view(
            'admin.vouchers.print',
            [
                'voucher' =>
                    $voucher,

                'publicVoucher' =>
                    true,

                'depositDetails' =>
                    $depositDetails,

                'relatedVouchers' =>
                    Voucher::query()
                        ->with([
                            'course',
                            'bankAccount',
                        ])
                        ->whereIn(
                            'id',
                            $allowedIds
                        )
                        ->orderBy('id')
                        ->get(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Voucher
    |--------------------------------------------------------------------------
    */

    private function createVoucher(
        array $data
    ): Voucher {

        $bankAccount =
            $data['bank_account'];

        $session =
            $data['session'];

        $course =
            $data['course'] ?? null;


        return Voucher::create([

            'voucher_no' =>
                $this->generateVoucherNo(),

            'admission_session_id' =>
                $session->id,

            /*
             * Final admission is NOT created here.
             */
            'admission_id' =>
                null,

            'course_id' =>
                $course?->id,

            'bank_account_id' =>
                $bankAccount->id,

            /*
             * Matches the working Admin
             * VoucherController.
             */
            'voucher_type' =>
                $data['voucher_type'],

            'applicant_name' =>
                $data['student_name'],

            'father_name' =>
                $data['father_name'] ?? null,

            'cnic' =>
                $data['cnic'],

            'date_of_birth' =>
                $data['date_of_birth'] ?? null,

            'gender' =>
                $data['gender'] ?? null,

            'phone' =>
                $data['phone'],

            'email' =>
                $data['email'] ?? null,

            'address' =>
                $data['address'] ?? null,

            'amount' =>
                $data['amount'],

            'issue_date' =>
                today(),

            'due_date' =>
                today()->addDays(7),

            'status' =>
                'generated',

            'remarks' =>
                $data['remarks'] ?? null,

            /*
             * Bank snapshot.
             */
            'bank_name' =>
                $bankAccount->bank_name,

            'account_title' =>
                $bankAccount->account_title,

            'account_number' =>
                $bankAccount->account_number,

            'iban' =>
                $bankAccount->iban,

            'branch_name' =>
                $bankAccount->branch_name,

            'branch_code' =>
                $bankAccount->branch_code,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Course Deposit Details
    |--------------------------------------------------------------------------
    */

    private function getCourseDepositDetails(
        int $courseId,
        string $courseType
    ): Collection {

        /*
         * First preference:
         *
         * course-specific configuration.
         */
        $details = FeeDepositDetail::query()
            ->where(
                'course_id',
                $courseId
            )
            ->where(
                'fee_category',
                $courseType
            )
            ->where(
                'status',
                true
            )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();


        if ($details->isNotEmpty()) {
            return $details;
        }


        /*
         * Fallback:
         *
         * global template for the course type.
         */
        return $this->getGlobalDepositDetails(
            $courseType
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Global Deposit Details
    |--------------------------------------------------------------------------
    */

    private function getGlobalDepositDetails(
        string $category
    ): Collection {

        return FeeDepositDetail::query()
            ->whereNull('course_id')
            ->where(
                'fee_category',
                $category
            )
            ->where(
                'status',
                true
            )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Total
    |--------------------------------------------------------------------------
    */

    private function calculateFeeTotal(
        Collection $details
    ): float {

        return round(
            $details->sum(
                fn ($detail) =>
                    (float) $detail->amount
            ),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Voucher Number
    |--------------------------------------------------------------------------
    */

    private function generateVoucherNo(): string
    {
        $year =
            now()->format('Y');


        $lastVoucher =
            Voucher::query()
                ->whereYear(
                    'created_at',
                    $year
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number =
            $lastVoucher
                ? (
                    (int) substr(
                        $lastVoucher->voucher_no,
                        -5
                    )
                ) + 1
                : 1;


        return 'VCH-' .
            $year .
            '-' .
            str_pad(
                $number,
                5,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Active Admission Session
    |--------------------------------------------------------------------------
    */

    private function activeAdmissionSession():
        ?AdmissionSession
    {
        return AdmissionSession::query()
            ->where(
                'is_open',
                true
            )
            ->whereDate(
                'opening_date',
                '<=',
                today()
            )
            ->whereDate(
                'closing_date',
                '>=',
                today()
            )
            ->latest('id')
            ->first();
    }
}