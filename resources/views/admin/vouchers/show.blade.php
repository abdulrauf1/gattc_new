@extends('layouts.admin')

@section('page-heading', 'Voucher Details')

@section('content')

<div class="max-w-6xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between gap-3">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-xl font-bold text-gray-900">
                    {{ $voucher->voucher_no }}
                </h1>

                @php
                    $statusClasses = match ($voucher->status) {
                        'paid' =>
                            'bg-emerald-50 text-emerald-700',
                        'cancelled' =>
                            'bg-red-50 text-red-700',
                        default =>
                            'bg-blue-50 text-blue-700',
                    };
                @endphp

                <span
                    class="px-2.5 py-1 rounded-full
                           text-xs font-semibold {{ $statusClasses }}">

                    {{ ucfirst($voucher->status) }}

                </span>

            </div>

            <p class="text-xs text-gray-500 mt-0.5">
                {{ $voucher->voucher_type_label }}
                voucher
            </p>

        </div>


        <div class="flex items-center gap-2">

            <a
                href="{{ route('admin.vouchers.index') }}"
                class="h-9 px-3 rounded-lg
                       bg-gray-100 hover:bg-gray-200
                       text-gray-700 text-sm
                       inline-flex items-center gap-1.5">

                <i data-lucide="arrow-left"
                   class="w-4 h-4">
                </i>

                Back

            </a>


            <a
                href="{{ route('admin.vouchers.print', $voucher) }}"
                target="_blank"
                class="h-9 px-3 rounded-lg
                       bg-emerald-500 hover:bg-emerald-600
                       text-white text-sm font-semibold
                       inline-flex items-center gap-1.5">

                <i data-lucide="printer"
                   class="w-4 h-4">
                </i>

                Print Challan

            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        <div class="bg-white border rounded-xl p-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Amount
            </p>

            <p class="text-lg font-bold mt-1">
                Rs. {{ number_format($voucher->amount, 0) }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Student
            </p>

            <p class="text-sm font-semibold mt-1 truncate">
                {{ $voucher->applicant_name }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Course
            </p>

            <p class="text-sm font-semibold mt-1 truncate">
                {{ $voucher->course?->title ?? 'Hostel' }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Due Date
            </p>

            <p class="text-sm font-semibold mt-1">
                {{ optional($voucher->due_date)->format('d M Y') }}
            </p>
        </div>

    </div>


    {{-- Applicant --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="px-4 py-3 border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Applicant Information
            </h2>

        </div>

        <div class="p-4 grid grid-cols-1
                    sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <p class="detail-label">Student Name</p>
                <p class="detail-value">
                    {{ $voucher->applicant_name }}
                </p>
            </div>

            <div>
                <p class="detail-label">Father Name</p>
                <p class="detail-value">
                    {{ $voucher->father_name ?: '—' }}
                </p>
            </div>

            <div>
                <p class="detail-label">CNIC</p>
                <p class="detail-value">
                    {{ $voucher->cnic }}
                </p>
            </div>

            <div>
                <p class="detail-label">Contact</p>
                <p class="detail-value">
                    {{ $voucher->phone }}
                </p>
            </div>

            <div>
                <p class="detail-label">Date of Birth</p>
                <p class="detail-value">
                    {{ optional($voucher->date_of_birth)->format('d M Y') ?: '—' }}
                </p>
            </div>

            <div>
                <p class="detail-label">Gender</p>
                <p class="detail-value">
                    {{ $voucher->gender ?: '—' }}
                </p>
            </div>

            <div class="lg:col-span-2">
                <p class="detail-label">Address</p>
                <p class="detail-value">
                    {{ $voucher->address ?: '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Academic --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="px-4 py-3 border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Academic Information
            </h2>

        </div>

        <div class="p-4 grid grid-cols-1
                    sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <p class="detail-label">Voucher Type</p>
                <p class="detail-value">
                    {{ $voucher->voucher_type_label }}
                </p>
            </div>

            <div>
                <p class="detail-label">Course</p>
                <p class="detail-value">
                    {{ $voucher->course?->title ?: 'Hostel' }}
                </p>
            </div>

            <div>
                <p class="detail-label">Course Type</p>
                <p class="detail-value">
                    {{ $voucher->course
                        ? ucfirst($voucher->course->course_type)
                        : '—'
                    }}
                </p>
            </div>

            <div>
                <p class="detail-label">Session</p>
                <p class="detail-value">
                    {{ $voucher->session?->title ?: '—' }}
                </p>
            </div>

            @if($voucher->admission)

                <div>
                    <p class="detail-label">
                        Admission No.
                    </p>

                    <p class="detail-value font-semibold">
                        {{ $voucher->admission->admission_no }}
                    </p>
                </div>

                <div>
                    <p class="detail-label">
                        Admission Status
                    </p>

                    <p class="detail-value">

                        @php
                            $admissionStatus =
                                match ($voucher->admission->status) {
                                    'approved' =>
                                        'text-emerald-600',
                                    'rejected' =>
                                        'text-red-600',
                                    default =>
                                        'text-amber-600',
                                };
                        @endphp

                        <span class="{{ $admissionStatus }}">
                            {{ ucfirst($voucher->admission->status) }}
                        </span>

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Bank --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="px-4 py-3 border-b bg-gray-50">

            <div class="flex items-center justify-between">

                <h2 class="text-sm font-semibold">
                    Receiving Bank Account
                </h2>

                @if(
                    $voucher->course &&
                    $voucher->course->course_type === 'private'
                )

                    <span
                        class="px-2 py-1 rounded-full
                               bg-indigo-50 text-indigo-700
                               text-[11px] font-semibold">

                        PRIVATE / IMC

                    </span>

                @endif

            </div>

        </div>

        <div class="p-4 grid grid-cols-1
                    sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <p class="detail-label">
                    Bank
                </p>

                <p class="detail-value">
                    {{ $voucher->bank_name }}
                </p>
            </div>

            <div class="lg:col-span-2">
                <p class="detail-label">
                    Account Title
                </p>

                <p class="detail-value">
                    {{ $voucher->account_title }}
                </p>
            </div>

            <div>
                <p class="detail-label">
                    Account Number
                </p>

                <p class="detail-value">
                    {{ $voucher->account_number }}
                </p>
            </div>

            <div class="lg:col-span-2">
                <p class="detail-label">
                    IBAN
                </p>

                <p class="detail-value">
                    {{ $voucher->iban ?: '—' }}
                </p>
            </div>

            <div>
                <p class="detail-label">
                    Branch
                </p>

                <p class="detail-value">
                    {{ $voucher->branch_name ?: '—' }}
                </p>
            </div>

            <div>
                <p class="detail-label">
                    Branch Code
                </p>

                <p class="detail-value">
                    {{ $voucher->branch_code ?: '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Dates / Remarks --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="p-4 grid grid-cols-1
                    md:grid-cols-3 gap-4">

            <div>
                <p class="detail-label">
                    Issue Date
                </p>

                <p class="detail-value">
                    {{ optional($voucher->issue_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="detail-label">
                    Due Date
                </p>

                <p class="detail-value">
                    {{ optional($voucher->due_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="detail-label">
                    Amount
                </p>

                <p class="text-xl font-bold text-gray-900">
                    Rs. {{ number_format($voucher->amount, 2) }}
                </p>
            </div>

        </div>


        @if($voucher->remarks)

            <div class="px-4 py-3
                        border-t bg-gray-50">

                <p class="detail-label">
                    Remarks
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    {{ $voucher->remarks }}
                </p>

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')
<style>

    .detail-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: rgb(107 114 128);
        font-weight: 600;
    }

    .detail-value {
        margin-top: 0.2rem;
        font-size: 0.8125rem;
        color: rgb(31 41 55);
    }

</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (window.lucide) {
        lucide.createIcons();
    }

});
</script>
@endpush