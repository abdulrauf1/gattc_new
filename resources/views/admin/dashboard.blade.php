@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-heading', 'Dashboard')

@section('content')

{{-- =========================================================
     WELCOME
========================================================== --}}

<div class="mb-8">

    <div
        class="relative overflow-hidden rounded-2xl
               bg-gradient-to-r from-slate-950 via-slate-900
               to-emerald-950 p-6 text-white shadow-xl
               sm:p-8"
    >

        <div class="relative z-10 max-w-3xl">

            <div class="mb-3 flex items-center gap-2">

                <span
                    class="rounded-full bg-emerald-500/20
                           px-3 py-1 text-xs font-semibold
                           text-emerald-300"
                >
                    GATTC ADMINISTRATION
                </span>

            </div>

            <h1 class="text-2xl font-bold sm:text-3xl">
                Welcome back, {{ auth()->user()->name }}
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                Manage admissions, courses, fees, announcements,
                events and the GATTC public website from one place.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <a
                    href="{{ route('admin.admission-sessions.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl
                           bg-emerald-500 px-4 py-2.5 text-sm
                           font-semibold text-white transition
                           hover:bg-emerald-400"
                >
                    <i data-lucide="calendar-plus" class="h-4 w-4"></i>
                    Manage Admissions
                </a>

                <a
                    href="{{ route('admin.fee-configurations.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl
                           border border-white/20 bg-white/10 px-4
                           py-2.5 text-sm font-semibold text-white
                           backdrop-blur transition hover:bg-white/20"
                >
                    <i data-lucide="receipt" class="h-4 w-4"></i>
                    Fee Configuration
                </a>

            </div>

        </div>


        {{-- Decorative icon --}}

        <div
            class="absolute -right-10 -top-10 hidden h-64 w-64
                   items-center justify-center rounded-full
                   bg-emerald-500/10 sm:flex"
        >
            <i
                data-lucide="graduation-cap"
                class="h-32 w-32 text-emerald-400/20"
            ></i>
        </div>

    </div>

</div>


{{-- =========================================================
     STATISTICS
========================================================== --}}

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Applications --}}

    <div
        class="rounded-2xl border border-slate-200 bg-white
               p-5 shadow-sm transition hover:-translate-y-0.5
               hover:shadow-md"
    >

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Applications
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $applicationCount ?? 0 }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Total applications
                </p>

            </div>

            <div
                class="flex h-12 w-12 items-center justify-center
                       rounded-xl bg-blue-50 text-blue-600"
            >
                <i data-lucide="file-user" class="h-6 w-6"></i>
            </div>

        </div>

    </div>


    {{-- Students --}}

    <div
        class="rounded-2xl border border-slate-200 bg-white
               p-5 shadow-sm transition hover:-translate-y-0.5
               hover:shadow-md"
    >

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Students
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $studentCount ?? 0 }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Admitted students
                </p>

            </div>

            <div
                class="flex h-12 w-12 items-center justify-center
                       rounded-xl bg-emerald-50 text-emerald-600"
            >
                <i data-lucide="graduation-cap" class="h-6 w-6"></i>
            </div>

        </div>

    </div>


    {{-- Fee collection --}}

    <div
        class="rounded-2xl border border-slate-200 bg-white
               p-5 shadow-sm transition hover:-translate-y-0.5
               hover:shadow-md"
    >

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Fee Collection
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    Rs. {{ number_format($feeCollection ?? 0) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Verified payments
                </p>

            </div>

            <div
                class="flex h-12 w-12 items-center justify-center
                       rounded-xl bg-amber-50 text-amber-600"
            >
                <i data-lucide="banknote" class="h-6 w-6"></i>
            </div>

        </div>

    </div>


    {{-- Pending payments --}}

    <div
        class="rounded-2xl border border-slate-200 bg-white
               p-5 shadow-sm transition hover:-translate-y-0.5
               hover:shadow-md"
    >

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Pending Verification
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $pendingPayments ?? 0 }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Payments awaiting review
                </p>

            </div>

            <div
                class="flex h-12 w-12 items-center justify-center
                       rounded-xl bg-red-50 text-red-600"
            >
                <i data-lucide="clock-3" class="h-6 w-6"></i>
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     SECONDARY STATISTICS
========================================================== --}}

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center gap-4">

            <div class="rounded-xl bg-indigo-50 p-3 text-indigo-600">
                <i data-lucide="book-open" class="h-5 w-5"></i>
            </div>

            <div>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $courseCount ?? 0 }}
                </p>

                <p class="text-sm text-slate-500">
                    Courses
                </p>

            </div>

        </div>

    </div>


    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center gap-4">

            <div class="rounded-xl bg-cyan-50 p-3 text-cyan-600">
                <i data-lucide="file-text" class="h-5 w-5"></i>
            </div>

            <div>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $pendingVouchers ?? 0 }}
                </p>

                <p class="text-sm text-slate-500">
                    Pending Vouchers
                </p>

            </div>

        </div>

    </div>


    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center gap-4">

            <div class="rounded-xl bg-violet-50 p-3 text-violet-600">
                <i data-lucide="landmark" class="h-5 w-5"></i>
            </div>

            <div>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $bankAccountCount ?? 0 }}
                </p>

                <p class="text-sm text-slate-500">
                    Bank Accounts
                </p>

            </div>

        </div>

    </div>


    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center gap-4">

            <div class="rounded-xl bg-orange-50 p-3 text-orange-600">
                <i data-lucide="calendar-check" class="h-5 w-5"></i>
            </div>

            <div>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $activeSessionCount ?? 0 }}
                </p>

                <p class="text-sm text-slate-500">
                    Active Admission Session
                </p>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     ADMISSION STATUS + QUICK ACTIONS
========================================================== --}}

<div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">

    {{-- Admission session --}}

    <div
        class="rounded-2xl border border-slate-200 bg-white
               p-6 shadow-sm xl:col-span-2"
    >

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-bold text-slate-900">
                    Admission Session
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Current admission window
                </p>

            </div>

            <i data-lucide="calendar-days"
               class="h-6 w-6 text-emerald-600"></i>

        </div>


        @if(isset($activeSession) && $activeSession)

            <div class="mt-6 rounded-xl bg-emerald-50 p-5">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                    <div>

                        <p class="text-sm font-semibold text-emerald-700">
                            {{ $activeSession->name }}
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            {{ $activeSession->session_code }}
                        </p>

                    </div>

                    <span
                        class="inline-flex w-fit items-center gap-2
                               rounded-full bg-emerald-100 px-3 py-1.5
                               text-xs font-semibold text-emerald-700"
                    >
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        OPEN
                    </span>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>

                        <p class="text-xs text-slate-500">
                            Opens
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $activeSession->opening_date->format('d M Y, h:i A') }}
                        </p>

                    </div>

                    <div>

                        <p class="text-xs text-slate-500">
                            Closes
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $activeSession->closing_date->format('d M Y, h:i A') }}
                        </p>

                    </div>

                </div>

            </div>

        @else

            <div class="mt-6 rounded-xl bg-amber-50 p-5">

                <div class="flex items-center gap-3">

                    <i data-lucide="alert-triangle"
                       class="h-6 w-6 text-amber-600"></i>

                    <div>

                        <p class="font-semibold text-amber-800">
                            No admission session is currently open.
                        </p>

                        <p class="mt-1 text-sm text-amber-700">
                            Open an admission session to start accepting applications.
                        </p>

                    </div>

                </div>

            </div>

        @endif

    </div>


    {{-- Quick actions --}}

    <div
        class="rounded-2xl border border-slate-200
               bg-white p-6 shadow-sm"
    >

        <h3 class="font-bold text-slate-900">
            Quick Actions
        </h3>

        <div class="mt-5 space-y-3">

            <a
                href="{{ route('admin.admission-sessions.create') }}"
                class="flex items-center gap-3 rounded-xl
                       border border-slate-200 p-3
                       transition hover:border-emerald-300
                       hover:bg-emerald-50"
            >
                <span class="rounded-lg bg-emerald-100 p-2 text-emerald-600">
                    <i data-lucide="calendar-plus" class="h-5 w-5"></i>
                </span>

                <span class="text-sm font-medium">
                    New Admission Session
                </span>
            </a>

            <a
                href="{{ route('admin.bank-accounts.create') }}"
                class="flex items-center gap-3 rounded-xl
                       border border-slate-200 p-3
                       transition hover:border-blue-300
                       hover:bg-blue-50"
            >
                <span class="rounded-lg bg-blue-100 p-2 text-blue-600">
                    <i data-lucide="landmark" class="h-5 w-5"></i>
                </span>

                <span class="text-sm font-medium">
                    Add Bank Account
                </span>
            </a>

            <a
                href="{{ route('admin.fee-configurations.create') }}"
                class="flex items-center gap-3 rounded-xl
                       border border-slate-200 p-3
                       transition hover:border-amber-300
                       hover:bg-amber-50"
            >
                <span class="rounded-lg bg-amber-100 p-2 text-amber-600">
                    <i data-lucide="receipt" class="h-5 w-5"></i>
                </span>

                <span class="text-sm font-medium">
                    Configure Fee
                </span>
            </a>

        </div>

    </div>

</div>


{{-- =========================================================
     RECENT ACTIVITY
========================================================== --}}

<div class="mt-8 rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="flex items-center justify-between border-b border-slate-100 p-6">

        <div>

            <h3 class="font-bold text-slate-900">
                System Overview
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Quick summary of GATTC administration
            </p>

        </div>

        <i data-lucide="activity"
           class="h-6 w-6 text-emerald-600"></i>

    </div>

    <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

        <div class="p-6">

            <p class="text-sm text-slate-500">
                Website
            </p>

            <p class="mt-2 font-semibold text-emerald-600">
                Operational
            </p>

        </div>

        <div class="p-6">

            <p class="text-sm text-slate-500">
                Admissions
            </p>

            <p class="mt-2 font-semibold text-slate-800">
                {{ $activeSessionCount ?? 0 }} active session
            </p>

        </div>

        <div class="p-6">

            <p class="text-sm text-slate-500">
                Finance
            </p>

            <p class="mt-2 font-semibold text-slate-800">
                Payment monitoring active
            </p>

        </div>

    </div>

</div>

@endsection