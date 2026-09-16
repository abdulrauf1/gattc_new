@extends('layouts.admin')

@section('page-heading', 'Payment Verification')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    @php
        $payment = $voucher->payment;
        $admission = $voucher->admission;

        $isHostel =
            $voucher->voucher_type === 'hostel';

        $isReadmission =
            $voucher->voucher_type === 'readmission';

        $isAdmission =
            $voucher->voucher_type === 'admission';
    @endphp


    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-xl font-bold text-gray-900">
                    Payment Verification
                </h1>

                @if(!$payment)

                    <span class="status-gray">
                        Not Submitted
                    </span>

                @elseif($payment->status === 'pending')

                    <span class="status-amber">
                        Pending Verification
                    </span>

                @elseif($payment->status === 'approved')

                    <span class="status-green">
                        Approved
                    </span>

                @else

                    <span class="status-red">
                        Rejected
                    </span>

                @endif

            </div>

            <p class="text-xs text-gray-500 mt-1">

                Voucher:
                <strong>
                    {{ $voucher->voucher_no }}
                </strong>

                —
                {{ $voucher->voucher_type_label }}

            </p>

        </div>


        <div class="flex items-center gap-2">

            <a
                href="{{ route(
                    'admin.fee-payments.index'
                ) }}"
                class="h-9 px-3 rounded-lg
                       bg-gray-100
                       hover:bg-gray-200
                       text-gray-700
                       text-sm
                       inline-flex
                       items-center gap-1.5">

                <i data-lucide="arrow-left"
                   class="w-4 h-4">
                </i>

                Back

            </a>


            <a
                href="{{ route(
                    'admin.vouchers.print',
                    $voucher
                ) }}"
                target="_blank"
                class="h-9 px-3 rounded-lg
                       bg-blue-600
                       hover:bg-blue-700
                       text-white
                       text-sm
                       font-semibold
                       inline-flex
                       items-center gap-1.5">

                <i data-lucide="printer"
                   class="w-4 h-4">
                </i>

                Print Voucher

            </a>

        </div>

    </div>


    {{-- Voucher Summary --}}
    <div class="grid grid-cols-2
                md:grid-cols-4 gap-3">

        <div class="bg-white border rounded-xl p-3">

            <p class="label">
                Student
            </p>

            <p class="value">
                {{ $voucher->applicant_name }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-3">

            <p class="label">
                Type
            </p>

            <p class="value">
                {{ $voucher->voucher_type_label }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-3">

            <p class="label">
                Course
            </p>

            <p class="value">
                {{ $voucher->course?->title
                    ?: (
                        $isHostel
                            ? 'Hostel Fee'
                            : '—'
                    )
                }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-3">

            <p class="label">
                Voucher Amount
            </p>

            <p class="text-lg font-bold text-gray-900">
                Rs.
                {{ number_format(
                    $voucher->amount,
                    2
                ) }}
            </p>

        </div>

    </div>


    {{-- Main --}}
    <div class="grid grid-cols-1
                xl:grid-cols-12 gap-4">


        {{-- LEFT --}}
        <div class="xl:col-span-8 space-y-4">


            {{-- Student --}}
            <div class="bg-white border
                        rounded-xl overflow-hidden">

                <div class="px-4 py-3
                            border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Student & Voucher Information
                    </h2>

                </div>


                <div class="p-4 grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-4 gap-4">

                    <div>
                        <p class="label">
                            Student Name
                        </p>

                        <p class="value">
                            {{ $voucher->applicant_name }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Father Name
                        </p>

                        <p class="value">
                            {{ $voucher->father_name ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            CNIC
                        </p>

                        <p class="value">
                            {{ $voucher->cnic }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Contact
                        </p>

                        <p class="value">
                            {{ $voucher->phone }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Issue Date
                        </p>

                        <p class="value">
                            {{ optional(
                                $voucher->issue_date
                            )->format('d M Y') }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Due Date
                        </p>

                        <p class="value">
                            {{ optional(
                                $voucher->due_date
                            )->format('d M Y') }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Session
                        </p>

                        <p class="value">
                            {{ $voucher->session?->title ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="label">
                            Admission No.
                        </p>

                        <p class="value font-semibold">
                            {{ $admission?->admission_no ?: 'Pending' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Payment details --}}
            <div class="bg-white border
                        rounded-xl overflow-hidden">

                <div class="px-4 py-3
                            border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Deposited Payment
                    </h2>

                    <p class="text-[11px] text-gray-500 mt-0.5">
                        Enter the actual bank deposit information submitted by the student.
                    </p>

                </div>


                @if($payment)

                    <div class="p-4 grid
                                grid-cols-1
                                sm:grid-cols-2
                                lg:grid-cols-4 gap-4">

                        <div>
                            <p class="label">
                                Deposit Slip No.
                            </p>

                            <p class="value">
                                {{ $payment->deposit_slip_no ?: '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="label">
                                Transaction No.
                            </p>

                            <p class="value">
                                {{ $payment->bank_transaction_no ?: '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="label">
                                Payment Date
                            </p>

                            <p class="value">
                                {{ optional(
                                    $payment->payment_date
                                )->format('d M Y') ?: '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="label">
                                Amount Deposited
                            </p>

                            <p class="text-lg font-bold">
                                Rs.
                                {{ number_format(
                                    $payment->amount,
                                    2
                                ) }}
                            </p>
                        </div>

                    </div>

                @endif


                {{-- Payment Entry Form --}}
                @if(
                    !$payment ||
                    $payment->status !== 'approved'
                )

                    <div class="border-t
                                bg-gray-50 p-4">

                        <form
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{ route(
                                'admin.fee-payments.update-details',
                                $voucher
                            ) }}">

                            @csrf
                            @method('PATCH')


                            <div class="grid
                                        grid-cols-1
                                        md:grid-cols-2
                                        lg:grid-cols-4 gap-3">


                                {{-- Slip --}}
                                <div>

                                    <label class="form-label">
                                        Deposit Slip No. *
                                    </label>

                                    <input
                                        type="text"
                                        name="deposit_slip_no"
                                        required
                                        value="{{ old(
                                            'deposit_slip_no',
                                            $payment?->deposit_slip_no
                                        ) }}"
                                        class="form-input"
                                        placeholder="Bank slip number">

                                </div>


                                {{-- Transaction --}}
                                <div>

                                    <label class="form-label">
                                        Bank Transaction No.
                                    </label>

                                    <input
                                        type="text"
                                        name="bank_transaction_no"
                                        value="{{ old(
                                            'bank_transaction_no',
                                            $payment?->bank_transaction_no
                                        ) }}"
                                        class="form-input"
                                        placeholder="Reference / transaction no.">

                                </div>


                                {{-- Payment Date --}}
                                <div>

                                    <label class="form-label">
                                        Payment Date *
                                    </label>

                                    <input
                                        type="date"
                                        name="payment_date"
                                        required
                                        value="{{ old(
                                            'payment_date',
                                            optional(
                                                $payment?->payment_date
                                            )->format('Y-m-d')
                                        ) }}"
                                        class="form-input">

                                </div>


                                {{-- Amount --}}
                                <div>

                                    <label class="form-label">
                                        Amount Deposited *
                                    </label>

                                    <input
                                        type="number"
                                        name="amount"
                                        step="0.01"
                                        min="0.01"
                                        required
                                        value="{{ old(
                                            'amount',
                                            $payment?->amount
                                            ?? $voucher->amount
                                        ) }}"
                                        class="form-input
                                               font-semibold">

                                    <p class="text-[10px]
                                              text-gray-400 mt-1">
                                        Voucher:
                                        Rs.
                                        {{ number_format(
                                            $voucher->amount,
                                            2
                                        ) }}
                                    </p>

                                </div>


                                {{-- Payment Method --}}
                                <div>

                                    <label class="form-label">
                                        Payment Method
                                    </label>

                                    <select
                                        name="payment_method"
                                        class="form-input">

                                        <option value="bank">
                                            Bank Deposit
                                        </option>

                                        <option value="cash"
                                            @selected(
                                                old(
                                                    'payment_method',
                                                    $payment?->payment_method
                                                ) === 'cash'
                                            )>
                                            Cash
                                        </option>

                                    </select>

                                </div>


                                {{-- Slip Upload --}}
                                <div class="md:col-span-2">

                                    <label class="form-label">
                                        Paid Bank Slip
                                        @if(!$payment)
                                            *
                                        @endif
                                    </label>

                                    <input
                                        type="file"
                                        name="payment_slip"
                                        accept=".jpg,.jpeg,.png,.pdf,.webp"
                                        class="block w-full text-xs
                                               border border-gray-300
                                               rounded-lg
                                               bg-white p-2">

                                    <p class="text-[10px]
                                              text-gray-500 mt-1">

                                        JPG, PNG, WEBP or PDF —
                                        maximum 5 MB.

                                    </p>

                                </div>


                                {{-- Remarks --}}
                                <div class="lg:col-span-2">

                                    <label class="form-label">
                                        Remarks
                                    </label>

                                    <input
                                        type="text"
                                        name="remarks"
                                        value="{{ old(
                                            'remarks'
                                        ) }}"
                                        class="form-input"
                                        placeholder="Payment entry / correction remarks">

                                </div>

                            </div>


                            <div class="flex justify-end mt-4">

                                <button
                                    type="submit"
                                    class="h-9 px-4 rounded-lg
                                           bg-blue-600
                                           hover:bg-blue-700
                                           text-white text-sm
                                           font-semibold
                                           inline-flex
                                           items-center gap-2">

                                    <i data-lucide="save"
                                       class="w-4 h-4">
                                    </i>

                                    Save Payment Details

                                </button>

                            </div>

                        </form>

                    </div>

                @endif


                {{-- Uploaded slip --}}
                <div class="px-4 pb-4">

                    <div class="border rounded-lg
                                bg-gray-50 p-3
                                flex items-center
                                justify-between">

                        <div>

                            <p class="text-xs font-semibold">
                                Paid Bank Slip
                            </p>

                            <p class="text-[11px]
                                      text-gray-500">
                                Compare this against the original physical slip.
                            </p>

                        </div>


                        @if($payment?->payment_slip)

                            <a
                                href="{{ route(
                                    'admin.fee-payments.slip',
                                    $payment
                                ) }}"
                                target="_blank"
                                class="h-8 px-3 rounded-lg
                                       bg-blue-600
                                       hover:bg-blue-700
                                       text-white text-xs
                                       font-semibold
                                       inline-flex
                                       items-center gap-1.5">

                                <i data-lucide="external-link"
                                   class="w-3.5 h-3.5">
                                </i>

                                View Slip

                            </a>

                        @else

                            <span class="text-xs
                                         font-medium
                                         text-red-600">
                                No slip uploaded
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Bank account --}}
            <div class="bg-white border
                        rounded-xl overflow-hidden">

                <div class="px-4 py-3
                            border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Expected Receiving Account
                    </h2>

                </div>


                <div class="p-4 grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-4 gap-4">

                    <div>
                        <p class="label">Bank</p>

                        <p class="value">
                            {{ $voucher->bank_name }}
                        </p>
                    </div>


                    <div class="lg:col-span-2">

                        <p class="label">
                            Account Title
                        </p>

                        <p class="value">
                            {{ $voucher->account_title }}
                        </p>

                    </div>


                    <div>

                        <p class="label">
                            Account Number
                        </p>

                        <p class="value">
                            {{ $voucher->account_number }}
                        </p>

                    </div>


                    <div class="lg:col-span-2">

                        <p class="label">
                            IBAN
                        </p>

                        <p class="value">
                            {{ $voucher->iban ?: '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="xl:col-span-4 space-y-4">


            {{-- Verify payment --}}
            @if(
                $payment &&
                $payment->status !== 'approved'
            )

                <div class="bg-white border
                            rounded-xl p-4">

                    <h2 class="text-sm font-semibold">
                        Physical Verification
                    </h2>

                    <p class="text-xs
                              text-gray-500 mt-1">

                        Check the original bank slip against
                        the information above before approving.

                    </p>


                    {{-- Approve --}}
                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fee-payments.approve',
                            $voucher
                        ) }}"
                        class="mt-4">

                        @csrf

                        <textarea
                            name="remarks"
                            rows="3"
                            class="w-full rounded-lg
                                   border border-gray-300
                                   p-2.5 text-sm"
                            placeholder="Approval remarks..."></textarea>


                        <button
                            type="submit"
                            class="mt-2 w-full h-10
                                   rounded-lg
                                   bg-emerald-500
                                   hover:bg-emerald-600
                                   text-white
                                   text-sm font-semibold">

                            Approve Payment

                        </button>

                    </form>


                    {{-- Reject --}}
                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fee-payments.reject',
                            $voucher
                        ) }}"
                        class="mt-3">

                        @csrf

                        <textarea
                            name="remarks"
                            rows="2"
                            required
                            class="w-full rounded-lg
                                   border border-gray-300
                                   p-2.5 text-sm"
                            placeholder="Reason for rejection..."></textarea>


                        <button
                            type="submit"
                            class="mt-2 w-full h-9
                                   rounded-lg
                                   bg-red-50
                                   hover:bg-red-100
                                   text-red-700
                                   text-sm font-semibold">

                            Reject Payment

                        </button>

                    </form>

                </div>

            @endif


            {{-- Payment approved --}}
            @if(
                $payment &&
                $payment->status === 'approved'
            )

                <div class="bg-emerald-50
                            border border-emerald-200
                            rounded-xl p-4">

                    <div class="flex items-start gap-2">

                        <i data-lucide="circle-check"
                           class="w-5 h-5
                                  text-emerald-600
                                  shrink-0">
                        </i>

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-emerald-800">

                                Payment Verified

                            </p>

                            <p class="text-xs
                                      text-emerald-700
                                      mt-1">

                                The deposited payment has been
                                physically verified and approved.

                            </p>

                        </div>

                    </div>


                    @if(!$isHostel)

                        <a
                            href="{{ route(
                                'admin.admissions.show',
                                $admission
                            ) }}"
                            class="mt-3 w-full h-9
                                   rounded-lg
                                   bg-white
                                   border
                                   border-emerald-300
                                   hover:bg-emerald-100
                                   text-emerald-700
                                   text-xs font-semibold
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-1.5">

                            <i data-lucide="graduation-cap"
                               class="w-4 h-4">
                            </i>

                            Go to Admission

                        </a>

                    @endif

                </div>

            @endif


            {{-- Hostel --}}
            @if($isHostel && $payment?->status === 'approved')

                <div class="bg-purple-50
                            border border-purple-200
                            rounded-xl p-4">

                    <p class="text-sm
                              font-semibold
                              text-purple-800">

                        Hostel Payment Complete

                    </p>

                    <p class="text-xs
                              text-purple-700 mt-1">

                        Hostel payments do not create an admission
                        or student card.

                    </p>

                </div>

            @endif


            {{-- Admission guidance --}}
            @if(
                !$isHostel &&
                $payment?->status === 'approved'
            )

                <div class="bg-white border
                            rounded-xl p-4">

                    <p class="text-sm font-semibold">
                        Next Step
                    </p>

                    @if($admission)

                        @if($admission->status === 'pending')

                            <p class="text-xs
                                      text-gray-500 mt-1">

                                Open the admission record,
                                verify the physical application
                                and documents, then approve the
                                admission.

                            </p>

                        @elseif($admission->status === 'approved')

                            <p class="text-xs
                                      text-gray-500 mt-1">

                                Admission has been approved.
                                Student card can now be generated
                                from the Admissions page.

                            </p>

                        @endif

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('styles')
<style>

    .label {
        font-size: 0.65rem;
        line-height: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
        color: rgb(107 114 128);
    }

    .value {
        margin-top: 0.2rem;
        font-size: 0.8125rem;
        color: rgb(31 41 55);
    }

    .form-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgb(55 65 81);
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
        outline: none;
    }

    .form-input:focus {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 1px rgb(59 130 246);
    }

    .status-gray,
    .status-amber,
    .status-green,
    .status-red {
        display: inline-flex;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .status-gray {
        background: rgb(243 244 246);
        color: rgb(75 85 99);
    }

    .status-amber {
        background: rgb(255 247 237);
        color: rgb(180 83 9);
    }

    .status-green {
        background: rgb(236 253 245);
        color: rgb(4 120 87);
    }

    .status-red {
        background: rgb(254 242 242);
        color: rgb(185 28 28);
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