@extends('layouts.admin')

@section('page-heading', 'Payment Verification')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    @php
        $voucher = $feePayment->voucher;
        $admission = $voucher?->admission;

        $isHostel =
            $voucher?->voucher_type === 'hostel';

        $isReadmission =
            $voucher?->voucher_type === 'readmission';

        $needsFinalization =
            !$isHostel &&
            $feePayment->status === 'approved';
    @endphp


    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-xl font-bold text-gray-900">
                    Payment Verification
                </h1>

                <span
                    class="px-2.5 py-1 rounded-full
                           text-xs font-semibold
                           @if($feePayment->status === 'approved')
                               bg-emerald-50 text-emerald-700
                           @elseif($feePayment->status === 'rejected')
                               bg-red-50 text-red-700
                           @else
                               bg-amber-50 text-amber-700
                           @endif">

                    {{ ucfirst($feePayment->status) }}

                </span>

            </div>

            <p class="text-xs text-gray-500 mt-1">

                Voucher:
                <strong>
                    {{ $voucher?->voucher_no }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route('admin.fee-payments.index') }}"
            class="h-9 px-3 rounded-lg bg-gray-100
                   hover:bg-gray-200
                   text-gray-700 text-sm
                   inline-flex items-center gap-1.5">

            <i data-lucide="arrow-left"
               class="w-4 h-4">
            </i>

            Back

        </a>

    </div>


    {{-- Verification Reminder --}}
    @if($feePayment->status === 'pending')

        <div
            class="rounded-xl border
                   border-amber-200
                   bg-amber-50 p-4">

            <div class="flex gap-3">

                <i data-lucide="triangle-alert"
                   class="w-5 h-5 text-amber-600">
                </i>

                <div>

                    <p class="text-sm font-semibold text-amber-800">
                        Physical Verification Required
                    </p>

                    <p class="text-xs text-amber-700 mt-1">
                        Compare the uploaded payment slip with the
                        original deposited bank copy before approving
                        this payment.
                    </p>

                </div>

            </div>

        </div>

    @endif


    <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">


        {{-- LEFT --}}
        <div class="xl:col-span-8 space-y-4">


            {{-- Student / Voucher --}}
            <div class="bg-white border rounded-xl overflow-hidden">

                <div class="px-4 py-3 border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Student & Voucher Information
                    </h2>

                </div>


                <div class="p-4 grid grid-cols-1
                            sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <p class="detail-label">
                            Student
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->applicant_name }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Father
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->father_name ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            CNIC
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->cnic }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Contact
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->phone }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Voucher
                        </p>

                        <p class="detail-value font-semibold">
                            {{ $voucher?->voucher_no }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Voucher Type
                        </p>

                        <p class="detail-value">
                            {{ ucfirst(
                                $voucher?->voucher_type
                            ) }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Course
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->course?->title
                                ?: (
                                    $isHostel
                                        ? 'Hostel Fee'
                                        : '—'
                                )
                            }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Voucher Amount
                        </p>

                        <p class="text-lg font-bold">
                            Rs.
                            {{ number_format(
                                $voucher?->amount ?? 0,
                                2
                            ) }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Payment --}}
            <div class="bg-white border rounded-xl overflow-hidden">

                <div class="px-4 py-3 border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Deposited Payment
                    </h2>

                </div>


                <div class="p-4 grid grid-cols-1
                            sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <p class="detail-label">
                            Deposit Slip No.
                        </p>

                        <p class="detail-value">
                            {{ $feePayment->deposit_slip_no ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Bank Transaction No.
                        </p>

                        <p class="detail-value">
                            {{ $feePayment->bank_transaction_no ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Payment Date
                        </p>

                        <p class="detail-value">
                            {{ optional(
                                $feePayment->payment_date
                            )->format('d M Y') ?: '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="detail-label">
                            Amount Deposited
                        </p>

                        <p class="text-lg font-bold">
                            Rs.
                            {{ number_format(
                                $feePayment->amount,
                                2
                            ) }}
                        </p>
                    </div>

                </div>


                <div class="px-4 pb-4">

                    <div class="rounded-lg
                                bg-gray-50 border p-3">

                        <div class="flex items-center
                                    justify-between">

                            <div>

                                <p class="text-xs
                                          font-semibold">
                                    Uploaded Payment Slip
                                </p>

                                <p class="text-[11px]
                                          text-gray-500 mt-0.5">
                                    Open and compare with the
                                    physical bank receipt.
                                </p>

                            </div>

                            @if($feePayment->payment_slip)

                                <a
                                    href="{{ route(
                                        'admin.fee-payments.slip',
                                        $feePayment
                                    ) }}"
                                    target="_blank"
                                    class="h-8 px-3
                                           rounded-lg
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

                                <span
                                    class="text-xs text-red-600">
                                    No payment slip uploaded.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Bank --}}
            <div class="bg-white border rounded-xl overflow-hidden">

                <div class="px-4 py-3 border-b bg-gray-50">

                    <h2 class="text-sm font-semibold">
                        Expected Bank Account
                    </h2>

                </div>

                <div class="p-4 grid grid-cols-1
                            sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <p class="detail-label">
                            Bank
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->bank_name }}
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="detail-label">
                            Account Title
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->account_title }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">
                            Account Number
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->account_number }}
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="detail-label">
                            IBAN
                        </p>

                        <p class="detail-value">
                            {{ $voucher?->iban ?: '—' }}
                        </p>
                    </div>

                </div>

            </div>


        </div>


        {{-- RIGHT --}}
        <div class="xl:col-span-4 space-y-4">


            {{-- Approve / Reject --}}
            @if($feePayment->status === 'pending')

                <div class="bg-white border rounded-xl p-4">

                    <h2 class="text-sm font-semibold">
                        Verification Decision
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        Verify the physical bank slip before making a decision.
                    </p>


                    {{-- Approve --}}
                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fee-payments.approve',
                            $feePayment
                        ) }}"
                        class="mt-4">

                        @csrf

                        <label class="detail-label">
                            Verification Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="w-full mt-1 rounded-lg
                                   border border-gray-300
                                   text-sm p-2.5"
                            placeholder="Payment verification remarks..."></textarea>

                        <button
                            type="submit"
                            class="mt-3 w-full h-10
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
                            $feePayment
                        ) }}"
                        class="mt-3">

                        @csrf

                        <textarea
                            name="remarks"
                            rows="2"
                            required
                            class="w-full rounded-lg
                                   border border-gray-300
                                   text-sm p-2.5"
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


            {{-- Admission Finalization --}}
            @if($needsFinalization)

                <div class="bg-white border
                            border-emerald-200
                            rounded-xl p-4">

                    <div class="flex items-center gap-2">

                        <div
                            class="w-8 h-8 rounded-lg
                                   bg-emerald-50
                                   flex items-center
                                   justify-center">

                            <i data-lucide="badge-check"
                               class="w-4 h-4
                                      text-emerald-600">
                            </i>

                        </div>

                        <div>

                            <h2 class="text-sm font-semibold">
                                Finalize Admission
                            </h2>

                            <p class="text-[11px] text-gray-500">
                                Payment approved. Complete physical admission verification.
                            </p>

                        </div>

                    </div>


                    @if($isReadmission)

                        <div
                            class="mt-3 rounded-lg
                                   bg-amber-50
                                   border border-amber-200
                                   p-3">

                            <p class="text-xs
                                      text-amber-800">

                                This is a
                                <strong>Readmission</strong>
                                voucher. The existing admission
                                number will remain unchanged.

                            </p>

                        </div>

                    @endif


                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route(
                            'admin.fee-payments.finalize-admission',
                            $feePayment
                        ) }}"
                        class="mt-4">

                        @csrf


                        <label class="detail-label">
                            Student Photo *
                        </label>

                        <input
                            type="file"
                            name="student_photo"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                            class="mt-1 block w-full text-xs
                                   border border-gray-300
                                   rounded-lg p-2">

                        <p class="mt-1 text-[10px]
                                  text-gray-500">
                            JPG, PNG or WEBP. Maximum 2 MB.
                        </p>


                        <label class="detail-label block mt-4">
                            Admission Remarks
                        </label>

                        <textarea
                            name="admission_remarks"
                            rows="3"
                            class="w-full mt-1 rounded-lg
                                   border border-gray-300
                                   text-sm p-2.5"
                            placeholder="Physical application/document verification remarks..."></textarea>


                        <button
                            type="submit"
                            class="mt-3 w-full h-10
                                   rounded-lg
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-sm font-semibold
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-2">

                            <i data-lucide="credit-card"
                               class="w-4 h-4">
                            </i>

                            Finalize & Generate Student Card

                        </button>

                    </form>

                </div>

            @endif


            {{-- Hostel --}}
            @if($isHostel && $feePayment->status === 'approved')

                <div class="bg-white border
                            border-purple-200
                            rounded-xl p-4">

                    <div class="flex items-center gap-2">

                        <i data-lucide="bed-double"
                           class="w-5 h-5 text-purple-600">
                        </i>

                        <div>

                            <h2 class="text-sm font-semibold">
                                Hostel Payment Verified
                            </h2>

                            <p class="text-xs text-gray-500">
                                No admission/card generation is required.
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Existing Admission --}}
            @if($admission)

                <div class="bg-white border rounded-xl p-4">

                    <p class="detail-label">
                        Admission
                    </p>

                    <p class="text-lg font-bold mt-1">
                        {{ $admission->admission_no }}
                    </p>

                    <div class="mt-2">

                        <span
                            class="inline-flex px-2.5 py-1
                                   rounded-full text-xs
                                   font-semibold
                                   @if($admission->status === 'approved')
                                       bg-emerald-50
                                       text-emerald-700
                                   @elseif($admission->status === 'rejected')
                                       bg-red-50 text-red-700
                                   @else
                                       bg-amber-50
                                       text-amber-700
                                   @endif">

                            {{ ucfirst($admission->status) }}

                        </span>

                    </div>

                    @if($admission->studentCards->count())

                        <div class="mt-3">

                            @foreach(
                                $admission->studentCards
                                ->where('status', true)
                                as $card
                            )

                                <a
                                    href="{{ route(
                                        'admin.student-cards.print',
                                        $card
                                    ) }}"
                                    target="_blank"
                                    class="w-full h-8 rounded-lg
                                           bg-gray-100
                                           hover:bg-gray-200
                                           text-gray-700
                                           text-xs font-medium
                                           inline-flex items-center
                                           justify-center gap-1.5">

                                    <i data-lucide="printer"
                                       class="w-3.5 h-3.5">
                                    </i>

                                    Print {{ $card->card_no }}

                                </a>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endif

        </div>

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