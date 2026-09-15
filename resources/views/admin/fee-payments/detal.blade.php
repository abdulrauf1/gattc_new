@extends('layouts.admin')

@section('title', 'Verify Payment')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Payment Verification
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Review the submitted payment and update its status.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="mb-5 text-lg font-bold">
                Voucher Information
            </h2>

            @if($feePayment->voucher)

                <div class="space-y-3 text-sm">

                    <div>
                        <strong>Voucher No:</strong>
                        {{ $feePayment->voucher->voucher_no }}
                    </div>

                    <div>
                        <strong>Applicant:</strong>
                        {{ $feePayment->voucher->applicant_name }}
                    </div>

                    <div>
                        <strong>Father:</strong>
                        {{ $feePayment->voucher->father_name }}
                    </div>

                    <div>
                        <strong>CNIC:</strong>
                        {{ $feePayment->voucher->cnic }}
                    </div>

                    <div>
                        <strong>Course:</strong>
                        {{ $feePayment->voucher->course?->title ?? 'N/A' }}
                    </div>

                    <div>
                        <strong>Amount:</strong>
                        Rs. {{ number_format($feePayment->voucher->amount, 0) }}
                    </div>

                    <div>
                        <strong>Bank:</strong>
                        {{ $feePayment->voucher->bank_name }}
                    </div>

                    <div>
                        <strong>Account:</strong>
                        {{ $feePayment->voucher->account_number }}
                    </div>

                </div>

            @endif

        </div>


        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="mb-5 text-lg font-bold">
                Payment
            </h2>

            <div class="space-y-3 text-sm">

                <div>
                    <strong>Payment Date:</strong>
                    {{ $feePayment->payment_date }}
                </div>

                <div>
                    <strong>Status:</strong>
                    {{ ucfirst($feePayment->status) }}
                </div>

                <div>
                    <strong>Remarks:</strong>
                    {{ $feePayment->remarks ?: '—' }}
                </div>

                @if($feePayment->payment_slip)

                    <a href="{{ route('admin.fee-payments.slip', $feePayment) }}"
                       target="_blank"
                       class="inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
                        View Payment Slip
                    </a>

                @endif

            </div>


            @if($feePayment->status === 'pending')

                <div class="mt-6 flex gap-3">

                    <form method="POST"
                          action="{{ route('admin.fee-payments.approve', $feePayment) }}">

                        @csrf

                        <button class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white">
                            Approve Payment
                        </button>

                    </form>


                    <form method="POST"
                          action="{{ route('admin.fee-payments.reject', $feePayment) }}">

                        @csrf

                        <button class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white">
                            Reject Payment
                        </button>

                    </form>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection