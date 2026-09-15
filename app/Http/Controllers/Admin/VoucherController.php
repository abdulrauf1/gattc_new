<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::query()
            ->with([
                'course',
                'bankAccount',
                'session',
                'payment',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'voucher_no',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'applicant_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'cnic',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->when(
                $request->filled('voucher_type'),
                fn ($q) =>
                    $q->where(
                        'voucher_type',
                        $request->voucher_type
                    )
            )
            ->when(
                $request->filled('status'),
                fn ($q) =>
                    $q->where(
                        'status',
                        $request->status
                    )
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.vouchers.index',
            compact('vouchers')
        );
    }

    /**
     * Show unified voucher-generation form.
     */
    public function create()
    {
        $courses = Course::query()
            ->where('status', true)
            ->with('bankAccount')
            ->orderBy('title')
            ->get();

        $bankAccounts = BankAccount::where('status', true)
            ->orderBy('account_title')
            ->get();

        $sessions = AdmissionSession::latest('id')->get();

        return view(
            'admin.vouchers.create',
            compact(
                'courses',
                'bankAccounts',
                'sessions'
            )
        );
    }

    /**
     * Generate voucher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_type' => [
                'required',
                'in:admission,hostel,readmission',
            ],

            'admission_session_id' => [
                'nullable',
                'exists:admission_sessions,id',
            ],

            'course_id' => [
                'nullable',
                'exists:courses,id',
            ],

            'bank_account_id' => [
                'nullable',
                'exists:bank_accounts,id',
            ],

            'student_name' => [
                'required',
                'string',
                'max:150',
            ],

            'father_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'cnic' => [
                'nullable',
                'string',
                'max:30',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                'in:Male,Female,Other',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $course = null;
        $bankAccount = null;

        /*
         * ADMISSION / RE-ADMISSION
         *
         * If a course is selected, the course controls
         * the fee and bank account.
         */
        if (
            in_array(
                $validated['voucher_type'],
                ['admission', 'readmission'],
                true
            )
        ) {
            if (empty($validated['course_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'course_id' =>
                            'Please select a course.',
                    ]);
            }

            $course = Course::with('bankAccount')
                ->whereKey($validated['course_id'])
                ->where('status', true)
                ->first();

            if (!$course) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'course_id' =>
                            'Selected course is not available.',
                    ]);
            }

            if (!$course->bankAccount) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'course_id' =>
                            'No bank account is configured for this course.',
                    ]);
            }

            /*
             * Admission and readmission use the course fee
             * by default.
             */
            if (
                empty($validated['amount']) ||
                $validated['voucher_type'] === 'admission'
            ) {
                $validated['amount'] =
                    $course->fee_amount;
            }

            $bankAccount = $course->bankAccount;
        }

        /*
         * HOSTEL
         *
         * Admin explicitly selects the Hostel bank account
         * and enters the hostel fee.
         */
        if ($validated['voucher_type'] === 'hostel') {
            if (empty($validated['bank_account_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'bank_account_id' =>
                            'Please select the hostel bank account.',
                    ]);
            }

            if (empty($validated['amount'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'amount' =>
                            'Please enter the hostel fee amount.',
                    ]);
            }

            $bankAccount = BankAccount::whereKey(
                $validated['bank_account_id']
            )
                ->where('status', true)
                ->first();

            if (!$bankAccount) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'bank_account_id' =>
                            'Selected bank account is not active.',
                    ]);
            }

            $course = null;
        }

        $voucher = DB::transaction(
            function () use (
                $validated,
                $course,
                $bankAccount
            ) {
                return Voucher::create([
                    'voucher_no' =>
                        $this->generateVoucherNumber(),

                    'admission_session_id' =>
                        $validated['admission_session_id']
                        ?? null,

                    'course_id' =>
                        $course?->id,

                    'admission_id' => null,

                    'bank_account_id' =>
                        $bankAccount->id,

                    'voucher_type' =>
                        $validated['voucher_type'],

                    'applicant_name' =>
                        $validated['student_name'],

                    'father_name' =>
                        $validated['father_name'] ?? null,

                    'cnic' =>
                        $validated['cnic'] ?? null,

                    'date_of_birth' =>
                        $validated['date_of_birth'] ?? null,

                    'gender' =>
                        $validated['gender'] ?? null,

                    'phone' =>
                        $validated['phone'] ?? null,

                    'email' =>
                        $validated['email'] ?? null,

                    'address' =>
                        $validated['address'] ?? null,

                    'amount' =>
                        $validated['amount'],

                    'issue_date' => today(),

                    'due_date' =>
                        $validated['due_date']
                        ?? today()->addDays(7),

                    'status' => 'generated',

                    'remarks' =>
                        $validated['remarks']
                        ?? ucfirst(
                            $validated['voucher_type']
                        ) .
                        ' voucher generated by administration.',

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
        );

        return redirect()
            ->route(
                'admin.vouchers.show',
                $voucher
            )
            ->with(
                'success',
                'Voucher generated successfully.'
            );
    }

    public function show(Voucher $voucher)
    {
        $voucher->load([
            'course',
            'bankAccount',
            'session',
            'payment',
        ]);

        return view(
            'admin.vouchers.show',
            compact('voucher')
        );
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->payment) {
            return back()->with(
                'error',
                'A voucher with a payment record cannot be deleted.'
            );
        }

        $voucher->delete();

        return back()->with(
            'success',
            'Voucher deleted successfully.'
        );
    }

    private function generateVoucherNumber(): string
    {
        do {
            $number = 'GATTC-' .
                now()->format('Y') .
                '-' .
                strtoupper(Str::random(8));
        } while (
            Voucher::where('voucher_no', $number)
                ->exists()
        );

        return $number;
    }
}