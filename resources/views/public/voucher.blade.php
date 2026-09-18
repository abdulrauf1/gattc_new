@extends('layouts.public')

@section('title', 'Fee Voucher')

@section('content')

<section class="bg-slate-950 py-12 text-white print:hidden">

    <div class="container-site flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
                Admission voucher
            </div>

            <h1 class="mt-2 text-3xl font-black">
                {{ $voucher->voucher_no }}
            </h1>

        </div>


        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-slate-900"
        >
            <i data-lucide="printer" class="h-4 w-4"></i>
            Print Voucher
        </button>

    </div>

</section>


<section class="py-10">

    <div class="container-site max-w-6xl">

        <div class="print-area rounded-2xl border border-slate-300 bg-white p-6 shadow-sm md:p-8">


            {{-- Header --}}
            <div class="flex flex-col gap-5 border-b-2 border-slate-900 pb-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="text-sm font-bold uppercase tracking-wider text-emerald-700">
                        Government of Khyber Pakhtunkhwa
                    </div>

                    <h2 class="mt-1 text-2xl font-black text-slate-900">
                        Government Advance Technical Training Centre
                    </h2>

                    <div class="mt-1 text-sm text-slate-500">
                        Hayatabad, Peshawar
                    </div>

                </div>


                <div class="sm:text-right">

                    <div class="text-xs font-bold uppercase text-slate-400">
                        Voucher No.
                    </div>

                    <div class="mt-1 text-xl font-black text-slate-900">
                        {{ $voucher->voucher_no }}
                    </div>

                </div>

            </div>


            {{-- Voucher information --}}
            <div class="mt-7 grid gap-4 md:grid-cols-3">

                <div class="rounded-xl bg-slate-50 p-4">

                    <div class="text-xs text-slate-400">
                        Voucher Type
                    </div>

                    <div class="mt-1 font-bold capitalize text-slate-900">
                        {{ str_replace('_', ' ', $voucher->voucher_category ?? $voucher->voucher_type ?? 'Admission') }}
                    </div>

                </div>


                <div class="rounded-xl bg-slate-50 p-4">

                    <div class="text-xs text-slate-400">
                        Issue Date
                    </div>

                    <div class="mt-1 font-bold text-slate-900">
                        {{ $voucher->issue_date?->format('d M Y') }}
                    </div>

                </div>


                <div class="rounded-xl bg-amber-50 p-4">

                    <div class="text-xs text-amber-600">
                        Due Date
                    </div>

                    <div class="mt-1 font-bold text-amber-800">
                        {{ $voucher->due_date?->format('d M Y') }}
                    </div>

                </div>

            </div>


            {{-- Applicant --}}
            <div class="mt-7">

                <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">
                    Applicant information
                </h3>

                <div class="mt-3 grid gap-x-8 gap-y-4 border-t border-slate-200 pt-4 sm:grid-cols-2">

                    <div>
                        <div class="text-xs text-slate-400">Name</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->applicant_name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">Father Name</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->father_name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">CNIC</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->cnic }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">Phone</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->phone }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">Course</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->course?->title ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">Session</div>
                        <div class="mt-1 font-semibold text-slate-900">
                            {{ $voucher->admissionSession?->title ?? '—' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- Bank --}}
            <div class="mt-7">

                <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">
                    Deposit account
                </h3>

                <div class="mt-3 rounded-2xl border border-slate-200 p-5">

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                        <div>
                            <div class="text-xs text-slate-400">Bank</div>
                            <div class="mt-1 font-semibold">
                                {{ $voucher->bank_name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400">Account Title</div>
                            <div class="mt-1 font-semibold">
                                {{ $voucher->account_title }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400">Account Number</div>
                            <div class="mt-1 font-semibold">
                                {{ $voucher->account_number }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400">IBAN</div>
                            <div class="mt-1 break-all font-semibold">
                                {{ $voucher->iban }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Amount --}}
            <div class="mt-7 overflow-hidden rounded-2xl border-2 border-slate-900">

                <div class="flex items-center justify-between gap-4 bg-slate-50 px-5 py-4">

                    <span class="font-bold text-slate-700">
                        Total Amount Payable
                    </span>

                    <span class="text-2xl font-black text-slate-900">
                        Rs. {{ number_format((float) $voucher->amount, 2) }}
                    </span>

                </div>

            </div>


            <div class="mt-6 text-xs leading-5 text-slate-500">

                Please keep the bank deposit slip safely. Payment will remain pending until verified by GATTC administration.

            </div>

        </div>


        {{-- Related generated vouchers --}}
        @if(session('voucher_ids'))

            @php
                $relatedVouchers = \App\Models\Voucher::query()
                    ->whereIn(
                        'id',
                        session('voucher_ids', [])
                    )
                    ->get();
            @endphp

            @if($relatedVouchers->count() > 1)

                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 print:hidden">

                    <div class="font-bold text-slate-900">
                        Generated vouchers
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2">

                        @foreach($relatedVouchers as $related)

                            <a
                                href="{{ route('public.admission.voucher', $related) }}"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-sm hover:border-emerald-300"
                            >
                                {{ $related->voucher_no }}
                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        @endif


        {{-- Payment submission --}}
        @php
            $payment = $voucher->payment ?? null;
        @endphp

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 print:hidden">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <h2 class="text-xl font-black text-slate-900">
                        Submit deposited payment
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Upload the bank slip after depositing the exact voucher amount.
                    </p>

                </div>


                @if($payment)

                    @if($payment->status === 'pending')

                        <span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                            Pending Verification
                        </span>

                    @elseif($payment->status === 'approved')

                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                            Payment Approved
                        </span>

                    @elseif($payment->status === 'rejected')

                        <span class="rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                            Payment Rejected
                        </span>

                    @endif

                @endif

            </div>


            @if(!$payment || $payment->status === 'rejected')

                <form
                    method="POST"
                    action="{{ route('public.payment.submit') }}"
                    enctype="multipart/form-data"
                    class="mt-6 grid gap-5 md:grid-cols-2"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="voucher_id"
                        value="{{ $voucher->id }}"
                    >


                    <div>

                        <label class="mb-2 block text-sm font-semibold">
                            Deposit Slip No.
                        </label>

                        <input
                            type="text"
                            name="deposit_slip_no"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold">
                            Bank Transaction No.
                        </label>

                        <input
                            type="text"
                            name="bank_transaction_no"
                            class="w-full rounded-xl border-slate-300 text-sm"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold">
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            value="{{ old('payment_date', now()->toDateString()) }}"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="w-full rounded-xl border-slate-300 text-sm"
                        >
                            <option value="bank">
                                Bank Deposit
                            </option>
                        </select>

                    </div>


                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold">
                            Bank Slip
                        </label>

                        <input
                            type="file"
                            name="payment_slip"
                            required
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                        >

                    </div>


                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="w-full rounded-xl border-slate-300 text-sm"
                        ></textarea>

                    </div>


                    <div class="md:col-span-2">

                        <div class="rounded-xl bg-blue-50 p-4 text-sm text-blue-800">

                            Payment amount:
                            <strong>
                                Rs. {{ number_format((float) $voucher->amount, 2) }}
                            </strong>

                        </div>

                    </div>


                    <div class="md:col-span-2">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                        >
                            Submit Payment
                            <i data-lucide="upload" class="h-4 w-4"></i>
                        </button>

                    </div>

                </form>

            @else

                <div class="mt-6 rounded-xl bg-slate-50 p-5 text-sm text-slate-600">

                    Your payment information has been submitted and is currently under verification by GATTC administration.

                </div>

            @endif

        </div>

    </div>

</section>


<style>

@media print {

    body {
        background: white !important;
    }

    header,
    footer,
    .print\:hidden,
    nav {
        display: none !important;
    }

    .print-area {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

}

</style>

@endsection