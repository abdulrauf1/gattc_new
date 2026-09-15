@extends('layouts.admin')

@section('page-heading', 'Generate Voucher')

@section('content')

<div class="max-w-6xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Generate Voucher
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Create a voucher for a student from the administration office.
            </p>
        </div>

        <a
            href="{{ route('admin.vouchers.index') }}"
            class="inline-flex items-center gap-2
                   px-3 py-2 rounded-lg
                   bg-gray-100 hover:bg-gray-200
                   text-gray-700 text-sm">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            Back

        </a>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="bg-red-50 border border-red-200
                    text-red-700 rounded-xl p-4">

            <div class="font-semibold mb-1">
                Please correct the following:
            </div>

            <ul class="list-disc ml-5 text-sm">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Voucher Type --}}
    <div class="bg-white border rounded-xl p-4">

        <p class="text-sm font-semibold text-gray-700 mb-3">
            Voucher Type
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            {{-- Admission --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'admission']) }}"
                class="border rounded-xl p-4
                       {{ $type === 'admission'
                            ? 'border-blue-500 bg-blue-50'
                            : 'border-gray-200 hover:border-blue-300'
                       }}">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-blue-100 flex items-center justify-center">

                        <i data-lucide="graduation-cap"
                           class="w-5 h-5 text-blue-600">
                        </i>

                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            Admission Voucher
                        </p>

                        <p class="text-xs text-gray-500">
                            Creates a pending admission automatically.
                        </p>
                    </div>

                </div>

            </a>


            {{-- Hostel --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'hostel']) }}"
                class="border rounded-xl p-4
                       {{ $type === 'hostel'
                            ? 'border-purple-500 bg-purple-50'
                            : 'border-gray-200 hover:border-purple-300'
                       }}">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-purple-100 flex items-center justify-center">

                        <i data-lucide="bed-double"
                           class="w-5 h-5 text-purple-600">
                        </i>

                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            Hostel Voucher
                        </p>

                        <p class="text-xs text-gray-500">
                            Hostel fee only. No new admission.
                        </p>
                    </div>

                </div>

            </a>


            {{-- Readmission --}}
            <a
                href="{{ route('admin.vouchers.create', ['type' => 'readmission']) }}"
                class="border rounded-xl p-4
                       {{ $type === 'readmission'
                            ? 'border-amber-500 bg-amber-50'
                            : 'border-gray-200 hover:border-amber-300'
                       }}">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg
                                bg-amber-100 flex items-center justify-center">

                        <i data-lucide="refresh-cw"
                           class="w-5 h-5 text-amber-600">
                        </i>

                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            Readmission Voucher
                        </p>

                        <p class="text-xs text-gray-500">
                            Attach to an existing admission.
                        </p>
                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('admin.vouchers.store') }}"
        class="bg-white border rounded-xl p-5">

        @csrf

        <input
            type="hidden"
            name="voucher_type"
            value="{{ $type }}">


        <div class="space-y-6">

            {{-- Student Information --}}
            <div>

                <h2 class="text-base font-semibold text-gray-900">
                    Student Information
                </h2>

                <p class="text-xs text-gray-500 mt-1">
                    Enter the applicant's information.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3
                            gap-4 mt-4">

                    {{-- Applicant --}}
                    <div>
                        <label class="form-label">
                            Student Name *
                        </label>

                        <input
                            type="text"
                            name="applicant_name"
                            value="{{ old('applicant_name') }}"
                            required
                            class="form-input w-full"
                            placeholder="Student full name">
                    </div>


                    {{-- Father --}}
                    <div>
                        <label class="form-label">
                            Father Name
                        </label>

                        <input
                            type="text"
                            name="father_name"
                            value="{{ old('father_name') }}"
                            class="form-input w-full"
                            placeholder="Father name">
                    </div>


                    {{-- CNIC --}}
                    <div>
                        <label class="form-label">
                            CNIC *
                        </label>

                        <input
                            type="text"
                            name="cnic"
                            value="{{ old('cnic') }}"
                            required
                            class="form-input w-full"
                            placeholder="17301-1234567-1">
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
                            class="form-input w-full">
                    </div>


                    {{-- Gender --}}
                    <div>
                        <label class="form-label">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-input w-full">

                            <option value="">
                                Select Gender
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


                    {{-- Phone --}}
                    <div>
                        <label class="form-label">
                            Phone *
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            class="form-input w-full"
                            placeholder="03XX-XXXXXXX">
                    </div>


                    {{-- Email --}}
                    <div>
                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-input w-full"
                            placeholder="student@example.com">
                    </div>


                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label class="form-label">
                            Address
                        </label>

                        <input
                            type="text"
                            name="address"
                            value="{{ old('address') }}"
                            class="form-input w-full"
                            placeholder="Complete address">
                    </div>

                </div>

            </div>


            {{-- Admission-specific --}}
            @if($type === 'admission')

                <div class="border-t pt-5">

                    <h2 class="text-base font-semibold text-gray-900">
                        Admission Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                        {{-- Session --}}
                        <div>

                            <label class="form-label">
                                Admission Session *
                            </label>

                            <select
                                name="admission_session_id"
                                required
                                class="form-input w-full">

                                <option value="">
                                    Select Session
                                </option>

                                @foreach($sessions as $session)

                                    <option
                                        value="{{ $session->id }}"
                                        @selected(old('admission_session_id') == $session->id)>

                                        {{ $session->title }}

                                        @if($session->is_open)
                                            — Open
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Course --}}
                        <div>

                            <label class="form-label">
                                Course *
                            </label>

                            <select
                                name="course_id"
                                id="course_id"
                                required
                                class="form-input w-full">

                                <option value="">
                                    Select Course
                                </option>

                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        data-fee="{{ $course->fee_amount }}"
                                        data-bank="{{ $course->bankAccount?->account_title }}"
                                        @selected(old('course_id') == $course->id)>

                                        {{ $course->title }}

                                        ({{ ucfirst($course->course_type) }})

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Readmission --}}
            @if($type === 'readmission')

                <div class="border-t pt-5">

                    <h2 class="text-base font-semibold text-gray-900">
                        Existing Admission
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                        {{-- Existing Admission --}}
                        <div>

                            <label class="form-label">
                                Existing Admission *
                            </label>

                            <select
                                name="admission_id"
                                required
                                class="form-input w-full">

                                <option value="">
                                    Select Admission
                                </option>

                                @foreach($admissions as $admission)

                                    <option
                                        value="{{ $admission->id }}"
                                        @selected(old('admission_id') == $admission->id)>

                                        {{ $admission->admission_no }}
                                        -
                                        {{ $admission->student_name }}
                                        -
                                        {{ $admission->course?->title }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Course --}}
                        <div>

                            <label class="form-label">
                                Course *
                            </label>

                            <select
                                name="course_id"
                                required
                                class="form-input w-full">

                                <option value="">
                                    Select Course
                                </option>

                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        @selected(old('course_id') == $course->id)>

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

                <div class="border-t pt-5">

                    <h2 class="text-base font-semibold text-gray-900">
                        Hostel Details
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        This voucher is deposited into the Hostel BOK account.
                        No new admission record will be created.
                    </p>

                    <div class="mt-4">

                        <label class="form-label">
                            Existing Admission
                        </label>

                        <select
                            name="admission_id"
                            class="form-input w-full">

                            <option value="">
                                Standalone Hostel Voucher
                            </option>

                            @foreach($admissions as $admission)

                                <option
                                    value="{{ $admission->id }}"
                                    @selected(old('admission_id') == $admission->id)>

                                    {{ $admission->admission_no }}
                                    -
                                    {{ $admission->student_name }}
                                    -
                                    {{ $admission->course?->title }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    @if($hostelAccount)

                        <div class="mt-4 bg-purple-50 border
                                    border-purple-200 rounded-lg p-3">

                            <p class="text-xs text-purple-600 font-semibold">
                                HOSTEL BANK ACCOUNT
                            </p>

                            <p class="text-sm font-semibold text-gray-900 mt-1">
                                {{ $hostelAccount->account_title }}
                            </p>

                            <p class="text-sm text-gray-600">
                                A/C {{ $hostelAccount->account_number }}
                            </p>

                        </div>

                    @else

                        <div class="mt-4 bg-red-50 border
                                    border-red-200 rounded-lg p-3
                                    text-sm text-red-700">

                            Hostel bank account is not configured.

                        </div>

                    @endif

                </div>

            @endif


            {{-- Financial Information --}}
            <div class="border-t pt-5">

                <h2 class="text-base font-semibold text-gray-900">
                    Voucher Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                    {{-- Amount --}}
                    <div>

                        <label class="form-label">
                            Amount (PKR) *
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="amount"
                            value="{{ old('amount') }}"
                            required
                            class="form-input w-full"
                            placeholder="0">

                        @if($type === 'admission')
                            <p class="text-xs text-gray-400 mt-1">
                                You can enter the course fee shown in the course record.
                            </p>
                        @endif

                    </div>


                    {{-- Issue Date --}}
                    <div>

                        <label class="form-label">
                            Issue Date *
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                            required
                            class="form-input w-full">

                    </div>


                    {{-- Due Date --}}
                    <div>

                        <label class="form-label">
                            Due Date *
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                            required
                            class="form-input w-full">

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="mt-4">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="2"
                        class="form-input w-full"
                        placeholder="Optional notes...">{{ old('remarks') }}</textarea>

                </div>

            </div>


            {{-- Action --}}
            <div class="border-t pt-5 flex justify-end gap-2">

                <a
                    href="{{ route('admin.vouchers.index') }}"
                    class="px-5 py-2.5 rounded-lg
                           bg-gray-100 hover:bg-gray-200
                           text-gray-700 text-sm font-medium">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg
                           bg-emerald-500 hover:bg-emerald-600
                           text-white text-sm font-semibold
                           inline-flex items-center gap-2">

                    <i data-lucide="file-plus"
                       class="w-4 h-4"></i>

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
        @apply block text-sm font-medium text-gray-700 mb-1.5;
    }

    .form-input {
        @apply rounded-lg border border-gray-300
               focus:border-blue-500
               focus:ring-1 focus:ring-blue-500;
    }
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (window.lucide) {
        lucide.createIcons();
    }

    /*
    |--------------------------------------------------------------------------
    | Automatically Fill Course Fee
    |--------------------------------------------------------------------------
    */

    const courseSelect = document.getElementById('course_id');
    const amountInput = document.querySelector('input[name="amount"]');

    if (courseSelect && amountInput) {

        courseSelect.addEventListener('change', function () {

            const selected =
                this.options[this.selectedIndex];

            const fee = selected.dataset.fee;

            if (fee && !amountInput.value) {
                amountInput.value = fee;
            }

        });

        /*
        | Fill immediately when validation redirects back.
        */

        if (courseSelect.value && !amountInput.value) {

            const selected =
                courseSelect.options[
                    courseSelect.selectedIndex
                ];

            if (selected.dataset.fee) {
                amountInput.value =
                    selected.dataset.fee;
            }
        }
    }

});
</script>
@endpush