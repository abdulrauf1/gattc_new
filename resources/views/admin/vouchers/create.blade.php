@extends('layouts.admin')

@section('page-heading', 'Generate Voucher')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Generate Voucher
            </h1>

            <p class="text-xs text-gray-500 mt-0.5">
                Generate an official GATTC fee challan from the administration office.
            </p>
        </div>

        <a
            href="{{ route('admin.vouchers.index') }}"
            class="inline-flex items-center gap-1.5
                   h-9 px-3 rounded-lg
                   bg-gray-100 hover:bg-gray-200
                   text-gray-700 text-sm">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back

        </a>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="rounded-lg border border-red-200
                    bg-red-50 px-4 py-3 text-sm text-red-700">

            <p class="font-semibold mb-1">
                Please correct the following:
            </p>

            <ul class="list-disc ml-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- Voucher Type --}}
    <div class="bg-white border border-gray-200 rounded-xl p-3">

        <div class="flex items-center justify-between mb-3">

            <div>
                <h2 class="text-sm font-semibold text-gray-900">
                    Voucher Type
                </h2>

                <p class="text-[11px] text-gray-500">
                    Select the type of fee challan to generate.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">

            {{-- Admission --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'admission']) }}"
                class="rounded-lg border p-3 transition
                {{ $type === 'admission'
                    ? 'border-blue-500 bg-blue-50'
                    : 'border-gray-200 hover:border-blue-300'
                }}">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-blue-100
                                flex items-center justify-center shrink-0">

                        <i data-lucide="graduation-cap"
                           class="w-4 h-4 text-blue-600">
                        </i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-gray-900">
                            Admission Voucher
                        </p>

                        <p class="text-[11px] text-gray-500">
                            Creates a pending admission record.
                        </p>

                    </div>

                </div>

            </a>


            {{-- Hostel --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'hostel']) }}"
                class="rounded-lg border p-3 transition
                {{ $type === 'hostel'
                    ? 'border-purple-500 bg-purple-50'
                    : 'border-gray-200 hover:border-purple-300'
                }}">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-purple-100
                                flex items-center justify-center shrink-0">

                        <i data-lucide="bed-double"
                           class="w-4 h-4 text-purple-600">
                        </i>

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-gray-900">
                            Hostel Voucher
                        </p>

                        <p class="text-[11px] text-gray-500">
                            Hostel fee only; no admission created.
                        </p>

                    </div>

                </div>

            </a>


            {{-- Readmission --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'readmission']) }}"
                class="rounded-lg border p-3 transition
                {{ $type === 'readmission'
                    ? 'border-amber-500 bg-amber-50'
                    : 'border-gray-200 hover:border-amber-300'
                }}">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-amber-100
                                flex items-center justify-center shrink-0">

                        <i data-lucide="refresh-cw"
                           class="w-4 h-4 text-amber-600">
                        </i>

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-gray-900">
                            Readmission Voucher
                        </p>

                        <p class="text-[11px] text-gray-500">
                            Attach to an existing admission.
                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- Main Form --}}
    <form
        method="POST"
        action="{{ route('admin.vouchers.store') }}"
        class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        @csrf

        <input
            type="hidden"
            name="voucher_type"
            value="{{ $type }}">


        {{-- Student Information --}}
        <div class="p-4 border-b border-gray-200">

            <div class="flex items-center gap-2 mb-3">

                <div class="w-7 h-7 rounded-md bg-blue-50
                            flex items-center justify-center">

                    <i data-lucide="user"
                       class="w-4 h-4 text-blue-600">
                    </i>

                </div>

                <div>

                    <h2 class="text-sm font-semibold text-gray-900">
                        Student Information
                    </h2>

                    <p class="text-[11px] text-gray-500">
                        Enter the student's details exactly as provided on the application.
                    </p>

                </div>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                {{-- Student Name --}}
                <div class="lg:col-span-2">

                    <label class="form-label">
                        Student Name <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="applicant_name"
                        value="{{ old('applicant_name') }}"
                        required
                        autofocus
                        class="form-input"
                        placeholder="Enter student full name">

                </div>


                {{-- Father --}}
                <div class="lg:col-span-2">

                    <label class="form-label">
                        Father Name
                    </label>

                    <input
                        type="text"
                        name="father_name"
                        value="{{ old('father_name') }}"
                        class="form-input"
                        placeholder="Enter father name">

                </div>


                {{-- CNIC --}}
                <div>

                    <label class="form-label">
                        CNIC <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="cnic"
                        value="{{ old('cnic') }}"
                        required
                        class="form-input"
                        placeholder="XXXXX-XXXXXXX-X">

                </div>


                {{-- Phone --}}
                <div>

                    <label class="form-label">
                        Contact No. <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        class="form-input"
                        placeholder="03XX-XXXXXXX">

                </div>


                {{-- DOB --}}
                <div>

                    <label class="form-label">
                        Date of Birth
                    </label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        class="form-input">

                </div>


                {{-- Gender --}}
                <div>

                    <label class="form-label">
                        Gender
                    </label>

                    <select
                        name="gender"
                        class="form-input">

                        <option value="">
                            Select gender
                        </option>

                        <option value="male"
                            @selected(old('gender') === 'male')}>
                            Male
                        </option>

                        <option value="female"
                            @selected(old('gender') === 'female')}>
                            Female
                        </option>

                    </select>

                </div>


                {{-- Email --}}
                <div class="lg:col-span-2">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                        placeholder="student@example.com">

                </div>


                {{-- Address --}}
                <div class="sm:col-span-2 lg:col-span-4">

                    <label class="form-label">
                        Address
                    </label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="form-input"
                        placeholder="Complete residential address">

                </div>

            </div>

        </div>


        {{-- Admission Details --}}
        @if($type === 'admission')

            <div class="p-4 border-b border-gray-200">

                <div class="flex items-center gap-2 mb-3">

                    <div class="w-7 h-7 rounded-md bg-emerald-50
                                flex items-center justify-center">

                        <i data-lucide="graduation-cap"
                           class="w-4 h-4 text-emerald-600">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Admission Details
                        </h2>

                        <p class="text-[11px] text-gray-500">
                            Select the admission session and course.
                        </p>

                    </div>

                </div>


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

                    {{-- Session --}}
                    <div>

                        <label class="form-label">
                            Admission Session <span>*</span>
                        </label>

                        <select
                            name="admission_session_id"
                            required
                            class="form-input">

                            <option value="">
                                Select admission session
                            </option>

                            @foreach($sessions as $session)

                                <option
                                    value="{{ $session->id }}"
                                    @selected(
                                        old('admission_session_id') == $session->id
                                    )>

                                    {{ $session->title }}

                                    @if($session->is_open)
                                        — Open
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Course --}}
                    <div class="lg:col-span-2">

                        <label class="form-label">
                            Course <span>*</span>
                        </label>

                        <select
                            name="course_id"
                            id="course_id"
                            required
                            class="form-input">

                            <option value="">
                                Select course
                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    data-fee="{{ $course->fee_amount ?? 0 }}"
                                    data-course-type="{{ $course->course_type }}"
                                    data-bank-title="{{ $course->bankAccount?->account_title }}"
                                    data-bank-number="{{ $course->bankAccount?->account_number }}"
                                    data-bank-purpose="{{ $course->bankAccount?->purpose }}"
                                    @selected(
                                        old('course_id') == $course->id
                                    )>

                                    {{ $course->title }}
                                    —
                                    {{ ucfirst($course->course_type) }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Course / Bank Preview --}}
                <div
                    id="course-preview"
                    class="hidden mt-3 rounded-lg
                           border border-emerald-200
                           bg-emerald-50 p-3">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <div>
                            <p class="preview-label">
                                Course Type
                            </p>

                            <p
                                id="preview-course-type"
                                class="preview-value">
                            </p>
                        </div>

                        <div>
                            <p class="preview-label">
                                Fee
                            </p>

                            <p
                                id="preview-fee"
                                class="preview-value">
                            </p>
                        </div>

                        <div>

                            <p class="preview-label">
                                Fee Deposited Into
                            </p>

                            <p
                                id="preview-bank"
                                class="preview-value">
                            </p>

                            <p
                                id="preview-account"
                                class="text-[11px] text-gray-500 mt-0.5">
                            </p>

                        </div>

                    </div>

                    {{-- IMC Notice --}}
                    <div
                        id="imc-notice"
                        class="hidden mt-3 rounded-md
                               bg-indigo-50 border
                               border-indigo-200
                               px-3 py-2">

                        <div class="flex gap-2">

                            <i data-lucide="building-2"
                               class="w-4 h-4 text-indigo-600 mt-0.5">
                            </i>

                            <p class="text-xs text-indigo-800">

                                This is a <strong>Private / IMC</strong>
                                course. The voucher will use the bank
                                account assigned to this course for
                                Private / IMC fees.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- Readmission --}}
        @if($type === 'readmission')

            <div class="p-4 border-b border-gray-200">

                <div class="flex items-center gap-2 mb-3">

                    <div class="w-7 h-7 rounded-md bg-amber-50
                                flex items-center justify-center">

                        <i data-lucide="refresh-cw"
                           class="w-4 h-4 text-amber-600">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Existing Admission
                        </h2>

                    </div>

                </div>


                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

                    <div>

                        <label class="form-label">
                            Existing Admission <span>*</span>
                        </label>

                        <select
                            name="admission_id"
                            required
                            class="form-input">

                            <option value="">
                                Select admission
                            </option>

                            @foreach($admissions as $admission)

                                <option
                                    value="{{ $admission->id }}"
                                    @selected(
                                        old('admission_id') == $admission->id
                                    )>

                                    {{ $admission->admission_no }}
                                    —
                                    {{ $admission->student_name }}
                                    —
                                    {{ $admission->course?->title }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label class="form-label">
                            Course <span>*</span>
                        </label>

                        <select
                            name="course_id"
                            required
                            class="form-input">

                            <option value="">
                                Select course
                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    @selected(
                                        old('course_id') == $course->id
                                    )>

                                    {{ $course->title }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        @endif


        {{-- Hostel --}}
        @if($type === 'hostel')

            <div class="p-4 border-b border-gray-200">

                <div class="flex items-center gap-2 mb-3">

                    <div class="w-7 h-7 rounded-md bg-purple-50
                                flex items-center justify-center">

                        <i data-lucide="bed-double"
                           class="w-4 h-4 text-purple-600">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Hostel Voucher
                        </h2>

                        <p class="text-[11px] text-gray-500">
                            Hostel vouchers do not create a new admission.
                        </p>

                    </div>

                </div>


                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

                    <div>

                        <label class="form-label">
                            Existing Admission
                        </label>

                        <select
                            name="admission_id"
                            class="form-input">

                            <option value="">
                                Standalone hostel voucher
                            </option>

                            @foreach($admissions as $admission)

                                <option
                                    value="{{ $admission->id }}"
                                    @selected(
                                        old('admission_id') == $admission->id
                                    )>

                                    {{ $admission->admission_no }}
                                    —
                                    {{ $admission->student_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        @if($hostelAccount)

                            <label class="form-label">
                                Receiving Bank Account
                            </label>

                            <div class="h-9 px-3 rounded-lg
                                        bg-purple-50 border
                                        border-purple-200
                                        flex items-center justify-between">

                                <span class="text-xs font-medium">
                                    {{ $hostelAccount->account_title }}
                                </span>

                                <span class="text-xs text-gray-600">
                                    {{ $hostelAccount->account_number }}
                                </span>

                            </div>

                        @else

                            <div class="rounded-lg bg-red-50
                                        border border-red-200
                                        p-3 text-xs text-red-700">

                                Hostel bank account is not configured.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @endif


        {{-- Voucher Information --}}
        <div class="p-4 border-b border-gray-200">

            <div class="flex items-center gap-2 mb-3">

                <div class="w-7 h-7 rounded-md bg-gray-100
                            flex items-center justify-center">

                    <i data-lucide="receipt"
                       class="w-4 h-4 text-gray-600">
                    </i>

                </div>

                <div>

                    <h2 class="text-sm font-semibold text-gray-900">
                        Voucher Information
                    </h2>

                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <div>

                    <label class="form-label">
                        Amount (PKR) <span>*</span>
                    </label>

                    <div class="relative">

                        <span
                            class="absolute left-3 top-1/2
                                   -translate-y-1/2
                                   text-xs text-gray-400">
                            Rs.
                        </span>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            required
                            readonly
                            class="form-input pl-9 font-semibold"
                            placeholder="0.00">

                    </div>

                </div>


                <div>

                    <label class="form-label">
                        Issue Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="issue_date"
                        value="{{ old(
                            'issue_date',
                            now()->format('Y-m-d')
                        ) }}"
                        required
                        readonly
                        class="form-input">

                </div>


                <div>

                    <label class="form-label">
                        Due Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        value="{{ old(
                            'due_date',
                            now()->addDays(7)->format('Y-m-d')
                        ) }}"
                        required
                        readonly
                        class="form-input">

                </div>

            </div>


            <div class="mt-3">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="2"
                    class="form-input resize-none"
                    placeholder="Optional administrative remarks...">{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- Footer Action --}}
        <div class="px-4 py-3 bg-gray-50
                    flex items-center justify-between">

            <p class="text-[11px] text-gray-500">
                The voucher will be generated as <strong>Generated</strong>.
                Payment can be verified later by administration.
            </p>

            <div class="flex items-center gap-2">

                <a
                    href="{{ route('admin.vouchers.index') }}"
                    class="h-9 px-4 rounded-lg
                           bg-white border
                           hover:bg-gray-100
                           text-gray-700
                           text-sm font-medium
                           inline-flex items-center">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="h-9 px-4 rounded-lg
                           bg-emerald-500
                           hover:bg-emerald-600
                           text-white
                           text-sm font-semibold
                           inline-flex items-center gap-2">

                    <i data-lucide="file-plus"
                       class="w-4 h-4">
                    </i>

                    Generate Voucher

                </button>

            </div>

        </div>

    </form>

</div>

@endsection


@push('styles')
<style>

    .form-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 600;
        color: rgb(55 65 81);
    }

    .form-label span {
        color: rgb(239 68 68);
    }

    .form-input {
        display: block;
        width: 100%;
        height: 2.25rem;
        border-radius: 0.5rem;
        border: 1px solid rgb(209 213 219);
        background: white;
        padding: 0 0.75rem;
        font-size: 0.8125rem;
        color: rgb(31 41 55);
        outline: none;
    }

    .form-input:focus {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 1px rgb(59 130 246);
    }

    .preview-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: rgb(107 114 128);
        font-weight: 600;
    }

    .preview-value {
        font-size: 0.8rem;
        font-weight: 700;
        color: rgb(17 24 39);
        margin-top: 2px;
    }

</style>
@endpush


@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    if (window.lucide) {
        lucide.createIcons();
    }

    const courseSelect =
        document.getElementById('course_id');

    const amountInput =
        document.getElementById('amount');

    const preview =
        document.getElementById('course-preview');

    const previewType =
        document.getElementById('preview-course-type');

    const previewFee =
        document.getElementById('preview-fee');

    const previewBank =
        document.getElementById('preview-bank');

    const previewAccount =
        document.getElementById('preview-account');

    const imcNotice =
        document.getElementById('imc-notice');


    function updateCoursePreview() {

        if (!courseSelect || !preview) {
            return;
        }

        const option =
            courseSelect.options[
                courseSelect.selectedIndex
            ];

        if (!option || !option.value) {

            preview.classList.add('hidden');

            if (imcNotice) {
                imcNotice.classList.add('hidden');
            }

            return;
        }

        const fee =
            option.dataset.fee || '0';

        const courseType =
            option.dataset.courseType || '';

        const bankTitle =
            option.dataset.bankTitle || 'Not configured';

        const bankNumber =
            option.dataset.bankNumber || '';

        preview.classList.remove('hidden');

        previewType.textContent =
            courseType
                ? courseType.charAt(0).toUpperCase() +
                  courseType.slice(1)
                : '—';

        previewFee.textContent =
            'Rs. ' +
            Number(fee).toLocaleString(
                'en-PK',
                {
                    minimumFractionDigits: 0
                }
            );

        previewBank.textContent =
            bankTitle;

        previewAccount.textContent =
            bankNumber
                ? 'A/C ' + bankNumber
                : 'Bank account not assigned';


        /*
        |--------------------------------------------------------------------------
        | Automatically fill fee
        |--------------------------------------------------------------------------
        */

        if (amountInput) {
            amountInput.value = fee;
        }


        /*
        |--------------------------------------------------------------------------
        | IMC
        |--------------------------------------------------------------------------
        */

        if (imcNotice) {

            if (courseType === 'private') {
                imcNotice.classList.remove('hidden');
            } else {
                imcNotice.classList.add('hidden');
            }
        }


        if (window.lucide) {
            lucide.createIcons();
        }
    }


    if (courseSelect) {

        courseSelect.addEventListener(
            'change',
            updateCoursePreview
        );

        updateCoursePreview();
    }

});

</script>
@endpush