<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSession;
use App\Models\Course;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicAdmissionController extends Controller
{
    /**
     * Show public online admission form.
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

        /*
         * Only show active courses which have been added
         * to the current admission session.
         */
        $courses = $activeSession->courses()
            ->where('courses.status', true)
            ->with('bankAccount')
            ->orderBy('courses.title')
            ->get();

        return view('public.admission', [
            'activeSession' => $activeSession,
            'courses' => $courses,
        ]);
    }

    /**
     * Generate an admission voucher from the public site.
     *
     * Important:
     * The application is NOT converted into an approved admission here.
     * The student first pays the voucher and submits the paid challan.
     */
    public function store(Request $request)
    {
        $activeSession = $this->activeAdmissionSession();

        if (!$activeSession) {
            return redirect()
                ->route('public.admission')
                ->with('error', 'Online admissions are currently closed.');
        }

        $validated = $request->validate([
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
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
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
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
        ]);

        /*
         * Get the selected course.
         */
        $course = $activeSession->courses()
            ->with('bankAccount')
            ->where('courses.id', $validated['course_id'])
            ->where('courses.status', true)
            ->first();

        if (!$course) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' =>
                        'The selected course is not available in the current admission session.',
                ]);
        }

        /*
         * Every course must have:
         *
         * 1. A fee
         * 2. A bank account
         */
        if ((float) $course->fee_amount <= 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' =>
                        'The selected course does not have a valid fee amount configured.',
                ]);
        }

        if (!$course->bankAccount || !$course->bankAccount->status) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' =>
                        'No active bank account is configured for the selected course.',
                ]);
        }

        /*
         * Generate voucher.
         */
        $voucher = DB::transaction(function () use (
            $validated,
            $activeSession,
            $course
        ) {
            return $this->createVoucher(
                validated: $validated,
                session: $activeSession,
                course: $course
            );
        });

        return redirect()
            ->route('public.admission.voucher', [
                'voucher' => $voucher->id,
            ])
            ->with(
                'success',
                'Your fee voucher has been generated successfully.'
            );
    }

    /**
     * Display generated voucher.
     */
    public function voucher(Voucher $voucher)
    {
        $voucher->load([
            'course',
            'bankAccount',
            'session',
        ]);

        return view('public.voucher', [
            'voucher' => $voucher,
        ]);
    }

    /**
     * Create admission voucher.
     */
    private function createVoucher(
        array $validated,
        AdmissionSession $session,
        Course $course
    ): Voucher {
        $bank = $course->bankAccount;

        return Voucher::create([
            'voucher_no' => $this->generateVoucherNumber(),

            'admission_session_id' => $session->id,
            'course_id' => $course->id,
            'admission_id' => null,

            'bank_account_id' => $bank->id,

            'voucher_type' => 'admission',

            'applicant_name' => $validated['student_name'],
            'father_name' => $validated['father_name'],
            'cnic' => $validated['cnic'],

            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,

            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'],

            'amount' => $course->fee_amount,

            'issue_date' => today(),
            'due_date' => today()->addDays(7),

            'status' => 'generated',

            'remarks' => 'Online admission fee voucher.',

            /*
             * Bank snapshot.
             */
            'bank_name' => $bank->bank_name,
            'account_title' => $bank->account_title,
            'account_number' => $bank->account_number,
            'iban' => $bank->iban,
            'branch_name' => $bank->branch_name,
            'branch_code' => $bank->branch_code,
        ]);
    }

    /**
     * Find currently open admission session.
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
     * Generate unique voucher number.
     */
    private function generateVoucherNumber(): string
    {
        do {
            $number = 'GATTC-' .
                now()->format('Y') .
                '-' .
                strtoupper(Str::random(8));
        } while (
            Voucher::where('voucher_no', $number)->exists()
        );

        return $number;
    }
}