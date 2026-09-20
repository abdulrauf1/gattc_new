@extends('layouts.public')

@section('title', 'Generated Vouchers - GATTC')

@section('content')

<div class="min-h-screen bg-slate-50 py-10">

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 text-center">

            <div class="mb-3 inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                GATTC Online Admission
            </div>

            <h1 class="text-3xl font-bold text-slate-900">
                Generated Fee Vouchers
            </h1>

            <p class="mt-3 text-sm text-slate-600">
                Your required vouchers have been generated.
                Print each voucher and deposit the amount into the
                bank account shown on that voucher.
            </p>

        </div>


        @if (session('success'))

            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>

        @endif


        <div class="space-y-4">

            @foreach ($vouchers as $voucher)

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                {{ ucfirst($voucher->voucher_type) }} Voucher
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-slate-900">
                                {{ $voucher->course?->title ?? 'Hostel Fee' }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-600">
                                Voucher No:
                                <strong>
                                    {{ $voucher->voucher_no }}
                                </strong>
                            </p>

                            <p class="mt-1 text-sm text-slate-600">
                                Amount:
                                <strong>
                                    Rs. {{ number_format($voucher->amount, 2) }}
                                </strong>
                            </p>

                            @if ($voucher->bankAccount)

                                <p class="mt-1 text-sm text-slate-600">
                                    Bank:
                                    {{ $voucher->bankAccount->bank_name }}
                                </p>

                                <p class="text-sm text-slate-600">
                                    Account:
                                    {{ $voucher->bankAccount->account_number }}
                                </p>

                            @endif

                        </div>


                        <div class="flex gap-2">

                            <a
                                href="{{ route('public.admission.voucher', $voucher->voucher_no) }}"
                                class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                            >
                                View / Print Voucher
                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        <div class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <h3 class="font-bold text-slate-900">
                Payment Procedure
            </h3>

            <ol class="mt-2 space-y-1 text-sm leading-6 text-slate-700">

                <li>
                    1. Print each generated voucher.
                </li>

                <li>
                    2. Deposit each voucher amount into the bank account printed on that voucher.
                </li>

                <li>
                    3. Bring the original bank deposit slip to GATTC.
                </li>

                <li>
                    4. The authorized GATTC clerk will record and verify the payment.
                </li>

                <li>
                    5. Admission approval will be completed after verification.
                </li>

            </ol>

        </div>

    </div>

</div>

@endsection