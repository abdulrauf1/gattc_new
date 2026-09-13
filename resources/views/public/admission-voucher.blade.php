@extends('layouts.public')

@section('title', 'Admission Fee Voucher')

@section('content')
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="bg-blue-900 px-6 py-8 text-center text-white">
                    <h1 class="text-2xl font-bold">
                        GATTC Admission Fee Voucher
                    </h1>

                    <p class="mt-2 text-blue-100">
                        Government Advance Technical Training Centre
                    </p>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Voucher No.</p>
                            <p class="font-bold">
                                {{ $voucher->voucher_no }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Admission Session
                            </p>
                            <p class="font-semibold">
                                {{ $voucher->admissionSession->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Applicant Name
                            </p>
                            <p class="font-semibold">
                                {{ $voucher->applicant_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Father Name
                            </p>
                            <p class="font-semibold">
                                {{ $voucher->father_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">CNIC</p>
                            <p class="font-semibold">
                                {{ $voucher->cnic }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-semibold">
                                {{ $voucher->phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Issue Date</p>
                            <p class="font-semibold">
                                {{ $voucher->issue_date?->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Due Date</p>
                            <p class="font-semibold text-red-600">
                                {{ $voucher->due_date?->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-blue-50 p-6 text-center">
                        <p class="text-sm text-blue-700">
                            Payable Amount
                        </p>

                        <p class="mt-2 text-4xl font-bold text-blue-900">
                            Rs. {{ number_format((float) $voucher->amount, 2) }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-dashed border-gray-300 p-5">
                        <h2 class="font-bold text-gray-900">
                            Payment Instructions
                        </h2>

                        <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-gray-600">
                            <li>Print this voucher.</li>
                            <li>Deposit the amount through the designated bank account.</li>
                            <li>Keep the stamped bank receipt safely.</li>
                            <li>Admission will be created after payment verification.</li>
                        </ul>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="rounded-xl bg-blue-700 px-5 py-3 font-semibold text-white"
                        >
                            Print Voucher
                        </button>

                        <a
                            href="{{ route('public.admission') }}"
                            class="rounded-xl border border-gray-300 px-5 py-3 font-semibold text-gray-700"
                        >
                            Back to Admission
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection