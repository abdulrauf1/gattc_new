@extends('layouts.admin')

@section('page-heading', 'Generate Voucher')

@section('content')
@php
    $today = \Illuminate\Support\Carbon::today();
    $hasCurrentlyOpenSession = collect($sessions ?? [])->contains(function ($session) use ($today) {
        if (!(bool) $session->is_open) {
            return false;
        }

        try {
            $opens = $session->opening_date
                ? \Illuminate\Support\Carbon::parse($session->opening_date)->startOfDay()
                : null;
            $closes = $session->closing_date
                ? \Illuminate\Support\Carbon::parse($session->closing_date)->startOfDay()
                : null;

            return (!$opens || $opens->lte($today)) && (!$closes || $closes->gte($today));
        } catch (\Throwable $e) {
            return false;
        }
    });
@endphp

<div class="max-w-7xl mx-auto space-y-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Generate Voucher</h1>
            <p class="text-xs text-gray-500 mt-0.5">Generate an official GATTC fee challan from the administration office.</p>
        </div>
        <a href="{{ route('admin.vouchers.index') }}"
           class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to vouchers
        </a>
    </div>

    {{-- Server-side validation errors --}}
    @if($errors->any())
        <div id="server-error-summary" tabindex="-1" role="alert"
             class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <div class="flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-semibold">Please correct the following fields</p>
                    <ul class="list-disc ml-5 mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Client-side validation summary --}}
    <div id="client-validation-summary" tabindex="-1" role="alert" aria-live="assertive"
         class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <div class="flex items-start gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <div class="min-w-0">
                <p class="font-semibold">Please review the following items</p>
                <ul id="client-validation-list" class="list-disc ml-5 mt-1 space-y-0.5"></ul>
            </div>
        </div>
    </div>

    {{-- Admission closed notice --}}
    @if($type === 'admission' && !$hasCurrentlyOpenSession)
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 sm:p-5" role="status">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100">
                    <i data-lucide="calendar-x" class="h-5 w-5 text-red-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-red-800">Admission Process Closed</h2>
                    <p class="mt-1 text-sm text-red-700">There is no admission session currently open for voucher generation. Please check that the session is enabled and today falls between its opening and closing dates.</p>
                    <p class="mt-2 text-sm text-gray-700">Hostel and readmission vouchers can still be generated using their respective voucher types.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Voucher Type --}}
    <section class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4" aria-labelledby="voucher-type-heading">
        <div class="mb-3">
            <h2 id="voucher-type-heading" class="text-sm font-semibold text-gray-900">Voucher Type</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">Choose the fee challan you need to generate.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">
            <a href="{{ route('admin.vouchers.create', ['type' => 'admission']) }}"
               @if($type === 'admission') aria-current="page" @endif
               class="rounded-lg border p-3 transition focus:outline-none focus:ring-2 focus:ring-blue-400 {{ $type === 'admission' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-100' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50/40' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0"><i data-lucide="graduation-cap" class="w-4 h-4 text-blue-600"></i></div>
                    <div class="min-w-0"><p class="text-sm font-semibold text-gray-900">Admission Voucher</p><p class="text-[11px] text-gray-500 mt-0.5">Creates a pending admission record.</p></div>
                    @if($type === 'admission')<i data-lucide="check-circle-2" class="w-4 h-4 text-blue-600 ml-auto shrink-0"></i>@endif
                </div>
            </a>
            <a href="{{ route('admin.vouchers.create', ['type' => 'hostel']) }}"
               @if($type === 'hostel') aria-current="page" @endif
               class="rounded-lg border p-3 transition focus:outline-none focus:ring-2 focus:ring-purple-400 {{ $type === 'hostel' ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-100' : 'border-gray-200 hover:border-purple-300 hover:bg-purple-50/40' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center shrink-0"><i data-lucide="bed-double" class="w-4 h-4 text-purple-600"></i></div>
                    <div class="min-w-0"><p class="text-sm font-semibold text-gray-900">Hostel Voucher</p><p class="text-[11px] text-gray-500 mt-0.5">Hostel fee only; does not create a new admission.</p></div>
                    @if($type === 'hostel')<i data-lucide="check-circle-2" class="w-4 h-4 text-purple-600 ml-auto shrink-0"></i>@endif
                </div>
            </a>
            <a href="{{ route('admin.vouchers.create', ['type' => 'readmission']) }}"
               @if($type === 'readmission') aria-current="page" @endif
               class="rounded-lg border p-3 transition focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $type === 'readmission' ? 'border-amber-500 bg-amber-50 ring-1 ring-amber-100' : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50/40' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0"><i data-lucide="refresh-cw" class="w-4 h-4 text-amber-600"></i></div>
                    <div class="min-w-0"><p class="text-sm font-semibold text-gray-900">Readmission Voucher</p><p class="text-[11px] text-gray-500 mt-0.5">For an existing admission.</p></div>
                    @if($type === 'readmission')<i data-lucide="check-circle-2" class="w-4 h-4 text-amber-600 ml-auto shrink-0"></i>@endif
                </div>
            </a>
        </div>
    </section>

    {{-- Main form --}}
    <form id="voucher-form" method="POST" action="{{ route('admin.vouchers.store') }}"
          class="bg-white border border-gray-200 rounded-xl overflow-hidden"
          novalidate
          data-voucher-type="{{ $type }}"
          data-admission-available="{{ $hasCurrentlyOpenSession ? '1' : '0' }}"
          data-hostel-account-available="{{ $hostelAccount ? '1' : '0' }}">
        @csrf
        <input type="hidden" name="voucher_type" value="{{ $type }}">

        {{-- Student information --}}
        <section class="p-4 border-b border-gray-200" aria-labelledby="student-information-heading">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center"><i data-lucide="user-round" class="w-4 h-4 text-blue-600"></i></div>
                <div><h2 id="student-information-heading" class="text-sm font-semibold text-gray-900">Student Information</h2><p class="text-[11px] text-gray-500 mt-0.5">Enter the details exactly as provided on the application.</p></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div id="field-applicant_name" class="lg:col-span-2">
                    <label for="applicant_name" class="form-label">Student Name <span>*</span></label>
                    <input id="applicant_name" type="text" name="applicant_name" value="{{ old('applicant_name') }}" required maxlength="255" autocomplete="name" autofocus
                           class="form-input @error('applicant_name') is-invalid @enderror" placeholder="Enter student's full name" aria-describedby="error-applicant_name" aria-invalid="{{ $errors->has('applicant_name') ? 'true' : 'false' }}">
                    <p id="error-applicant_name" class="field-error {{ $errors->has('applicant_name') ? '' : 'hidden' }}" data-error-for="applicant_name">@error('applicant_name'){{ $message }}@enderror</p>
                </div>
                <div id="field-father_name" class="lg:col-span-2">
                    <label for="father_name" class="form-label">Father Name<span>*</span></label>
                    <input id="father_name" type="text" name="father_name" value="{{ old('father_name') }}" required maxlength="255" autocomplete="off"
                           class="form-input @error('father_name') is-invalid @enderror" placeholder="Enter father's name" aria-describedby="error-father_name" aria-invalid="{{ $errors->has('father_name') ? 'true' : 'false' }}">
                    <p id="error-father_name" class="field-error {{ $errors->has('father_name') ? '' : 'hidden' }}" data-error-for="father_name">@error('father_name'){{ $message }}@enderror</p>
                </div>
                <div id="field-cnic">
                    <label for="cnic" class="form-label">CNIC / Form-B <span>*</span></label>
                    <input id="cnic" type="text" name="cnic" value="{{ old('cnic') }}" required maxlength="13" inputmode="numeric" autocomplete="off"
                           class="form-input @error('cnic') is-invalid @enderror" placeholder="XXXXX-XXXXXXX-X" aria-describedby="hint-cnic error-cnic" aria-invalid="{{ $errors->has('cnic') ? 'true' : 'false' }}">
                    <p id="hint-cnic" class="field-hint">13 digits; hyphens are optional.</p>
                    <p id="error-cnic" class="field-error {{ $errors->has('cnic') ? '' : 'hidden' }}" data-error-for="cnic">@error('cnic'){{ $message }}@enderror</p>
                </div>
                <div id="field-phone">
                    <label for="phone" class="form-label">Contact Number <span>*</span></label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="30" inputmode="tel" autocomplete="tel"
                           class="form-input @error('phone') is-invalid @enderror" placeholder="03XX-XXXXXXX" aria-describedby="hint-phone error-phone" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}">
                    <p id="hint-phone" class="field-hint">Use a Pakistani mobile number, e.g. 03001234567 or +923001234567.</p>
                    <p id="error-phone" class="field-error {{ $errors->has('phone') ? '' : 'hidden' }}" data-error-for="phone">@error('phone'){{ $message }}@enderror</p>
                </div>
                <div id="field-date_of_birth">
                    <label for="date_of_birth" class="form-label">Date of Birth <span>*</span></label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ $today->toDateString() }}"
                           class="form-input @error('date_of_birth') is-invalid @enderror" aria-describedby="error-date_of_birth" aria-invalid="{{ $errors->has('date_of_birth') ? 'true' : 'false' }}">
                    <p id="error-date_of_birth" class="field-error {{ $errors->has('date_of_birth') ? '' : 'hidden' }}" data-error-for="date_of_birth">@error('date_of_birth'){{ $message }}@enderror</p>
                </div>
                <div id="field-gender">
                    <label for="gender" class="form-label">Gender <span>*</span></label>
                    <select id="gender" name="gender" class="form-input @error('gender') is-invalid @enderror" aria-describedby="error-gender" aria-invalid="{{ $errors->has('gender') ? 'true' : 'false' }}">
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                    </select>
                    <p id="error-gender" class="field-error {{ $errors->has('gender') ? '' : 'hidden' }}" data-error-for="gender">@error('gender'){{ $message }}@enderror</p>
                </div>
                <div id="field-email" class="lg:col-span-2">
                    <label for="email" class="form-label">Email Address <span>*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email"
                           class="form-input @error('email') is-invalid @enderror" placeholder="student@example.com" aria-describedby="error-email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    <p id="error-email" class="field-error {{ $errors->has('email') ? '' : 'hidden' }}" data-error-for="email">@error('email'){{ $message }}@enderror</p>
                </div>
                <div id="field-address" class="sm:col-span-2 lg:col-span-4">
                    <label for="address" class="form-label">Residential Address <span>*</span></label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" maxlength="1000" autocomplete="street-address"
                           class="form-input @error('address') is-invalid @enderror" placeholder="Enter complete residential address" aria-describedby="hint-address error-address" aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}">
                    <div class="flex justify-between gap-3"><p id="hint-address" class="field-hint">Optional, up to 1,000 characters.</p><p id="address-counter" class="field-hint tabular-nums">{{ mb_strlen(old('address', '')) }}/1000</p></div>
                    <p id="error-address" class="field-error {{ $errors->has('address') ? '' : 'hidden' }}" data-error-for="address">@error('address'){{ $message }}@enderror</p>
                </div>
            </div>
        </section>

        {{-- Admission details --}}
        @if($type === 'admission')
            <section class="p-4 border-b border-gray-200" aria-labelledby="admission-details-heading">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center"><i data-lucide="graduation-cap" class="w-4 h-4 text-emerald-600"></i></div>
                    <div><h2 id="admission-details-heading" class="text-sm font-semibold text-gray-900">Admission Details</h2><p class="text-[11px] text-gray-500 mt-0.5">Select the currently open session and the relevant course.</p></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <div id="field-admission_session_id">
                        <label for="admission_session_id" class="form-label">Admission Session <span>*</span></label>
                        <select id="admission_session_id" name="admission_session_id" required class="form-input @error('admission_session_id') is-invalid @enderror" aria-describedby="error-admission_session_id" aria-invalid="{{ $errors->has('admission_session_id') ? 'true' : 'false' }}">
                            <option value="">Select admission session</option>
                            @foreach($sessions as $session)
                                @php
                                    $sessionOpen = false;
                                    try {
                                        $sessionOpen = (bool) $session->is_open
                                            && (!$session->opening_date || \Illuminate\Support\Carbon::parse($session->opening_date)->startOfDay()->lte($today))
                                            && (!$session->closing_date || \Illuminate\Support\Carbon::parse($session->closing_date)->startOfDay()->gte($today));
                                    } catch (\Throwable $e) { $sessionOpen = false; }
                                @endphp
                                <option value="{{ $session->id }}"
                                        data-currently-open="{{ $sessionOpen ? '1' : '0' }}"
                                        @selected((string) old('admission_session_id') === (string) $session->id)
                                        @disabled(!$sessionOpen)>
                                    {{ $session->title }}{{ $sessionOpen ? ' — Open' : ' — Not currently open' }}
                                </option>
                            @endforeach
                        </select>
                        <p id="error-admission_session_id" class="field-error {{ $errors->has('admission_session_id') ? '' : 'hidden' }}" data-error-for="admission_session_id">@error('admission_session_id'){{ $message }}@enderror</p>
                    </div>
                    <div id="field-course_id" class="lg:col-span-2">
                        <label for="course_id" class="form-label">Course <span>*</span></label>
                        <select id="course_id" name="course_id" required disabled class="form-input @error('course_id') is-invalid @enderror" aria-describedby="admission-course-filter-hint error-course_id" aria-invalid="{{ $errors->has('course_id') ? 'true' : 'false' }}">
                            <option value="">Select an admission session first</option>
                            @foreach($courses as $course)
                                @php
                                    $assignedSessionIds = array_map('strval', $sessionCourseIds[$course->id] ?? []);
                                    $oldSessionId = (string) old('admission_session_id', '');
                                    $isAssignedToOldSession = $oldSessionId !== '' && in_array($oldSessionId, $assignedSessionIds, true);
                                @endphp
                                <option value="{{ $course->id }}"
                                        data-session-ids="{{ implode(',', $assignedSessionIds) }}"
                                        data-fee="{{ $course->fee_amount ?? 0 }}"
                                        data-course-type="{{ $course->course_type }}"
                                        data-bank-account-id="{{ $course->bank_account_id ?? '' }}"
                                        data-bank-title="{{ $course->bankAccount?->account_title ?? '' }}"
                                        data-bank-number="{{ $course->bankAccount?->account_number ?? '' }}"
                                        data-bank-purpose="{{ $course->bankAccount?->purpose ?? '' }}"
                                        @if(!$isAssignedToOldSession) hidden disabled @endif
                                        @selected((string) old('course_id') === (string) $course->id)>
                                    {{ $course->title }} — {{ ucfirst($course->course_type) }}
                                </option>
                            @endforeach
                        </select>
                        <p id="admission-course-filter-hint" class="field-hint" aria-live="polite">Select an admission session to see its assigned courses only.</p>
                        <p id="error-course_id" class="field-error {{ $errors->has('course_id') ? '' : 'hidden' }}" data-error-for="course_id">@error('course_id'){{ $message }}@enderror</p>
                    </div>
                </div>

                <div id="course-preview" class="hidden mt-3 rounded-lg border border-emerald-200 bg-emerald-50 p-3" aria-live="polite">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div><p class="preview-label">Course Type</p><p id="preview-course-type" class="preview-value">—</p></div>
                        <div><p class="preview-label">Course Fee</p><p id="preview-fee" class="preview-value">—</p><p class="field-hint">This value fills the Admission amount.</p></div>
                        <div><p class="preview-label">Fee Deposited Into</p><p id="preview-bank" class="preview-value">—</p><p id="preview-account" class="text-[11px] text-gray-500 mt-0.5"></p></div>
                    </div>
                    <div id="course-bank-warning" class="hidden mt-3 rounded-md bg-red-50 border border-red-200 px-3 py-2 text-xs text-red-800" role="alert">No bank account is assigned to this course. Assign the correct receiving account before generating the voucher.</div>
                    <div id="imc-notice" class="hidden mt-3 rounded-md bg-indigo-50 border border-indigo-200 px-3 py-2">
                        <div class="flex gap-2"><i data-lucide="building-2" class="w-4 h-4 text-indigo-600 mt-0.5"></i><p class="text-xs text-indigo-800">This is a <strong>Private / IMC</strong> course. The voucher will use the bank account assigned to this course.</p></div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Readmission details --}}
        @if($type === 'readmission')
            <section class="p-4 border-b border-gray-200" aria-labelledby="readmission-heading">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center"><i data-lucide="refresh-cw" class="w-4 h-4 text-amber-600"></i></div>
                    <div><h2 id="readmission-heading" class="text-sm font-semibold text-gray-900">Existing Admission</h2><p class="text-[11px] text-gray-500 mt-0.5">Select the student’s existing admission. The related course will be selected for you.</p></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                    <div id="field-admission_id">
                        <label for="admission_id" class="form-label">Existing Admission <span>*</span></label>
                        <select id="admission_id" name="admission_id" required class="form-input @error('admission_id') is-invalid @enderror" aria-describedby="error-admission_id" aria-invalid="{{ $errors->has('admission_id') ? 'true' : 'false' }}">
                            <option value="">Select admission</option>
                            @foreach($admissions as $admission)
                                <option value="{{ $admission->id }}"
                                        data-course-id="{{ $admission->course_id ?? '' }}"
                                        data-name="{{ $admission->student_name ?? '' }}"
                                        data-father-name="{{ $admission->father_name ?? '' }}"
                                        data-cnic="{{ $admission->cnic ?? '' }}"
                                        data-phone="{{ $admission->phone ?? '' }}"
                                        data-dob="{{ $admission->date_of_birth ? \Illuminate\Support\Carbon::parse($admission->date_of_birth)->format('Y-m-d') : '' }}"
                                        data-gender="{{ $admission->gender ?? '' }}"
                                        data-email="{{ $admission->email ?? '' }}"
                                        data-address="{{ $admission->address ?? '' }}"
                                        @selected((string) old('admission_id') === (string) $admission->id)>
                                    {{ $admission->admission_no }} — {{ $admission->student_name }} — {{ $admission->course?->title }}
                                </option>
                            @endforeach
                        </select>
                        <p id="error-admission_id" class="field-error {{ $errors->has('admission_id') ? '' : 'hidden' }}" data-error-for="admission_id">@error('admission_id'){{ $message }}@enderror</p>
                    </div>
                    <div id="field-course_id">
                        <label for="course_id" class="form-label">Course <span>*</span></label>
                        <select id="course_id" name="course_id" required class="form-input @error('course_id') is-invalid @enderror" aria-describedby="hint-readmission-course error-course_id" aria-invalid="{{ $errors->has('course_id') ? 'true' : 'false' }}">
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                        data-fee="{{ $course->fee_amount ?? 0 }}"
                                        data-course-type="{{ $course->course_type }}"
                                        data-bank-account-id="{{ $course->bank_account_id ?? '' }}"
                                        @selected((string) old('course_id') === (string) $course->id)>
                                    {{ $course->title }} — {{ ucfirst($course->course_type) }}
                                </option>
                            @endforeach
                        </select>
                        <p id="hint-readmission-course" class="field-hint">The selected course must match the existing admission.</p>
                        <p id="error-course_id" class="field-error {{ $errors->has('course_id') ? '' : 'hidden' }}" data-error-for="course_id">@error('course_id'){{ $message }}@enderror</p>
                    </div>
                </div>
                <div id="readmission-course-note" class="hidden mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800" aria-live="polite">The course selection has been matched to the selected admission. Enter the approved Re-Admission Fee below; this can differ from the regular course fee.</div>
            </section>
        @endif

        {{-- Hostel details --}}
        @if($type === 'hostel')
            <section class="p-4 border-b border-gray-200" aria-labelledby="hostel-heading">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center"><i data-lucide="bed-double" class="w-4 h-4 text-purple-600"></i></div>
                    <div><h2 id="hostel-heading" class="text-sm font-semibold text-gray-900">Hostel Voucher</h2><p class="text-[11px] text-gray-500 mt-0.5">Optionally link this hostel voucher to an existing admission.</p></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                    <div id="field-admission_id">
                        <label for="admission_id" class="form-label">Existing Admission <span class="font-normal text-gray-400">(optional)</span></label>
                        <select id="admission_id" name="admission_id" class="form-input @error('admission_id') is-invalid @enderror" aria-describedby="hint-hostel-admission error-admission_id" aria-invalid="{{ $errors->has('admission_id') ? 'true' : 'false' }}">
                            <option value="">Standalone hostel voucher</option>
                            @foreach($admissions as $admission)
                                <option value="{{ $admission->id }}"
                                        data-name="{{ $admission->student_name ?? '' }}"
                                        data-father-name="{{ $admission->father_name ?? '' }}"
                                        data-cnic="{{ $admission->cnic ?? '' }}"
                                        data-phone="{{ $admission->phone ?? '' }}"
                                        data-dob="{{ $admission->date_of_birth ? \Illuminate\Support\Carbon::parse($admission->date_of_birth)->format('Y-m-d') : '' }}"
                                        data-gender="{{ $admission->gender ?? '' }}"
                                        data-email="{{ $admission->email ?? '' }}"
                                        data-address="{{ $admission->address ?? '' }}"
                                        @selected((string) old('admission_id') === (string) $admission->id)>
                                    {{ $admission->admission_no }} — {{ $admission->student_name }}
                                </option>
                            @endforeach
                        </select>
                        <p id="hint-hostel-admission" class="field-hint">Selecting a record can fill the student details above.</p>
                        <p id="error-admission_id" class="field-error {{ $errors->has('admission_id') ? '' : 'hidden' }}" data-error-for="admission_id">@error('admission_id'){{ $message }}@enderror</p>
                    </div>
                    <div>
                        <label class="form-label">Receiving Bank Account</label>
                        @if($hostelAccount)
                            <div class="min-h-9 px-3 py-2 rounded-lg bg-purple-50 border border-purple-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <span class="text-xs font-semibold text-gray-800">{{ $hostelAccount->account_title }}</span>
                                <span class="text-xs text-gray-600">A/C {{ $hostelAccount->account_number }}</span>
                            </div>
                            <p class="field-hint">The voucher will be assigned to the configured active Hostel account.</p>
                        @else
                            <div class="rounded-lg bg-red-50 border border-red-200 p-3 text-xs text-red-700" role="alert">Hostel bank account is not configured. Configure an active Hostel bank account before generating a voucher.</div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- Voucher information --}}
        <section class="p-4 border-b border-gray-200" aria-labelledby="voucher-information-heading">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center"><i data-lucide="receipt-text" class="w-4 h-4 text-gray-600"></i></div>
                <div><h2 id="voucher-information-heading" class="text-sm font-semibold text-gray-900">Voucher Information</h2><p class="text-[11px] text-gray-500 mt-0.5">Confirm the amount and voucher dates before generating the challan.</p></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div id="field-amount">
                    <label for="amount" class="form-label">Amount (PKR) <span>*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rs.</span>
                        @if($type === 'admission')
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" required readonly
                                   class="form-input pl-9 font-semibold @error('amount') is-invalid @enderror" placeholder="Select a course first" aria-describedby="hint-amount error-amount" aria-invalid="{{ $errors->has('amount') ? 'true' : 'false' }}">
                        @else
                            <input type="number" step="0.01" min="0.01" max="999999999" name="amount" id="amount" value="{{ old('amount') }}" required inputmode="decimal"
                                   class="form-input pl-9 font-semibold @error('amount') is-invalid @enderror" placeholder="Enter approved fee amount" aria-describedby="hint-amount error-amount" aria-invalid="{{ $errors->has('amount') ? 'true' : 'false' }}">
                        @endif
                    </div>
                    <p id="hint-amount" class="field-hint">
                        @if($type === 'admission') The amount is taken from the selected course fee. @elseif($type === 'readmission') Enter the approved Re-Admission Fee; it may differ from the regular course fee. @else Enter the approved Hostel Fee amount. @endif
                    </p>
                    <p id="error-amount" class="field-error {{ $errors->has('amount') ? '' : 'hidden' }}" data-error-for="amount">@error('amount'){{ $message }}@enderror</p>
                </div>
                <div id="field-issue_date">
                    <label for="issue_date" class="form-label">Issue Date <span>*</span></label>
                    <input id="issue_date" type="date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required readonly
                           class="form-input @error('issue_date') is-invalid @enderror" aria-describedby="hint-issue_date error-issue_date" aria-invalid="{{ $errors->has('issue_date') ? 'true' : 'false' }}">
                    <p id="hint-issue_date" class="field-hint">Set automatically to today.</p>
                    <p id="error-issue_date" class="field-error {{ $errors->has('issue_date') ? '' : 'hidden' }}" data-error-for="issue_date">@error('issue_date'){{ $message }}@enderror</p>
                </div>
                <div id="field-due_date">
                    <label for="due_date" class="form-label">Due Date <span>*</span></label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required readonly
                           class="form-input @error('due_date') is-invalid @enderror" aria-describedby="hint-due_date error-due_date" aria-invalid="{{ $errors->has('due_date') ? 'true' : 'false' }}">
                    <p id="hint-due_date" class="field-hint">Default: seven days from today.</p>
                    <p id="error-due_date" class="field-error {{ $errors->has('due_date') ? '' : 'hidden' }}" data-error-for="due_date">@error('due_date'){{ $message }}@enderror</p>
                </div>
            </div>
            <div id="field-remarks" class="mt-3">
                <div class="flex items-center justify-between gap-3"><label for="remarks" class="form-label">Remarks <span class="font-normal text-gray-400">(optional)</span></label><span id="remarks-counter" class="field-hint tabular-nums">{{ mb_strlen(old('remarks', '')) }}/1000</span></div>
                <textarea id="remarks" name="remarks" rows="3" maxlength="1000" class="form-input form-textarea @error('remarks') is-invalid @enderror" placeholder="Add any administrative remarks..." aria-describedby="error-remarks" aria-invalid="{{ $errors->has('remarks') ? 'true' : 'false' }}">{{ old('remarks') }}</textarea>
                <p id="error-remarks" class="field-error {{ $errors->has('remarks') ? '' : 'hidden' }}" data-error-for="remarks">@error('remarks'){{ $message }}@enderror</p>
            </div>
        </section>

        {{-- Footer actions --}}
        <div class="px-4 py-3 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-[11px] leading-5 text-gray-500">The voucher will be generated with <strong>Generated</strong> status. Payment can be recorded and verified by authorized administration staff later.</p>
            <div class="flex items-center justify-end gap-2 shrink-0">
                <a href="{{ route('admin.vouchers.index') }}" class="h-9 px-4 rounded-lg bg-white border hover:bg-gray-100 text-gray-700 text-sm font-medium inline-flex items-center">Cancel</a>
                <button id="generate-voucher-button" type="submit"
                        @disabled($type === 'admission' && !$hasCurrentlyOpenSession)
                        class="h-9 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 text-white text-sm font-semibold inline-flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                    <i data-lucide="file-plus-2" class="w-4 h-4"></i><span id="submit-button-label">Generate Voucher</span>
                </button>
            </div>
        </div>
    </form>
    <p class="text-[11px] text-gray-500 px-1">Please verify the student information, receiving bank account and fee amount before generating the voucher.</p>
</div>
@endsection

@push('styles')
<style>
    .form-label{display:block;margin-bottom:.35rem;font-size:.75rem;line-height:1rem;font-weight:600;color:rgb(55 65 81)}
    .form-label span{color:rgb(239 68 68)}
    .form-input{display:block;width:100%;min-height:2.35rem;border-radius:.5rem;border:1px solid rgb(209 213 219);background:#fff;padding:.45rem .75rem;font-size:.8125rem;line-height:1.25rem;color:rgb(31 41 55);outline:none;transition:border-color .15s,box-shadow .15s}
    input.form-input,select.form-input{height:2.35rem;padding-top:0;padding-bottom:0}
    .form-textarea{height:auto;min-height:5rem;resize:vertical}
    .form-input:focus{border-color:rgb(59 130 246);box-shadow:0 0 0 2px rgb(59 130 246 / 15%)}
    .form-input[readonly]{background:rgb(249 250 251);color:rgb(55 65 81);cursor:not-allowed}
    .form-input:disabled{background:rgb(243 244 246);cursor:not-allowed}
    .form-input.is-invalid{border-color:rgb(220 38 38);background:rgb(254 242 242)}
    .form-input.is-invalid:focus{border-color:rgb(220 38 38);box-shadow:0 0 0 2px rgb(220 38 38 / 12%)}
    .field-error{margin-top:.3rem;font-size:.72rem;line-height:1rem;color:rgb(185 28 28)}
    .field-error.hidden{display:none}
    .field-hint{margin-top:.3rem;font-size:.68rem;line-height:1rem;color:rgb(107 114 128)}
    .preview-label{font-size:.65rem;text-transform:uppercase;letter-spacing:.04em;color:rgb(107 114 128);font-weight:600}
    .preview-value{font-size:.8rem;font-weight:700;color:rgb(17 24 39);margin-top:2px;overflow-wrap:anywhere}
    [hidden]{display:none!important}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) window.lucide.createIcons();

    const form = document.getElementById('voucher-form');
    if (!form) return;

    const voucherType = form.dataset.voucherType || '';
    const clientSummary = document.getElementById('client-validation-summary');
    const clientList = document.getElementById('client-validation-list');
    const submitButton = document.getElementById('generate-voucher-button');
    const submitLabel = document.getElementById('submit-button-label');
    const addressInput = document.getElementById('address');
    const remarksInput = document.getElementById('remarks');
    const amountInput = document.getElementById('amount');
    const courseSelect = document.getElementById('course_id');
    const admissionSelect = document.getElementById('admission_id');
    const sessionSelect = document.getElementById('admission_session_id');
    const preview = document.getElementById('course-preview');
    const previewType = document.getElementById('preview-course-type');
    const previewFee = document.getElementById('preview-fee');
    const previewBank = document.getElementById('preview-bank');
    const previewAccount = document.getElementById('preview-account');
    const bankWarning = document.getElementById('course-bank-warning');
    const imcNotice = document.getElementById('imc-notice');
    const readmissionCourseNote = document.getElementById('readmission-course-note');

    const labels = {
        applicant_name: 'Student name', father_name: 'Father name', cnic: 'CNIC / Form-B',
        phone: 'Contact number', date_of_birth: 'Date of birth', gender: 'Gender',
        email: 'Email address', address: 'Residential address', admission_session_id: 'Admission session',
        course_id: 'Course', admission_id: 'Existing admission', amount: 'Amount',
        issue_date: 'Issue date', due_date: 'Due date', remarks: 'Remarks'
    };

    function field(name) {
        return form.elements.namedItem(name);
    }

    function fieldErrorElement(name) {
        return form.querySelector('[data-error-for="' + name + '"]');
    }

    function setError(name, message) {
        const input = field(name);
        const error = fieldErrorElement(name);
        if (input && input.classList) {
            input.classList.toggle('is-invalid', Boolean(message));
            input.setAttribute('aria-invalid', message ? 'true' : 'false');
        }
        if (error) {
            error.textContent = message || '';
            error.classList.toggle('hidden', !message);
        }
    }

    function digits(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function validatePhone(value) {
        const d = digits(value);
        // Accept local 03xxxxxxxxx, international 923xxxxxxxxx or 00923xxxxxxxxx formats.
        return (d.length === 11 && d.startsWith('03'))
            || (d.length === 12 && d.startsWith('923'))
            || (d.length === 14 && d.startsWith('00923'));
    }

    function selectedCourseOption() {
        if (!courseSelect || !courseSelect.value) return null;
        return courseSelect.options[courseSelect.selectedIndex] || null;
    }

    function selectedAdmissionOption() {
        if (!admissionSelect || !admissionSelect.value) return null;
        return admissionSelect.options[admissionSelect.selectedIndex] || null;
    }

    function validateField(name) {
        const input = field(name);
        if (!input || !('value' in input)) return '';
        const value = String(input.value || '').trim();
        let message = '';

        switch (name) {
            case 'applicant_name':
                if (!value) message = 'Enter the student’s full name.';
                else if (value.length < 3) message = 'Student name must contain at least 3 characters.';
                else if (value.length > 255) message = 'Student name cannot exceed 255 characters.';
                break;
            case 'father_name':
                if (!value) message = 'Enter the student’s full name.';
                else if (value.length > 255) message = 'Father name cannot exceed 255 characters.';
                break;
            case 'cnic': {
                const d = digits(value);
                if (!value) message = 'Enter the student’s CNIC or Form-B number.';
                else if (d.length !== 13) message = 'Enter exactly 13 digits for CNIC / Form-B.';
                break;
            }
            case 'phone':
                if (!value) message = 'Enter a contact number.';
                else if (!validatePhone(value)) message = 'Enter a valid Pakistani mobile number, e.g. 03001234567 or +923001234567.';
                break;
            case 'date_of_birth':
                if (!value) message = 'Enter the student’s date of birth.';
                else if (value && value > new Date().toISOString().slice(0, 10)) message = 'Date of birth cannot be in the future.';
                break;
            case 'gender':
                if (!value) message = 'Select the student’s gender.';
                else if (value && !['male', 'female'].includes(value)) message = 'Select a valid gender.';
                break;
            case 'email':
                if (!value) message = 'Enter the student’s email address.';
                else if (value && (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || value.length > 255)) message = 'Enter a valid email address (maximum 255 characters).';
                break;
            case 'address':
                if (!value) message = 'Enter the student’s address.';
                else if (value.length > 1000) message = 'Address cannot exceed 1,000 characters.';
                break;
            case 'admission_session_id': {
                if (!value) message = 'Select an admission session.';
                else {
                    const option = sessionSelect.options[sessionSelect.selectedIndex];
                    if (!option || option.dataset.currentlyOpen !== '1') message = 'Choose a session that is currently open and within its dates.';
                }
                break;
            }
            case 'course_id': {
                if (!value) message = 'Select a course.';
                else {
                    const option = selectedCourseOption();
                    if (!option) message = 'Select a valid course.';
                    else if (voucherType === 'admission') {
                        const sessionId = sessionSelect ? String(sessionSelect.value || '') : '';
                        const assignedSessionIds = String(option.dataset.sessionIds || '').split(',').filter(Boolean);
                        if (!sessionId) message = 'Select an admission session before selecting a course.';
                        else if (!assignedSessionIds.includes(sessionId)) message = 'This course is not assigned to the selected admission session. Choose a course from the filtered list.';
                        else if (!option.dataset.bankAccountId) message = 'This course has no bank account assigned. Ask an administrator to configure it.';
                        else if (!Number.isFinite(Number(option.dataset.fee)) || Number(option.dataset.fee) <= 0) message = 'The selected course does not have a valid fee amount configured.';
                    } else if (voucherType === 'readmission') {
                        const admission = selectedAdmissionOption();
                        if (admission && admission.dataset.courseId && admission.dataset.courseId !== value) message = 'The course must match the selected existing admission.';
                        if (admission && !admission.dataset.courseId) message = 'The selected admission has no course linked. Please correct the admission record first.';
                    }
                }
                break;
            }
            case 'admission_id':
                if (voucherType === 'readmission' && !value) message = 'Select the existing admission for readmission.';
                break;
            case 'amount': {
                const n = Number(value);
                if (!value || !Number.isFinite(n)) message = 'Enter a valid voucher amount.';
                else if (n <= 0) message = 'Voucher amount must be greater than zero.';
                else if (n > 999999999) message = 'Voucher amount is above the allowed limit.';
                else if (voucherType === 'admission') {
                    const option = selectedCourseOption();
                    const courseFee = option ? Number(option.dataset.fee) : 0;
                    if (!option || !option.value) message = 'Select a course to calculate the admission fee.';
                    else if (Math.abs(n - courseFee) > 0.009) message = 'Admission amount must match the selected course fee.';
                }
                break;
            }
            case 'issue_date':
                if (!value) message = 'Issue date is required.';
                break;
            case 'due_date': {
                const issue = field('issue_date') ? field('issue_date').value : '';
                if (!value) message = 'Due date is required.';
                else if (issue && value < issue) message = 'Due date cannot be earlier than the issue date.';
                break;
            }
            case 'remarks':
                if (value.length > 1000) message = 'Remarks cannot exceed 1,000 characters.';
                break;
        }

        setError(name, message);
        return message;
    }

    const commonFields = ['applicant_name', 'father_name', 'cnic', 'phone', 'date_of_birth', 'gender', 'email', 'address', 'amount', 'issue_date', 'due_date', 'remarks'];
    function activeFields() {
        const names = commonFields.slice();
        if (voucherType === 'admission') names.push('admission_session_id', 'course_id');
        if (voucherType === 'readmission') names.push('admission_id', 'course_id');
        if (voucherType === 'hostel' && admissionSelect) names.push('admission_id');
        return names;
    }

    function updateSummary(errors) {
        if (!clientSummary || !clientList) return;
        clientList.replaceChildren();
        if (!errors.length) {
            clientSummary.classList.add('hidden');
            return;
        }
        errors.forEach(function (entry) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#field-' + entry.name;
            link.className = 'underline underline-offset-2 hover:text-red-950';
            link.textContent = (labels[entry.name] || entry.name) + ': ' + entry.message;
            link.addEventListener('click', function () {
                const input = field(entry.name);
                if (input && typeof input.focus === 'function') input.focus({ preventScroll: true });
            });
            li.appendChild(link);
            clientList.appendChild(li);
        });
        clientSummary.classList.remove('hidden');
    }

    function runValidation() {
        const errors = [];
        activeFields().forEach(function (name) {
            const message = validateField(name);
            if (message) errors.push({ name: name, message: message });
        });
        if (voucherType === 'admission' && form.dataset.admissionAvailable !== '1') {
            errors.push({ name: 'admission_session_id', message: 'Admission voucher generation is unavailable because no session is currently open.' });
        }
        if (voucherType === 'hostel' && form.dataset.hostelAccountAvailable !== '1') {
            errors.push({ name: 'admission_id', message: 'An active Hostel bank account must be configured before a hostel voucher can be generated.' });
        }
        updateSummary(errors);
        return errors;
    }

    // Keep field feedback current after the user has interacted with a field.
    activeFields().forEach(function (name) {
        const input = field(name);
        if (!input || !input.addEventListener) return;
        input.addEventListener('blur', function () {
            input.dataset.touched = '1';
            validateField(name);
        });
        input.addEventListener('input', function () {
            if (input.dataset.touched === '1' || input.classList.contains('is-invalid')) validateField(name);
            if (name === 'address') updateCounter(addressInput, document.getElementById('address-counter'), 1000);
            if (name === 'remarks') updateCounter(remarksInput, document.getElementById('remarks-counter'), 1000);
        });
        input.addEventListener('change', function () {
            input.dataset.touched = '1';
            validateField(name);
        });
    });

    function updateCounter(input, counter, max) {
        if (input && counter) counter.textContent = String(input.value.length) + '/' + max;
    }
    updateCounter(addressInput, document.getElementById('address-counter'), 1000);
    updateCounter(remarksInput, document.getElementById('remarks-counter'), 1000);

    // Format a complete 13-digit CNIC when leaving the field, without moving the cursor while typing.
    const cnicInput = document.getElementById('cnic');
    if (cnicInput) {
        cnicInput.addEventListener('blur', function () {
            const d = digits(cnicInput.value);
            if (d.length === 13) cnicInput.value = d.slice(0, 5) + '-' + d.slice(5, 12) + '-' + d.slice(12);
        });
    }

    function filterAdmissionCourses() {
        if (voucherType !== 'admission' || !sessionSelect || !courseSelect) return 0;

        const selectedSessionId = String(sessionSelect.value || '');
        const options = Array.from(courseSelect.options).filter(function (option) {
            return option.value !== '';
        });
        let availableCount = 0;

        options.forEach(function (option) {
            const assignedSessionIds = String(option.dataset.sessionIds || '')
                .split(',')
                .filter(Boolean);
            const isAvailable = Boolean(selectedSessionId)
                && assignedSessionIds.includes(selectedSessionId);

            option.hidden = !isAvailable;
            option.disabled = !isAvailable;
            if (isAvailable) availableCount += 1;
        });

        const selectedOption = selectedCourseOption();
        if (selectedOption && selectedOption.value && (selectedOption.hidden || selectedOption.disabled)) {
            courseSelect.value = '';
        }

        courseSelect.disabled = !selectedSessionId || availableCount === 0;

        const placeholder = courseSelect.options[0];
        if (placeholder) {
            placeholder.textContent = !selectedSessionId
                ? 'Select an admission session first'
                : availableCount === 0
                    ? 'No courses assigned to this session'
                    : 'Select course';
        }

        const hint = document.getElementById('admission-course-filter-hint');
        if (hint) {
            if (!selectedSessionId) {
                hint.textContent = 'Select an admission session to see its assigned courses only.';
                hint.classList.remove('text-red-600');
                hint.classList.add('text-gray-500');
            } else if (availableCount === 0) {
                hint.textContent = 'No active courses are assigned to this session. Assign courses in Admission Sessions before generating a voucher.';
                hint.classList.remove('text-gray-500');
                hint.classList.add('text-red-600');
            } else {
                hint.textContent = availableCount + ' course' + (availableCount === 1 ? '' : 's') + ' available for the selected session.';
                hint.classList.remove('text-red-600');
                hint.classList.add('text-gray-500');
            }
        }

        return availableCount;
    }

    function updateCoursePreview() {
        if (!courseSelect || !preview) return;
        const option = selectedCourseOption();
        if (!option || !option.value) {
            preview.classList.add('hidden');
            if (imcNotice) imcNotice.classList.add('hidden');
            if (bankWarning) bankWarning.classList.add('hidden');
            if (amountInput && voucherType === 'admission') amountInput.value = '';
            return;
        }
        const fee = Number(option.dataset.fee || 0);
        const courseType = option.dataset.courseType || '';
        const bankTitle = option.dataset.bankTitle || 'Not configured';
        const bankNumber = option.dataset.bankNumber || '';
        preview.classList.remove('hidden');
        if (previewType) previewType.textContent = courseType ? courseType.charAt(0).toUpperCase() + courseType.slice(1) : '—';
        if (previewFee) previewFee.textContent = 'Rs. ' + fee.toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (previewBank) previewBank.textContent = bankTitle;
        if (previewAccount) previewAccount.textContent = bankNumber ? 'A/C ' + bankNumber : 'Bank account not assigned';
        if (amountInput && voucherType === 'admission') amountInput.value = fee > 0 ? fee.toFixed(2) : '';
        if (bankWarning) bankWarning.classList.toggle('hidden', Boolean(option.dataset.bankAccountId));
        if (imcNotice) imcNotice.classList.toggle('hidden', courseType !== 'private');
        validateField('course_id');
        if (amountInput && voucherType === 'admission') validateField('amount');
        if (window.lucide) window.lucide.createIcons();
    }

    function fillStudentFromAdmission(option) {
        if (!option || !option.value) return;
        const map = {
            applicant_name: 'name', father_name: 'fatherName', cnic: 'cnic', phone: 'phone',
            date_of_birth: 'dob', gender: 'gender', email: 'email', address: 'address'
        };
        Object.keys(map).forEach(function (fieldName) {
            const input = field(fieldName);
            const attr = 'data-' + map[fieldName].replace(/[A-Z]/g, function (m) { return '-' + m.toLowerCase(); });
            if (input && option.hasAttribute(attr)) {
                input.value = option.getAttribute(attr) || '';
                input.dataset.touched = '1';
                validateField(fieldName);
            }
        });
        updateCounter(addressInput, document.getElementById('address-counter'), 1000);
    }

    if (voucherType === 'admission' && sessionSelect && courseSelect) {
        sessionSelect.addEventListener('change', function () {
            filterAdmissionCourses();
            updateCoursePreview();
            validateField('admission_session_id');
            validateField('course_id');
        });

        // Apply the selected session to the course options on initial page load,
        // including after the server redirects back with validation errors.
        filterAdmissionCourses();
        updateCoursePreview();
    }

    if (courseSelect) {
        courseSelect.addEventListener('change', function () {
            updateCoursePreview();
            if (voucherType === 'readmission') validateField('course_id');
            if (voucherType === 'admission') validateField('course_id');
        });
        if (voucherType !== 'admission') updateCoursePreview();
    }

    if (admissionSelect) {
        admissionSelect.addEventListener('change', function () {
            const option = selectedAdmissionOption();
            if (option && option.value) {
                fillStudentFromAdmission(option);
                if (voucherType === 'readmission' && courseSelect) {
                    const relatedCourse = option.dataset.courseId || '';
                    if (relatedCourse) {
                        courseSelect.value = relatedCourse;
                        if (readmissionCourseNote) readmissionCourseNote.classList.remove('hidden');
                    } else {
                        courseSelect.value = '';
                        if (readmissionCourseNote) readmissionCourseNote.classList.add('hidden');
                    }
                    validateField('course_id');
                }
            } else if (voucherType === 'readmission' && readmissionCourseNote) {
                readmissionCourseNote.classList.add('hidden');
            }
            validateField('admission_id');
        });
        // On a validation redirect, synchronize the course for the selected old admission.
        if (voucherType === 'readmission' && admissionSelect.value && courseSelect) {
            const option = selectedAdmissionOption();
            if (option && option.dataset.courseId) {
                courseSelect.value = option.dataset.courseId;
                if (readmissionCourseNote) readmissionCourseNote.classList.remove('hidden');
            }
        }
    }

    form.addEventListener('submit', function (event) {
        const errors = runValidation();
        if (errors.length) {
            event.preventDefault();
            const first = errors[0];
            const input = field(first.name);
            const target = input || document.getElementById('client-validation-summary');
            if (target && typeof target.scrollIntoView === 'function') target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (target && typeof target.focus === 'function') window.setTimeout(function () { target.focus({ preventScroll: true }); }, 250);
            return;
        }
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75');
        }
        if (submitLabel) submitLabel.textContent = 'Generating Voucher…';
    });

    // Show inline feedback immediately for fields rejected by Laravel on the previous request.
    form.querySelectorAll('.form-input.is-invalid').forEach(function (input) {
        input.dataset.touched = '1';
    });

    if (window.lucide) window.lucide.createIcons();
});
</script>
@endpush
