<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Voucher List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $type = $request->input('type');

        $query = Voucher::query()
            ->with([
                'course:id,title,course_type',
                'session:id,title',
                'admission:id,admission_no,status',
                'bankAccount:id,account_title,account_number,purpose',
            ])
            ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | Tabs
        |--------------------------------------------------------------------------
        */

        if (in_array($type, ['admission', 'hostel', 'readmission'], true)) {
            $query->where('voucher_type', $type);
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('voucher_no', 'like', "%{$search}%")
                    ->orWhere('applicant_name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('cnic', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('admission', function ($admission) use ($search) {
                        $admission
                            ->where('admission_no', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Course Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $vouchers = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Tab Statistics
        |--------------------------------------------------------------------------
        */

        $totalVouchers = Voucher::count();

        $admissionVouchers = Voucher::where(
            'voucher_type',
            'admission'
        )->count();

        $hostelVouchers = Voucher::where(
            'voucher_type',
            'hostel'
        )->count();

        $readmissionVouchers = Voucher::where(
            'voucher_type',
            'readmission'
        )->count();

        $generatedVouchers = Voucher::where(
            'status',
            'generated'
        )->count();

        $paidVouchers = Voucher::where(
            'status',
            'paid'
        )->count();

        $cancelledVouchers = Voucher::where(
            'status',
            'cancelled'
        )->count();

        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view('admin.vouchers.index', compact(
            'vouchers',
            'courses',
            'type',
            'totalVouchers',
            'admissionVouchers',
            'hostelVouchers',
            'readmissionVouchers',
            'generatedVouchers',
            'paidVouchers',
            'cancelledVouchers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Voucher
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $type = $request->get('type', 'admission');

        if (!in_array($type, ['admission', 'hostel', 'readmission'], true)) {
            $type = 'admission';
        }

        $sessions = AdmissionSession::query()
            ->orderByDesc('opening_date')
            ->get();

        $courses = Course::query()
            ->with('bankAccount')
            ->where('status', true)
            ->orderBy('course_type')
            ->orderBy('title')
            ->get();

        $hostelAccount = BankAccount::where(
            'purpose',
            'like',
            '%Hostel%'
        )
            ->where('status', true)
            ->first();

        $admissions = Admission::query()
            ->with([
                'course:id,title',
                'session:id,title',
            ])
            ->latest('id')
            ->limit(100)
            ->get();

        return view('admin.vouchers.create', compact(
            'type',
            'sessions',
            'courses',
            'hostelAccount',
            'admissions'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Voucher
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $voucherType = $request->input('voucher_type');

        if (!in_array(
            $voucherType,
            ['admission', 'hostel', 'readmission'],
            true
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'voucher_type' => 'Invalid voucher type.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Common Validation
        |--------------------------------------------------------------------------
        */

        $rules = [
            'voucher_type' => [
                'required',
                Rule::in([
                    'admission',
                    'hostel',
                    'readmission',
                ]),
            ],

            'applicant_name' => [
                'required',
                'string',
                'max:255',
            ],

            'father_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cnic' => [
                'required',
                'string',
                'max:30',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
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
                'nullable',
                'string',
                'max:1000',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:issue_date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Admission / Readmission
        |--------------------------------------------------------------------------
        */

        if ($voucherType === 'admission') {

            $rules['admission_session_id'] = [
                'required',
                'exists:admission_sessions,id',
            ];

            $rules['course_id'] = [
                'required',
                'exists:courses,id',
            ];
        }

        if ($voucherType === 'readmission') {

            $rules['admission_id'] = [
                'required',
                'exists:admissions,id',
            ];

            $rules['course_id'] = [
                'required',
                'exists:courses,id',
            ];

            $rules['admission_session_id'] = [
                'nullable',
                'exists:admission_sessions,id',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hostel
        |--------------------------------------------------------------------------
        */

        if ($voucherType === 'hostel') {
            $rules['admission_id'] = [
                'nullable',
                'exists:admissions,id',
            ];
        }

        $validated = $request->validate($rules);

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        $voucher = DB::transaction(function () use (
            $validated,
            $voucherType
        ) {

            $admission = null;
            $course = null;
            $bankAccount = null;
            $session = null;

            /*
            |--------------------------------------------------------------------------
            | ADMISSION VOUCHER
            |--------------------------------------------------------------------------
            */

            if ($voucherType === 'admission') {

                $session = AdmissionSession::findOrFail(
                    $validated['admission_session_id']
                );

                $course = Course::with('bankAccount')
                    ->findOrFail($validated['course_id']);

                /*
                | Course must have its bank account.
                */

                if (!$course->bankAccount) {
                    throw new \RuntimeException(
                        'The selected course does not have a bank account assigned.'
                    );
                }

                $bankAccount = $course->bankAccount;

                /*
                |--------------------------------------------------------------------------
                | Create Admission Immediately
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Admin-generated admission voucher creates the
                | admission immediately with PENDING status.
                |
                */

                $admission = Admission::create([
                    'admission_no' => $this->generateAdmissionNo(),

                    'admission_session_id' =>
                        $session->id,

                    'course_id' =>
                        $course->id,

                    'student_name' =>
                        $validated['applicant_name'],

                    'father_name' =>
                        $validated['father_name'] ?? null,

                    'cnic' =>
                        $validated['cnic'],

                    'date_of_birth' =>
                        $validated['date_of_birth'] ?? null,

                    'gender' =>
                        $validated['gender'] ?? null,

                    'phone' =>
                        $validated['phone'],

                    'email' =>
                        $validated['email'] ?? null,

                    'address' =>
                        $validated['address'] ?? null,

                    'status' =>
                        'pending',

                    'remarks' =>
                        'Admission created by administration while generating voucher.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | READMISSION
            |--------------------------------------------------------------------------
            */

            elseif ($voucherType === 'readmission') {

                $admission = Admission::findOrFail(
                    $validated['admission_id']
                );

                $course = Course::with('bankAccount')
                    ->findOrFail($validated['course_id']);

                if (!$course->bankAccount) {
                    throw new \RuntimeException(
                        'The selected course does not have a bank account assigned.'
                    );
                }

                $bankAccount = $course->bankAccount;

                $session = !empty($validated['admission_session_id'])
                    ? AdmissionSession::find(
                        $validated['admission_session_id']
                    )
                    : $admission->session;
            }

            /*
            |--------------------------------------------------------------------------
            | HOSTEL
            |--------------------------------------------------------------------------
            */

            elseif ($voucherType === 'hostel') {

                $bankAccount = BankAccount::where(
                    'purpose',
                    'like',
                    '%Hostel%'
                )
                    ->where('status', true)
                    ->first();

                if (!$bankAccount) {
                    throw new \RuntimeException(
                        'Active Hostel bank account was not found.'
                    );
                }

                /*
                | Hostel does NOT create a new admission.
                */

                if (!empty($validated['admission_id'])) {
                    $admission = Admission::find(
                        $validated['admission_id']
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Voucher Number
            |--------------------------------------------------------------------------
            */

            $voucherNo = $this->generateVoucherNo();

            /*
            |--------------------------------------------------------------------------
            | Create Voucher
            |--------------------------------------------------------------------------
            */

            return Voucher::create([

                'voucher_no' =>
                    $voucherNo,

                'admission_session_id' =>
                    $session?->id,

                'course_id' =>
                    $course?->id,

                'admission_id' =>
                    $admission?->id,

                'bank_account_id' =>
                    $bankAccount->id,

                'voucher_type' =>
                    $voucherType,

                'applicant_name' =>
                    $validated['applicant_name'],

                'father_name' =>
                    $validated['father_name'] ?? null,

                'cnic' =>
                    $validated['cnic'],

                'date_of_birth' =>
                    $validated['date_of_birth'] ?? null,

                'gender' =>
                    $validated['gender'] ?? null,

                'phone' =>
                    $validated['phone'],

                'email' =>
                    $validated['email'] ?? null,

                'address' =>
                    $validated['address'] ?? null,

                'amount' =>
                    $validated['amount'],

                'issue_date' =>
                    $validated['issue_date'],

                'due_date' =>
                    $validated['due_date'],

                'status' =>
                    'generated',

                'remarks' =>
                    $validated['remarks'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Snapshot Bank Information
                |--------------------------------------------------------------------------
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
        });

        return redirect()
            ->route('admin.vouchers.show', $voucher)
            ->with(
                'success',
                'Voucher generated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Voucher Details
    |--------------------------------------------------------------------------
    */

    public function show(Voucher $voucher)
    {
        $voucher->load([
            'session',
            'course.category',
            'bankAccount',
            'admission',
            'payment',
        ]);

        return view(
            'admin.vouchers.show',
            compact('voucher')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Voucher
    |--------------------------------------------------------------------------
    */

    public function destroy(Voucher $voucher)
    {
        /*
        | Do not delete paid vouchers.
        */

        if ($voucher->status === 'paid') {
            return back()->withErrors([
                'voucher' =>
                    'Paid vouchers cannot be deleted.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Admission Created Only For This Voucher
        |--------------------------------------------------------------------------
        |
        | We do NOT automatically delete the admission here.
        | The admission is an official record and should remain
        | for administrative tracking.
        |
        */

        $voucher->delete();

        return redirect()
            ->route('admin.vouchers.index')
            ->with(
                'success',
                'Voucher deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Voucher Number
    |--------------------------------------------------------------------------
    */

    private function generateVoucherNo(): string
    {
        $year = now()->format('Y');

        $lastVoucher = Voucher::query()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->lockForUpdate()
            ->first();

        $number = $lastVoucher
            ? ((int) substr($lastVoucher->voucher_no, -5)) + 1
            : 1;

        return 'VCH-' . $year . '-' . str_pad(
            $number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admission Number
    |--------------------------------------------------------------------------
    */

    private function generateAdmissionNo(): string
    {
        $year = now()->format('Y');

        $lastAdmission = Admission::query()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->lockForUpdate()
            ->first();

        $number = $lastAdmission
            ? ((int) substr($lastAdmission->admission_no, -5)) + 1
            : 1;

        return 'ADM-' . $year . '-' . str_pad(
            $number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}