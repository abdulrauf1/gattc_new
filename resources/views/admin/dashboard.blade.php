@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-heading', 'Dashboard')


@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    GATTC Admin Dashboard
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Government Advance Technical Training Centre
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('admin.vouchers.create') }}"
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Generate Voucher
                </a>

                <a href="{{ route('admin.admission-sessions.index') }}"
                   class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">
                    Admission Sessions
                </a>

            </div>

        </div>


        {{-- Active Session --}}
        @if($activeSession)

            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-sm font-semibold text-emerald-800">
                            Active Admission Session
                        </p>

                        <h2 class="text-lg font-bold text-emerald-900">
                            {{ $activeSession->title }}
                        </h2>

                        <p class="text-sm text-emerald-700">
                            {{ \Carbon\Carbon::parse($activeSession->opening_date)->format('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse($activeSession->closing_date)->format('d M Y') }}
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full bg-emerald-600 px-3 py-1 text-xs font-bold text-white">
                        OPEN
                    </span>

                </div>

            </div>

        @else

            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">

                <p class="text-sm font-semibold text-amber-800">
                    No Active Admission Session
                </p>

                <p class="mt-1 text-sm text-amber-700">
                    Online admissions are currently closed.
                </p>

            </div>

        @endif


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Applications --}}
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm font-medium text-slate-500">
                    Applications
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ number_format($applicationCount) }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Total admission applications
                </p>

            </div>


            {{-- Approved Students --}}
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm font-medium text-slate-500">
                    Approved Students
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ number_format($studentCount) }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Finalized admissions
                </p>

            </div>


            {{-- Courses --}}
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm font-medium text-slate-500">
                    Active Courses
                </p>

                <p class="mt-2 text-3xl font-bold text-indigo-600">
                    {{ number_format($courseCount) }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Currently available courses
                </p>

            </div>


            {{-- Collection --}}
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm font-medium text-slate-500">
                    Fee Collection
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-600">
                    Rs. {{ number_format($feeCollection, 0) }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Approved payments
                </p>

            </div>

        </div>


        {{-- Secondary Statistics --}}
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">
                    Pending Payments
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ number_format($pendingPayments) }}
                </p>

                <a href="{{ route('admin.fee-payments.index') }}"
                   class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Review Payments →
                </a>
            </div>


            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">
                    Generated Vouchers
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-600">
                    {{ number_format($pendingVouchers) }}
                </p>

                <a href="{{ route('admin.vouchers.index') }}"
                   class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    View Vouchers →
                </a>
            </div>


            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">
                    Paid Vouchers
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-600">
                    {{ number_format($paidVouchers) }}
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Successfully paid
                </p>
            </div>


            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">
                    Active Bank Accounts
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ number_format($bankAccountCount) }}
                </p>

                <a href="{{ route('admin.bank-accounts.index') }}"
                   class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Manage Accounts →
                </a>
            </div>

        </div>


        {{-- Main Content --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">


            {{-- Recent Applications --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                    <h2 class="font-semibold text-slate-800">
                        Recent Applications
                    </h2>

                    <a href="{{ route('admin.admissions.index') }}"
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        View All
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($recentApplications as $application)

                        <div class="flex items-center justify-between px-5 py-4">

                            <div class="min-w-0">

                                <p class="truncate font-semibold text-slate-800">
                                    {{ $application->student_name }}
                                </p>

                                <p class="truncate text-sm text-slate-500">
                                    {{ $application->course?->title ?? 'Course unavailable' }}
                                </p>

                            </div>


                            @php
                                $statusClass = match($application->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp

                            <span class="ml-4 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($application->status) }}
                            </span>

                        </div>

                    @empty

                        <div class="px-5 py-8 text-center text-sm text-slate-500">
                            No applications found.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Recent Vouchers --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                    <h2 class="font-semibold text-slate-800">
                        Recent Vouchers
                    </h2>

                    <a href="{{ route('admin.vouchers.index') }}"
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        View All
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($recentVouchers as $voucher)

                        <div class="flex items-center justify-between px-5 py-4">

                            <div class="min-w-0">

                                <p class="font-semibold text-slate-800">
                                    {{ $voucher->voucher_no }}
                                </p>

                                <p class="truncate text-sm text-slate-500">
                                    {{ $voucher->applicant_name }}
                                    -
                                    {{ $voucher->course?->title ?? 'N/A' }}
                                </p>

                            </div>


                            <div class="ml-4 text-right">

                                <p class="font-semibold text-slate-800">
                                    Rs. {{ number_format($voucher->amount, 0) }}
                                </p>

                                @php
                                    $voucherClass = match($voucher->status) {
                                        'paid' => 'text-emerald-600',
                                        'cancelled' => 'text-red-600',
                                        default => 'text-amber-600',
                                    };
                                @endphp

                                <p class="text-xs font-semibold {{ $voucherClass }}">
                                    {{ ucfirst($voucher->status) }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-8 text-center text-sm text-slate-500">
                            No vouchers found.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Course Statistics --}}
        <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100">

            <div class="border-b border-slate-100 px-5 py-4">

                <h2 class="font-semibold text-slate-800">
                    Course Statistics
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-100">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Course
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Type
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Fee
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Applications
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($courseStatistics as $course)

                            <tr>

                                <td class="px-5 py-4 text-sm font-medium text-slate-800">
                                    {{ $course->title }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">

                                    @php
                                        $typeLabel = match($course->course_type) {
                                            'dit' => 'DIT / 2nd Shift',
                                            'private' => 'Private / IMC',
                                            default => 'Regular',
                                        };
                                    @endphp

                                    {{ $typeLabel }}

                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    Rs. {{ number_format($course->fee_amount, 0) }}
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-800">
                                    {{ number_format($course->admissions_count) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4"
                                    class="px-5 py-8 text-center text-sm text-slate-500">
                                    No course statistics available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Payment Summary --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm text-slate-500">
                    Pending Payments
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-600">
                    {{ number_format($pendingPayments) }}
                </p>

            </div>


            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm text-slate-500">
                    Approved Payments
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ number_format($approvedPayments) }}
                </p>

            </div>


            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                <p class="text-sm text-slate-500">
                    Rejected Payments
                </p>

                <p class="mt-2 text-3xl font-bold text-red-600">
                    {{ number_format($rejectedPayments) }}
                </p>

            </div>

        </div>

    </div>

</div>

@endsection