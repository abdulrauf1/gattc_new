@extends('layouts.admin')

@section('page-heading', 'Voucher Details')

@section('content')

<div class="max-w-5xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Voucher {{ $voucher->voucher_no }}
            </h1>

            <p class="text-sm text-gray-500">
                Voucher details and payment information.
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('admin.vouchers.index') }}"
                class="px-3 py-2 rounded-lg bg-gray-100
                       hover:bg-gray-200 text-sm">

                Back

            </a>

            <button
                onclick="window.print()"
                class="px-3 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-700
                       text-white text-sm">

                <i data-lucide="printer"
                   class="w-4 h-4 inline-block"></i>

                Print

            </button>

        </div>

    </div>


    {{-- Main Voucher --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        {{-- Voucher Header --}}
        <div class="p-5 border-b">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-lg font-bold">
                        GOVERNMENT ADVANCE TECHNICAL
                        TRAINING CENTRE
                    </p>

                    <p class="text-sm text-gray-500">
                        Hayatabad, Peshawar
                    </p>

                </div>

                <div class="text-right">

                    <p class="text-xs uppercase text-gray-500">
                        Voucher No.
                    </p>

                    <p class="text-xl font-bold">
                        {{ $voucher->voucher_no }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Type --}}
        <div class="p-5 border-b
                    grid grid-cols-1 md:grid-cols-4 gap-4">

            <div>
                <p class="text-xs text-gray-500">
                    Voucher Type
                </p>

                <p class="font-semibold">
                    {{ $voucher->voucher_type_label }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Issue Date
                </p>

                <p class="font-semibold">
                    {{ optional($voucher->issue_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Due Date
                </p>

                <p class="font-semibold">
                    {{ optional($voucher->due_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Status
                </p>

                <span class="inline-flex mt-1 px-2.5 py-1
                             rounded-full text-xs font-semibold
                             @if($voucher->status === 'paid')
                                 bg-emerald-50 text-emerald-700
                             @elseif($voucher->status === 'cancelled')
                                 bg-red-50 text-red-700
                             @else
                                 bg-blue-50 text-blue-700
                             @endif">

                    {{ ucfirst($voucher->status) }}

                </span>
            </div>

        </div>


        {{-- Applicant --}}
        <div class="p-5 border-b">

            <h2 class="font-semibold text-gray-900 mb-4">
                Applicant Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Student Name
                    </p>

                    <p class="font-medium">
                        {{ $voucher->applicant_name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Father Name
                    </p>

                    <p>
                        {{ $voucher->father_name ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        CNIC
                    </p>

                    <p>
                        {{ $voucher->cnic }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Phone
                    </p>

                    <p>
                        {{ $voucher->phone }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Email
                    </p>

                    <p>
                        {{ $voucher->email ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Gender
                    </p>

                    <p>
                        {{ $voucher->gender ?: '—' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Course / Admission --}}
        <div class="p-5 border-b">

            <h2 class="font-semibold text-gray-900 mb-4">
                Academic Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Course
                    </p>

                    <p class="font-medium">
                        {{ $voucher->course?->title ?: 'Hostel' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Session
                    </p>

                    <p>
                        {{ $voucher->session?->title ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Admission No.
                    </p>

                    <p class="font-medium">
                        {{ $voucher->admission?->admission_no ?: '—' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Bank --}}
        <div class="p-5 border-b">

            <h2 class="font-semibold text-gray-900 mb-4">
                Bank of Khyber Account
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Account Title
                    </p>

                    <p class="font-medium">
                        {{ $voucher->account_title }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Account Number
                    </p>

                    <p class="font-medium">
                        {{ $voucher->account_number }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        IBAN
                    </p>

                    <p class="font-medium">
                        {{ $voucher->iban ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Branch
                    </p>

                    <p>
                        {{ $voucher->branch_name ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Branch Code
                    </p>

                    <p>
                        {{ $voucher->branch_code ?: '—' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Amount --}}
        <div class="p-5 bg-gray-50">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Amount Payable
                    </p>

                    <p class="text-3xl font-bold text-gray-900">
                        Rs. {{ number_format($voucher->amount, 2) }}
                    </p>
                </div>

                @if($voucher->admission)

                    <div class="text-right">

                        <p class="text-xs text-gray-500">
                            Admission Status
                        </p>

                        <span class="inline-flex mt-1 px-3 py-1
                                     rounded-full text-xs font-semibold
                                     @if($voucher->admission->status === 'approved')
                                         bg-emerald-50 text-emerald-700
                                     @elseif($voucher->admission->status === 'rejected')
                                         bg-red-50 text-red-700
                                     @else
                                         bg-amber-50 text-amber-700
                                     @endif">

                            {{ ucfirst($voucher->admission->status) }}

                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- Remarks --}}
        @if($voucher->remarks)

            <div class="p-5 border-t">

                <p class="text-xs text-gray-500">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@endpush