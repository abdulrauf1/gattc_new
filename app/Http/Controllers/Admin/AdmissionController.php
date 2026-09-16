<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\FeePayment;
use App\Models\StudentCard;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admissions List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Admission::query()
            ->with([
                'course.category',
                'session',
                'vouchers.payment',
            ])
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'admission_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'student_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'father_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'cnic',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Course
        |--------------------------------------------------------------------------
        */

        if ($request->filled('course_id')) {

            $query->where(
                'course_id',
                $request->course_id
            );
        }


        $admissions =
            $query
                ->paginate(15)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalAdmissions =
            Admission::count();

        $pendingAdmissions =
            Admission::where(
                'status',
                'pending'
            )->count();

        $approvedAdmissions =
            Admission::where(
                'status',
                'approved'
            )->count();

        $rejectedAdmissions =
            Admission::where(
                'status',
                'rejected'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        $courses =
            \App\Models\Course::query()
                ->where('status', true)
                ->orderBy('title')
                ->get();


        return view(
            'admin.admissions.index',
            compact(
                'admissions',
                'courses',
                'totalAdmissions',
                'pendingAdmissions',
                'approvedAdmissions',
                'rejectedAdmissions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admission Details
    |--------------------------------------------------------------------------
    */

    public function show(Admission $admission)
    {
        $admission->load([
            'course.category',
            'session',
            'vouchers.payment',
            'vouchers.bankAccount',
            'studentCards.issuedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Determine whether card generation is allowed
        |--------------------------------------------------------------------------
        */

        $paidAdmissionVoucher =
            $admission->vouchers
                ->whereIn(
                    'voucher_type',
                    [
                        'admission',
                        'readmission',
                    ]
                )
                ->first(
                    fn ($voucher) =>
                        $voucher->payment &&
                        $voucher->payment->status === 'approved'
                );


        $canGenerateCard =
            $admission->status === 'approved'
            &&
            $paidAdmissionVoucher !== null;


        return view(
            'admin.admissions.show',
            compact(
                'admission',
                'paidAdmissionVoucher',
                'canGenerateCard'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Admission Status
    |--------------------------------------------------------------------------
    |
    | Payment verification does NOT approve the admission.
    |
    | Admission status is controlled here after physical verification
    | of the application/documents.
    |
    */

    public function updateStatus(
        Request $request,
        Admission $admission
    ) {
        $validated =
            $request->validate([

                'status' => [
                    'required',
                    'in:pending,approved,rejected',
                ],

                'remarks' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Approved admission requires verified payment
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'approved'
        ) {

            $hasApprovedPayment =
                $admission->vouchers()
                    ->whereIn(
                        'voucher_type',
                        [
                            'admission',
                            'readmission',
                        ]
                    )
                    ->whereHas(
                        'payment',
                        function ($payment) {
                            $payment->where(
                                'status',
                                'approved'
                            );
                        }
                    )
                    ->exists();


            if (!$hasApprovedPayment) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'status' =>
                            'Admission cannot be approved until the admission/readmission payment has been verified.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Admission Number when approved
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'approved'
            &&
            empty($admission->admission_no)
        ) {

            $validated['admission_no'] =
                $this->generateAdmissionNo();
        }


        $admission->update([

            'admission_no' =>
                $validated['admission_no']
                ?? $admission->admission_no,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?: $admission->remarks,
        ]);


        return redirect()
            ->route(
                'admin.admissions.show',
                $admission
            )
            ->with(
                'success',
                'Admission status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate / Reissue Student Card
    |--------------------------------------------------------------------------
    |
    | THIS IS THE ONLY PLACE where a student card is generated.
    |
    */

    public function generateStudentCard(
        Request $request,
        Admission $admission
    ) {
        /*
        |--------------------------------------------------------------------------
        | Admission must be approved
        |--------------------------------------------------------------------------
        */

        if ($admission->status !== 'approved') {

            return back()->withErrors([
                'card' =>
                    'Student card can only be generated after the admission is approved.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify payment
        |--------------------------------------------------------------------------
        */

        $paidVoucher =
            $admission->vouchers()
                ->whereIn(
                    'voucher_type',
                    [
                        'admission',
                        'readmission',
                    ]
                )
                ->whereHas(
                    'payment',
                    function ($payment) {
                        $payment->where(
                            'status',
                            'approved'
                        );
                    }
                )
                ->latest('id')
                ->first();


        if (!$paidVoucher) {

            return back()->withErrors([
                'card' =>
                    'A verified admission or readmission payment is required before generating a student card.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'student_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $result =
            DB::transaction(function () use (
                $request,
                $admission,
                $paidVoucher
            ) {

                /*
                |--------------------------------------------------------------------------
                | Generate Admission No if still missing
                |--------------------------------------------------------------------------
                */

                if (
                    empty(
                        $admission->admission_no
                    )
                ) {

                    $admission->update([
                        'admission_no' =>
                            $this->generateAdmissionNo(),
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Save student photo
                |--------------------------------------------------------------------------
                */

                $photoPath =
                    $request
                        ->file('student_photo')
                        ->store(
                            'students/photos',
                            'public'
                        );


                /*
                |--------------------------------------------------------------------------
                | Update admission photo
                |--------------------------------------------------------------------------
                */

                $admission->update([
                    'student_photo' =>
                        $photoPath,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Disable previous active card
                |--------------------------------------------------------------------------
                */

                StudentCard::query()
                    ->where(
                        'admission_id',
                        $admission->id
                    )
                    ->where(
                        'status',
                        true
                    )
                    ->update([
                        'status' => false,

                        'remarks' =>
                            'Superseded by newly issued student card.',
                    ]);


                /*
                |--------------------------------------------------------------------------
                | Generate card number
                |--------------------------------------------------------------------------
                */

                $cardNo =
                    $this->generateStudentCardNo();


                /*
                |--------------------------------------------------------------------------
                | Expiry
                |--------------------------------------------------------------------------
                */

                $expiryDate =
                    $request->input(
                        'expiry_date'
                    );


                /*
                | Default card validity:
                | one year from issue date.
                */

                if (!$expiryDate) {

                    $expiryDate =
                        now()
                            ->addYear()
                            ->toDateString();
                }


                /*
                |--------------------------------------------------------------------------
                | Create card
                |--------------------------------------------------------------------------
                */

                $studentCard =
                    StudentCard::create([

                        'admission_id' =>
                            $admission->id,

                        'card_no' =>
                            $cardNo,

                        'photo' =>
                            $photoPath,

                        'issued_at' =>
                            now()->toDateString(),

                        'expiry_date' =>
                            $expiryDate,

                        'issued_by' =>
                            auth()->id(),

                        'status' =>
                            true,

                        'remarks' =>
                            $paidVoucher->voucher_type === 'readmission'

                                ? (
                                    $request->input(
                                        'remarks'
                                    )
                                    ?: 'Student card reissued after readmission payment verification.'
                                )

                                : (
                                    $request->input(
                                        'remarks'
                                    )
                                    ?: 'Student card generated after admission and payment verification.'
                                ),
                    ]);


                return $studentCard;
            });


        return redirect()
            ->route(
                'admin.student-cards.print',
                $result
            )
            ->with(
                'success',
                'Student card generated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Print Student Card
    |--------------------------------------------------------------------------
    */

    public function printStudentCard(
        StudentCard $studentCard
    ) {
        $studentCard->load([
            'admission.course',
            'admission.session',
        ]);

        return view(
            'admin.student-cards.print',
            compact('studentCard')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admission Number
    |--------------------------------------------------------------------------
    */

    private function generateAdmissionNo(): string
    {
        $year =
            now()->format('Y');


        $lastAdmission =
            Admission::query()
                ->where(
                    'admission_no',
                    'like',
                    'ADM-' . $year . '-%'
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if ($lastAdmission) {

            $number =
                ((int) substr(
                    $lastAdmission->admission_no,
                    -5
                )) + 1;
        }


        do {

            $admissionNo =
                'ADM-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                Admission::where(
                    'admission_no',
                    $admissionNo
                )->exists();


            if ($exists) {
                $number++;
            }

        } while ($exists);


        return $admissionNo;
    }


    /*
    |--------------------------------------------------------------------------
    | Student Card Number
    |--------------------------------------------------------------------------
    */

    private function generateStudentCardNo(): string
    {
        $year =
            now()->format('Y');


        $lastCard =
            StudentCard::query()
                ->where(
                    'card_no',
                    'like',
                    'STC-' . $year . '-%'
                )
                ->latest('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if ($lastCard) {

            $number =
                ((int) substr(
                    $lastCard->card_no,
                    -5
                )) + 1;
        }


        do {

            $cardNo =
                'STC-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                StudentCard::where(
                    'card_no',
                    $cardNo
                )->exists();


            if ($exists) {
                $number++;
            }

        } while ($exists);


        return $cardNo;
    }
}