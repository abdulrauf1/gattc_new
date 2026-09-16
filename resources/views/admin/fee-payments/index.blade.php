@extends('layouts.admin')

@section('page-heading', 'Payment Verification')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Payment Verification
            </h1>

            <p class="text-xs text-gray-500 mt-1">
                Physically verify deposited bank slips before finalizing admissions.
            </p>
        </div>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Total
            </p>

            <p class="text-xl font-bold text-gray-900">
                {{ $totalPayments }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Pending
            </p>

            <p class="text-xl font-bold text-amber-600">
                {{ $pendingPayments }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
                Approved
            </p>

            <p class="text-xl font-bold text-emerald-600">
                {{ $approvedPayments }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-[11px] uppercase text-gray-400 font-semibold">
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

            <div class="flex items-center gap-2">

                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search voucher, student, CNIC, slip or transaction..."
                        class="w-full h-9 rounded-lg border
                               border-gray-300 px-3 text-sm
                               focus:border-blue-500
                               focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="w-36">
                    <select
                        name="status"
                        class="w-full h-9 rounded-lg
                               border border-gray-300
                               px-3 text-sm">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending"
                            @selected(request('status') === 'pending')}>
                            Pending
                        </option>

                        <option value="approved"
                            @selected(request('status') === 'approved')}>
                            Approved
                        </option>

                        <option value="rejected"
                            @selected(request('status') === 'rejected')}>
                            Rejected
                        </option>

                    </select>
                </div>

                <button
                    type="submit"
                    class="h-9 px-4 rounded-lg
                           bg-emerald-500
                           hover:bg-emerald-600
                           text-white text-sm font-semibold">

                    Filter

                </button>

                <a
                    href="{{ route('admin.fee-payments.index') }}"
                    class="h-9 w-9 rounded-lg bg-gray-100
                           flex items-center justify-center">

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

            <table class="w-full min-w-[1000px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Payment
                        </th>

                        <th class="px-4 py-3 text-left">
                            Student
                        </th>

                        <th class="px-4 py-3 text-left">
                            Voucher
                        </th>

                        <th class="px-4 py-3 text-left">
                            Type
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
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

                @forelse($payments as $payment)

                    @php
                        $voucher = $payment->voucher;
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">

                            <div class="font-semibold">
                                {{ $payment->deposit_slip_no ?: '—' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ optional($payment->payment_date)->format('d M Y') }}
                            </div>

                        </td>


                        <td class="px-4 py-3">

                            <div class="font-medium">
                                {{ $voucher?->applicant_name ?: '—' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $voucher?->cnic ?: '—' }}
                            </div>

                        </td>


                        <td class="px-4 py-3">

                            <span class="font-semibold">
                                {{ $voucher?->voucher_no ?: '—' }}
                            </span>

                        </td>


                        <td class="px-4 py-3">

                            {{ $voucher?->voucher_type
                                ? ucfirst($voucher->voucher_type)
                                : '—'
                            }}

                        </td>


                        <td class="px-4 py-3 font-semibold">

                            Rs.
                            {{ number_format(
                                $payment->amount,
                                0
                            ) }}

                        </td>


                        <td class="px-4 py-3">

                            @php
                                $statusClass = match ($payment->status) {
                                    'approved' =>
                                        'bg-emerald-50 text-emerald-700',

                                    'rejected' =>
                                        'bg-red-50 text-red-700',

                                    default =>
                                        'bg-amber-50 text-amber-700',
                                };
                            @endphp

                            <span
                                class="inline-flex px-2.5 py-1
                                       rounded-full text-xs
                                       font-semibold
                                       {{ $statusClass }}">

                                {{ ucfirst($payment->status) }}

                            </span>

                        </td>


                        <td class="px-4 py-3 text-right">

                            <a
                                href="{{ route(
                                    'admin.fee-payments.show',
                                    $payment
                                ) }}"
                                class="inline-flex items-center
                                       justify-center w-8 h-8
                                       rounded-lg bg-gray-100
                                       hover:bg-gray-200">

                                <i data-lucide="eye"
                                   class="w-4 h-4 text-gray-600">
                                </i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-12 text-center">

                            <i data-lucide="receipt-text"
                               class="w-10 h-10 mx-auto
                                      text-gray-300">
                            </i>

                            <p class="mt-2 text-sm
                                      text-gray-500">
                                No payment records found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($payments->hasPages())

            <div class="border-t px-4 py-3">
                {{ $payments->links() }}
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