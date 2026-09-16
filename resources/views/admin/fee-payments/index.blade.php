@extends('layouts.admin')

@section('page-heading', 'Payment Verification')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            Payment Verification
        </h1>

        <p class="text-xs text-gray-500 mt-1">
            Verify deposited bank slips against generated vouchers.
        </p>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase font-semibold text-gray-400">
                All Vouchers
            </p>

            <p class="text-xl font-bold text-gray-900">
                {{ $totalVouchers }}
            </p>
        </div>


        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase font-semibold text-gray-400">
                Not Submitted
            </p>

            <p class="text-xl font-bold text-gray-500">
                {{ $notSubmitted }}
            </p>
        </div>


        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase font-semibold text-gray-400">
                Pending
            </p>

            <p class="text-xl font-bold text-amber-600">
                {{ $pendingVerification }}
            </p>
        </div>


        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase font-semibold text-gray-400">
                Approved
            </p>

            <p class="text-xl font-bold text-emerald-600">
                {{ $approvedPayments }}
            </p>
        </div>


        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase font-semibold text-gray-400">
                Rejected
            </p>

            <p class="text-xl font-bold text-red-600">
                {{ $rejectedPayments }}
            </p>
        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white border rounded-xl p-3">

        <form method="GET"
              action="{{ route('admin.fee-payments.index') }}">

            <div class="flex flex-col lg:flex-row gap-2">

                {{-- Search --}}
                <div class="flex-1">

                    <div class="relative">

                        <i data-lucide="search"
                           class="absolute left-3 top-1/2
                                  -translate-y-1/2
                                  w-4 h-4 text-gray-400">
                        </i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search voucher, student, CNIC or phone..."
                            class="w-full h-9 rounded-lg
                                   border border-gray-300
                                   pl-9 pr-3 text-sm
                                   focus:border-blue-500
                                   focus:ring-1
                                   focus:ring-blue-500">

                    </div>

                </div>


                {{-- Payment Status --}}
                <div class="w-full lg:w-44">

                    <select
                        name="payment_status"
                        class="w-full h-9 rounded-lg
                               border border-gray-300
                               px-3 text-sm">

                        <option value="">
                            All Payment Status
                        </option>

                        <option
                            value="not_submitted"
                            @selected(
                                request('payment_status') === 'not_submitted'
                            )>
                            Not Submitted
                        </option>

                        <option
                            value="pending"
                            @selected(
                                request('payment_status') === 'pending'
                            )>
                            Pending Verification
                        </option>

                        <option
                            value="approved"
                            @selected(
                                request('payment_status') === 'approved'
                            )>
                            Approved
                        </option>

                        <option
                            value="rejected"
                            @selected(
                                request('payment_status') === 'rejected'
                            )>
                            Rejected
                        </option>

                    </select>

                </div>


                {{-- Type --}}
                <div class="w-full lg:w-36">

                    <select
                        name="type"
                        class="w-full h-9 rounded-lg
                               border border-gray-300
                               px-3 text-sm">

                        <option value="">
                            All Types
                        </option>

                        <option
                            value="admission"
                            @selected(
                                request('type') === 'admission'
                            )>
                            Admission
                        </option>

                        <option
                            value="hostel"
                            @selected(
                                request('type') === 'hostel'
                            )>
                            Hostel
                        </option>

                        <option
                            value="readmission"
                            @selected(
                                request('type') === 'readmission'
                            )>
                            Readmission
                        </option>

                    </select>

                </div>


                {{-- Filter --}}
                <button
                    type="submit"
                    class="h-9 px-4 rounded-lg
                           bg-emerald-500
                           hover:bg-emerald-600
                           text-white text-sm font-semibold
                           inline-flex items-center
                           justify-center gap-1.5">

                    <i data-lucide="filter"
                       class="w-4 h-4">
                    </i>

                    Filter

                </button>


                {{-- Reset --}}
                <a
                    href="{{ route(
                        'admin.fee-payments.index'
                    ) }}"
                    class="h-9 w-9 shrink-0
                           rounded-lg
                           bg-gray-100
                           hover:bg-gray-200
                           flex items-center
                           justify-center">

                    <i data-lucide="rotate-ccw"
                       class="w-4 h-4 text-gray-600">
                    </i>

                </a>

            </div>

        </form>

    </div>


    {{-- Table --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Voucher
                        </th>

                        <th class="px-4 py-3 text-left">
                            Student
                        </th>

                        <th class="px-4 py-3 text-left">
                            Type
                        </th>

                        <th class="px-4 py-3 text-left">
                            Course
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left">
                            Payment
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                        <th class="px-4 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse($vouchers as $voucher)

                    @php
                        $payment = $voucher->payment;
                    @endphp

                    <tr class="hover:bg-gray-50">

                        {{-- Voucher --}}
                        <td class="px-4 py-3">

                            <div class="font-semibold text-gray-900">
                                {{ $voucher->voucher_no }}
                            </div>

                            @if($voucher->admission)
                                <div class="text-[11px] text-gray-500">
                                    {{ $voucher->admission->admission_no }}
                                </div>
                            @endif

                        </td>


                        {{-- Student --}}
                        <td class="px-4 py-3">

                            <div class="font-medium text-gray-900">
                                {{ $voucher->applicant_name }}
                            </div>

                            <div class="text-[11px] text-gray-500">
                                {{ $voucher->cnic }}
                            </div>

                        </td>


                        {{-- Type --}}
                        <td class="px-4 py-3">

                            @php
                                $typeClass =
                                    match ($voucher->voucher_type) {
                                        'admission' =>
                                            'bg-blue-50 text-blue-700',

                                        'hostel' =>
                                            'bg-purple-50 text-purple-700',

                                        'readmission' =>
                                            'bg-amber-50 text-amber-700',

                                        default =>
                                            'bg-gray-100 text-gray-600',
                                    };
                            @endphp

                            <span
                                class="inline-flex
                                       px-2 py-1 rounded-full
                                       text-[11px] font-semibold
                                       {{ $typeClass }}">

                                {{ $voucher->voucher_type_label }}

                            </span>

                        </td>


                        {{-- Course --}}
                        <td class="px-4 py-3">

                            {{ $voucher->course?->title
                                ?: (
                                    $voucher->voucher_type === 'hostel'
                                        ? 'Hostel Fee'
                                        : '—'
                                )
                            }}

                        </td>


                        {{-- Amount --}}
                        <td class="px-4 py-3 font-semibold">

                            Rs.
                            {{ number_format(
                                $voucher->amount,
                                0
                            ) }}

                        </td>


                        {{-- Payment --}}
                        <td class="px-4 py-3">

                            @if(!$payment)

                                <span class="text-gray-400">
                                    Not Submitted
                                </span>

                            @else

                                <div class="font-medium">

                                    {{ $payment->deposit_slip_no
                                        ?: 'Slip Pending'
                                    }}

                                </div>

                                <div class="text-[11px] text-gray-500">

                                    {{ optional(
                                        $payment->payment_date
                                    )->format('d M Y') }}

                                </div>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td class="px-4 py-3">

                            @if(!$payment)

                                <span
                                    class="inline-flex
                                           px-2 py-1
                                           rounded-full
                                           bg-gray-100
                                           text-gray-600
                                           text-[11px]
                                           font-semibold">
                                    Not Submitted
                                </span>

                            @else

                                @php
                                    $statusClass =
                                        match ($payment->status) {
                                            'approved' =>
                                                'bg-emerald-50 text-emerald-700',

                                            'rejected' =>
                                                'bg-red-50 text-red-700',

                                            default =>
                                                'bg-amber-50 text-amber-700',
                                        };
                                @endphp

                                <span
                                    class="inline-flex
                                           px-2 py-1
                                           rounded-full
                                           text-[11px]
                                           font-semibold
                                           {{ $statusClass }}">

                                    {{ ucfirst(
                                        $payment->status
                                    ) }}

                                </span>

                            @endif

                        </td>


                        {{-- Action --}}
                        <td class="px-4 py-3 text-right">

                            <a
                                href="{{ route(
                                    'admin.fee-payments.voucher',
                                    $voucher
                                ) }}"
                                class="inline-flex
                                       items-center justify-center
                                       h-8 px-3 rounded-lg
                                       bg-gray-100
                                       hover:bg-gray-200
                                       text-gray-700 text-xs
                                       font-semibold">

                                @if(!$payment)
                                    Enter Payment
                                @elseif($payment->status === 'pending')
                                    Verify
                                @elseif($payment->status === 'rejected')
                                    Review
                                @else
                                    View
                                @endif

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="px-6 py-12 text-center">

                            <i data-lucide="receipt-text"
                               class="w-10 h-10
                                      mx-auto
                                      text-gray-300">
                            </i>

                            <p class="mt-2
                                      text-sm text-gray-500">
                                No vouchers found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($vouchers->hasPages())

            <div class="border-t px-4 py-3">
                {{ $vouchers->links() }}
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