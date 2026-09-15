@extends('layouts.admin')

@section('title', 'Payment Verification')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Payment Verification
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Review uploaded bank payment slips.
        </p>

    </div>


    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                        Voucher
                    </th>

                    <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                        Applicant
                    </th>

                    <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                        Payment Date
                    </th>

                    <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                        Status
                    </th>

                    <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">
                        Action
                    </th>

                </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                @forelse($feePayments as $payment)

                    <tr>

                        <td class="px-5 py-4 font-semibold">
                            {{ $payment->voucher?->voucher_no ?? 'N/A' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $payment->voucher?->applicant_name ?? 'N/A' }}
                        </td>

                        <td class="px-5 py-4 text-sm">
                            {{ $payment->payment_date }}
                        </td>

                        <td class="px-5 py-4">

                            @php
                                $paymentClass = match($payment->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentClass }}">
                                {{ ucfirst($payment->status) }}
                            </span>

                        </td>

                        <td class="px-5 py-4 text-right">

                            <a href="{{ route('admin.fee-payments.show', $payment) }}"
                               class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700">
                                Review
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-5 py-10 text-center text-slate-500">

                            No payment records found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection