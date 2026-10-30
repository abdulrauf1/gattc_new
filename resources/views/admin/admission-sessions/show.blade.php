@extends('layouts.admin')

@section('title', $admissionSession->title)
@section('page-heading', 'Admission Session Details')

@section('content')

@php
    $openingAt = $admissionSession->opening_date
        ? \Illuminate\Support\Carbon::parse($admissionSession->opening_date)
        : null;

    $closingAt = $admissionSession->closing_date
        ? \Illuminate\Support\Carbon::parse($admissionSession->closing_date)
        : null;

    if (!$admissionSession->is_open) {
        $effectiveStatus = 'closed';
    } elseif ($openingAt && now()->lt($openingAt)) {
        $effectiveStatus = 'scheduled';
    } elseif ($closingAt && now()->gt($closingAt)) {
        $effectiveStatus = 'expired';
    } else {
        $effectiveStatus = 'open';
    }

    $courses = $admissionSession->courses ?? collect();

    $admissionCount = $admissionCount
        ?? $admissionSession->admissions_count
        ?? 0;

    $voucherCount = $voucherCount ?? 0;
@endphp


<div class="mx-auto max-w-7xl space-y-6">

    {{-- ============================================================
         HEADER
    ============================================================= --}}
    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

        <div class="flex min-w-0 items-start gap-4">

            <div
                @class([
                    'flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl',
                    'bg-emerald-100 text-emerald-700' => $effectiveStatus === 'open',
                    'bg-blue-100 text-blue-700' => $effectiveStatus === 'scheduled',
                    'bg-amber-100 text-amber-700' => $effectiveStatus === 'expired',
                    'bg-slate-100 text-slate-600' => $effectiveStatus === 'closed',
                ])
            >
                <i data-lucide="calendar-days" class="h-7 w-7"></i>
            </div>


            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="break-words text-2xl font-bold tracking-tight text-slate-900">
                        {{ $admissionSession->title }}
                    </h1>


                    @switch($effectiveStatus)

                        @case('open')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                OPEN
                            </span>
                            @break

                        @case('scheduled')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">
                                <i data-lucide="clock" class="h-3.5 w-3.5"></i>
                                SCHEDULED
                            </span>
                            @break

                        @case('expired')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                <i data-lucide="calendar-clock" class="h-3.5 w-3.5"></i>
                                EXPIRED
                            </span>
                            @break

                        @default
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                CLOSED
                            </span>

                    @endswitch

                </div>


                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Review session settings, assigned programmes, admission activity and generated vouchers.
                </p>


                @if ($effectiveStatus === 'scheduled')
                    <p class="mt-3 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs leading-5 text-blue-800">
                        The session is marked open, but its opening date and time have not arrived yet.
                    </p>
                @elseif ($effectiveStatus === 'expired')
                    <p class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs leading-5 text-amber-800">
                        The session closing time has passed. New public applications will not be available unless the period is updated.
                    </p>
                @endif

            </div>

        </div>


        {{-- HEADER ACTIONS --}}
        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('admin.admission-sessions.edit', $admissionSession) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-indigo-50 px-4 py-2.5 text-sm font-semibold
                       text-indigo-700 transition hover:bg-indigo-100"
            >
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Edit Session
            </a>


            @if ($admissionSession->is_open)

                <form
                    method="POST"
                    action="{{ route('admin.admission-sessions.close', $admissionSession) }}"
                    onsubmit="return confirm('Close this admission session? Applicants will no longer be able to submit applications through it.');"
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl
                               bg-amber-50 px-4 py-2.5 text-sm font-semibold
                               text-amber-800 transition hover:bg-amber-100"
                    >
                        <i data-lucide="lock" class="h-4 w-4"></i>
                        Close Session
                    </button>
                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.admission-sessions.open', $admissionSession) }}"
                    onsubmit="return confirm('Open this admission session? Any other open admission session may be closed.');"
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl
                               bg-emerald-50 px-4 py-2.5 text-sm font-semibold
                               text-emerald-800 transition hover:bg-emerald-100"
                    >
                        <i data-lucide="lock-open" class="h-4 w-4"></i>
                        Open Session
                    </button>
                </form>

            @endif


            <a
                href="{{ route('admin.admission-sessions.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       border border-slate-200 bg-white px-4 py-2.5 text-sm
                       font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                All Sessions
            </a>

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================= --}}
    @if (session('success'))
        <div
            role="status"
            class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4"
        >
            <i data-lucide="circle-check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-700"></i>

            <p class="text-sm font-medium text-emerald-900">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div
            role="alert"
            class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4"
        >
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0 text-red-700"></i>

            <p class="text-sm font-medium text-red-900">
                {{ session('error') }}
            </p>
        </div>
    @endif


    {{-- ============================================================
         SUMMARY CARDS
    ============================================================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- STATUS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Session Status
                    </p>

                    <p
                        @class([
                            'mt-2 text-xl font-bold',
                            'text-emerald-700' => $effectiveStatus === 'open',
                            'text-blue-700' => $effectiveStatus === 'scheduled',
                            'text-amber-700' => $effectiveStatus === 'expired',
                            'text-slate-700' => $effectiveStatus === 'closed',
                        ])
                    >
                        {{ ucfirst($effectiveStatus) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Administration and date status
                    </p>
                </div>

                <div class="rounded-xl bg-slate-100 p-3">
                    <i data-lucide="activity" class="h-6 w-6 text-slate-600"></i>
                </div>

            </div>

        </div>


        {{-- COURSES --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Assigned Courses
                    </p>

                    <p class="mt-2 text-3xl font-bold text-indigo-600">
                        {{ $courses->count() }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Programmes in this session
                    </p>
                </div>

                <div class="rounded-xl bg-indigo-50 p-3">
                    <i data-lucide="book-open" class="h-6 w-6 text-indigo-600"></i>
                </div>

            </div>

        </div>


        {{-- ADMISSIONS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Admissions
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ $admissionCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Applications linked to this session
                    </p>
                </div>

                <div class="rounded-xl bg-emerald-50 p-3">
                    <i data-lucide="users" class="h-6 w-6 text-emerald-600"></i>
                </div>

            </div>

        </div>


        {{-- VOUCHERS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Vouchers
                    </p>

                    <p class="mt-2 text-3xl font-bold text-amber-600">
                        {{ $voucherCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Fee vouchers associated with this session
                    </p>
                </div>

                <div class="rounded-xl bg-amber-50 p-3">
                    <i data-lucide="receipt-text" class="h-6 w-6 text-amber-600"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         MAIN DETAILS
    ============================================================= --}}
    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-3">


        {{-- SESSION INFORMATION --}}
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            <div class="border-b border-slate-100 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                        <i data-lucide="calendar-range" class="h-5 w-5 text-slate-700"></i>
                    </div>

                    <div>
                        <h2 class="font-bold text-slate-900">
                            Session Information
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Dates and configuration
                        </p>
                    </div>

                </div>

            </div>


            <div class="space-y-5 p-6">

                {{-- TITLE --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Session Title
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-slate-900">
                        {{ $admissionSession->title }}
                    </p>
                </div>


                {{-- OPENING --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Opening Date &amp; Time
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $openingAt?->format('d M Y') ?? 'Not set' }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $openingAt?->format('h:i A') ?? 'Time not set' }}
                    </p>
                </div>


                {{-- CLOSING --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Closing Date &amp; Time
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $closingAt?->format('d M Y') ?? 'Not set' }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $closingAt?->format('h:i A') ?? 'Time not set' }}
                    </p>
                </div>


                {{-- DURATION --}}
                @if ($openingAt && $closingAt && $closingAt->greaterThan($openingAt))

                    <div class="rounded-xl bg-slate-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Admission Period
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $openingAt->diffInDays($closingAt) }} days
                        </p>

                    </div>

                @endif


                {{-- DESCRIPTION --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Description
                    </p>

                    @if ($admissionSession->description)

                        <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-slate-600">
                            {{ $admissionSession->description }}
                        </p>

                    @else

                        <p class="mt-2 text-sm italic text-slate-400">
                            No description has been added.
                        </p>

                    @endif

                </div>


                {{-- CREATED / UPDATED --}}
                @if ($admissionSession->created_at)

                    <div class="border-t border-slate-100 pt-4">

                        <p class="text-xs text-slate-400">
                            Created
                        </p>

                        <p class="mt-1 text-xs font-medium text-slate-600">
                            {{ $admissionSession->created_at->format('d M Y, h:i A') }}
                        </p>

                        @if ($admissionSession->updated_at)

                            <p class="mt-3 text-xs text-slate-400">
                                Last Updated
                            </p>

                            <p class="mt-1 text-xs font-medium text-slate-600">
                                {{ $admissionSession->updated_at->format('d M Y, h:i A') }}
                            </p>

                        @endif

                    </div>

                @endif

            </div>

        </section>


        {{-- ASSIGNED COURSES --}}
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 xl:col-span-2">

            <div class="border-b border-slate-100 px-6 py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="font-bold text-slate-900">
                            Courses Offered
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Active courses associated with this admission session.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-800">
                        {{ $courses->count() }} assigned
                    </span>

                </div>

            </div>


            <div class="p-6">

                @if ($courses->isNotEmpty())

                    {{-- COURSE TYPE SUMMARY --}}
                    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">

                        <div class="rounded-xl bg-emerald-50 p-3">
                            <p class="text-xs font-medium text-emerald-700">
                                Regular / Morning
                            </p>

                            <p class="mt-1 text-xl font-bold text-emerald-800">
                                {{ $courses->where('course_type', 'regular')->count() }}
                            </p>
                        </div>


                        <div class="rounded-xl bg-blue-50 p-3">
                            <p class="text-xs font-medium text-blue-700">
                                DIT / Second Shift
                            </p>

                            <p class="mt-1 text-xl font-bold text-blue-800">
                                {{ $courses->where('course_type', 'dit')->count() }}
                            </p>
                        </div>


                        <div class="rounded-xl bg-purple-50 p-3">
                            <p class="text-xs font-medium text-purple-700">
                                Private / IMC
                            </p>

                            <p class="mt-1 text-xl font-bold text-purple-800">
                                {{ $courses->where('course_type', 'private')->count() }}
                            </p>
                        </div>

                    </div>


                    {{-- COURSE LIST --}}
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                        @foreach ($courses->sortBy('title') as $course)

                            <article class="rounded-xl border border-slate-200 p-4 transition hover:border-emerald-200 hover:shadow-sm">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <h3 class="break-words font-semibold text-slate-900">
                                            {{ $course->title }}
                                        </h3>

                                        <div class="mt-2">

                                            @switch($course->course_type)

                                                @case('dit')
                                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
                                                        DIT / Second Shift
                                                    </span>
                                                    @break

                                                @case('private')
                                                    <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-800">
                                                        Private / IMC
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                                        Regular / Morning
                                                    </span>

                                            @endswitch

                                        </div>

                                    </div>


                                    <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-bold text-slate-800">
                                        Rs. {{ number_format((float) $course->fee_amount, 2) }}
                                    </span>

                                </div>


                                <div class="mt-4 space-y-2 border-t border-slate-100 pt-3 text-xs text-slate-500">

                                    <p class="flex items-start gap-2">
                                        <i data-lucide="clock" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>

                                        <span>
                                            {{ $course->duration ?: 'Duration not specified' }}
                                        </span>
                                    </p>


                                    <p class="flex items-start gap-2">
                                        <i data-lucide="graduation-cap" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>

                                        <span>
                                            {{ $course->eligibility ?: 'Eligibility not specified' }}
                                        </span>
                                    </p>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200">
                            <i data-lucide="book-open" class="h-7 w-7 text-slate-400"></i>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-800">
                            No Courses Assigned
                        </h3>

                        <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                            This session currently has no assigned courses. Edit the session to select the programmes available to applicants.
                        </p>

                        <a
                            href="{{ route('admin.admission-sessions.edit', $admissionSession) }}"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl
                                   bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white
                                   transition hover:bg-indigo-700"
                        >
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                            Assign Courses
                        </a>

                    </div>

                @endif

            </div>

        </section>

    </div>

</div>

@endsection