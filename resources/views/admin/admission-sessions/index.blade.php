@extends('layouts.admin')

@section('title', 'Admission Sessions')
@section('page-heading', 'Admission Sessions')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Support collections and paginated results
    |--------------------------------------------------------------------------
    */
    $sessionItems = method_exists($admissionSessions, 'getCollection')
        ? $admissionSessions->getCollection()
        : collect($admissionSessions);

    $totalSessionCount = $totalSessions
        ?? (method_exists($admissionSessions, 'total')
            ? $admissionSessions->total()
            : $sessionItems->count());

    $openSessionCount = $openSessionsCount
        ?? $sessionItems->filter(fn ($session) => (bool) $session->is_open)->count();

    $closedSessionCount = $closedSessionsCount
        ?? $sessionItems->filter(fn ($session) => !(bool) $session->is_open)->count();

    $assignedCourseCount = $assignedCoursesCount
        ?? $sessionItems->sum(
            fn ($session) => $session->courses_count
                ?? (isset($session->courses) ? $session->courses->count() : 0)
        );
@endphp


<div class="mx-auto max-w-7xl space-y-6">

    {{-- ============================================================
         HEADER
    ============================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-start gap-3">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                <i data-lucide="calendar-days" class="h-6 w-6"></i>
            </div>

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Admission Sessions
                </h1>

                <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">
                    Manage admission periods, available courses and online admission availability.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.admission-sessions.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-emerald-600 px-5 py-3 text-sm font-semibold text-white
                   shadow-sm transition hover:bg-emerald-700
                   focus:outline-none focus:ring-2 focus:ring-emerald-500
                   focus:ring-offset-2"
        >
            <i data-lucide="plus" class="h-4 w-4"></i>
            Create Session
        </a>

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

    @if ($errors->any())
        <div
            role="alert"
            class="rounded-xl border border-red-200 bg-red-50 p-4"
        >
            <h2 class="font-semibold text-red-900">
                Please review the following errors
            </h2>

            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ============================================================
         SUMMARY CARDS
    ============================================================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Sessions
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $totalSessionCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Admission periods created
                    </p>
                </div>

                <div class="rounded-xl bg-slate-100 p-3">
                    <i data-lucide="calendar-range" class="h-6 w-6 text-slate-600"></i>
                </div>

            </div>

        </div>


        {{-- OPEN FLAG --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Marked Open
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-600">
                        {{ $openSessionCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Sessions enabled by administration
                    </p>
                </div>

                <div class="rounded-xl bg-emerald-50 p-3">
                    <i data-lucide="calendar-check" class="h-6 w-6 text-emerald-600"></i>
                </div>

            </div>

        </div>


        {{-- CLOSED FLAG --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Marked Closed
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-700">
                        {{ $closedSessionCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Sessions disabled by administration
                    </p>
                </div>

                <div class="rounded-xl bg-slate-100 p-3">
                    <i data-lucide="calendar-x" class="h-6 w-6 text-slate-600"></i>
                </div>

            </div>

        </div>


        {{-- ASSIGNED COURSES --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Course Assignments
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-indigo-600">
                        {{ $assignedCourseCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Course-to-session assignments
                    </p>
                </div>

                <div class="rounded-xl bg-indigo-50 p-3">
                    <i data-lucide="book-open" class="h-6 w-6 text-indigo-600"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         SESSION LIST HEADER
    ============================================================= --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <h2 class="text-lg font-bold text-slate-900">
                All Admission Sessions
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Open a session to manage its courses, dates and admission settings.
            </p>
        </div>

        @if (method_exists($admissionSessions, 'total'))
            <p class="text-xs text-slate-500">
                Showing {{ $admissionSessions->firstItem() ?? 0 }}
                – {{ $admissionSessions->lastItem() ?? 0 }}
                of {{ $admissionSessions->total() }} sessions
            </p>
        @endif

    </div>


    {{-- ============================================================
         SESSION CARDS
    ============================================================= --}}
    <div class="space-y-5">

        @forelse ($admissionSessions as $session)

            @php
                $openingAt = $session->opening_date
                    ? \Illuminate\Support\Carbon::parse($session->opening_date)
                    : null;

                $closingAt = $session->closing_date
                    ? \Illuminate\Support\Carbon::parse($session->closing_date)
                    : null;

                /*
                 * Effective status combines the admin flag with the dates.
                 */
                if (!$session->is_open) {
                    $effectiveStatus = 'closed';
                } elseif ($openingAt && now()->lt($openingAt)) {
                    $effectiveStatus = 'scheduled';
                } elseif ($closingAt && now()->gt($closingAt)) {
                    $effectiveStatus = 'expired';
                } else {
                    $effectiveStatus = 'open';
                }

                $courseCount = $session->courses_count
                    ?? (isset($session->courses) ? $session->courses->count() : 0);

                $admissionCount = $session->admissions_count ?? 0;
            @endphp


            <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                {{-- CARD HEADER --}}
                <div class="border-b border-slate-100 p-5">

                    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                        {{-- TITLE AND STATUS --}}
                        <div class="flex min-w-0 items-start gap-4">

                            <div
                                @class([
                                    'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl',
                                    'bg-emerald-100 text-emerald-700' => $effectiveStatus === 'open',
                                    'bg-blue-100 text-blue-700' => $effectiveStatus === 'scheduled',
                                    'bg-amber-100 text-amber-700' => $effectiveStatus === 'expired',
                                    'bg-slate-100 text-slate-500' => $effectiveStatus === 'closed',
                                ])
                            >
                                <i data-lucide="calendar-days" class="h-6 w-6"></i>
                            </div>


                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="break-words text-lg font-bold text-slate-900">
                                        {{ $session->title }}
                                    </h3>


                                    @switch($effectiveStatus)

                                        @case('open')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                                OPEN
                                            </span>
                                            @break

                                        @case('scheduled')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800">
                                                <i data-lucide="clock" class="h-3.5 w-3.5"></i>
                                                SCHEDULED
                                            </span>
                                            @break

                                        @case('expired')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                                                <i data-lucide="calendar-clock" class="h-3.5 w-3.5"></i>
                                                EXPIRED
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                                CLOSED
                                            </span>

                                    @endswitch

                                </div>


                                <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-500">

                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="h-4 w-4 shrink-0"></i>

                                        <span>
                                            {{ $openingAt?->format('d M Y') ?? 'Not set' }}
                                            –
                                            {{ $closingAt?->format('d M Y') ?? 'Not set' }}
                                        </span>
                                    </span>


                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="book-open" class="h-4 w-4 shrink-0"></i>
                                        {{ $courseCount }} {{ \Illuminate\Support\Str::plural('course', $courseCount) }}
                                    </span>


                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="users" class="h-4 w-4 shrink-0"></i>
                                        {{ $admissionCount }} {{ \Illuminate\Support\Str::plural('admission', $admissionCount) }}
                                    </span>

                                </div>


                                @if ($effectiveStatus === 'scheduled')
                                    <p class="mt-3 text-xs leading-5 text-blue-700">
                                        This session is marked open by the administration, but its opening time has not arrived.
                                    </p>
                                @elseif ($effectiveStatus === 'expired')
                                    <p class="mt-3 text-xs leading-5 text-amber-700">
                                        The closing time has passed. Update the dates or close this session explicitly.
                                    </p>
                                @endif

                            </div>

                        </div>


                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-2 xl:justify-end">

                            <a
                                href="{{ route('admin.admission-sessions.show', $session) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg
                                       border border-slate-200 bg-white px-3 py-2 text-sm
                                       font-semibold text-slate-700 transition hover:bg-slate-50"
                            >
                                <i data-lucide="eye" class="h-4 w-4"></i>
                                View
                            </a>


                            <a
                                href="{{ route('admin.admission-sessions.edit', $session) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg
                                       bg-indigo-50 px-3 py-2 text-sm font-semibold
                                       text-indigo-700 transition hover:bg-indigo-100"
                            >
                                <i data-lucide="pencil" class="h-4 w-4"></i>
                                Edit
                            </a>


                            @if ($session->is_open)

                                <form
                                    method="POST"
                                    action="{{ route('admin.admission-sessions.close', $session) }}"
                                    onsubmit="return confirm('Close the admission session {{ addslashes($session->title) }}? Applicants will no longer be able to submit applications through this session.');"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg
                                               bg-amber-50 px-3 py-2 text-sm font-semibold
                                               text-amber-800 transition hover:bg-amber-100"
                                    >
                                        <i data-lucide="lock" class="h-4 w-4"></i>
                                        Close
                                    </button>
                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('admin.admission-sessions.open', $session) }}"
                                    onsubmit="return confirm('Open the admission session {{ addslashes($session->title) }}? Any other open admission session may be closed.');"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg
                                               bg-emerald-50 px-3 py-2 text-sm font-semibold
                                               text-emerald-800 transition hover:bg-emerald-100"
                                    >
                                        <i data-lucide="lock-open" class="h-4 w-4"></i>
                                        Open
                                    </button>
                                </form>

                            @endif


                            <form
                                method="POST"
                                action="{{ route('admin.admission-sessions.destroy', $session) }}"
                                onsubmit="return confirm('Are you sure you want to delete this admission session? This action may be restricted if related admissions or vouchers exist.');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg
                                           bg-red-50 px-3 py-2 text-sm font-semibold
                                           text-red-700 transition hover:bg-red-100"
                                >
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                @if ($session->description)

                    <div class="border-b border-slate-100 px-5 py-4">

                        <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-600">
                            {{ $session->description }}
                        </p>

                    </div>

                @endif


                {{-- SESSION SUMMARY FOOTER --}}
                <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                    <div class="p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Opens
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-800">
                            {{ $openingAt?->format('d M Y') ?? 'Not set' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $openingAt?->format('h:i A') ?? 'Time not set' }}
                        </p>

                    </div>


                    <div class="p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Closes
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-800">
                            {{ $closingAt?->format('d M Y') ?? 'Not set' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $closingAt?->format('h:i A') ?? 'Time not set' }}
                        </p>

                    </div>


                    <div class="p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Activity
                        </p>

                        <div class="mt-2 flex flex-wrap gap-2">

                            <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                {{ $courseCount }} courses
                            </span>

                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $admissionCount }} admissions
                            </span>

                        </div>

                    </div>

                </div>

            </article>

        @empty

            {{-- EMPTY STATE --}}
            <div class="rounded-2xl bg-white px-6 py-16 text-center shadow-sm ring-1 ring-slate-200">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <i data-lucide="calendar-x" class="h-8 w-8 text-slate-400"></i>
                </div>

                <h2 class="mt-5 text-lg font-bold text-slate-900">
                    No Admission Sessions Found
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    Create a session, define its opening and closing times, and assign
                    the training courses that should be available to applicants.
                </p>

                <a
                    href="{{ route('admin.admission-sessions.create') }}"
                    class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl
                           bg-emerald-600 px-5 py-3 text-sm font-semibold text-white
                           transition hover:bg-emerald-700"
                >
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Create First Session
                </a>

            </div>

        @endforelse

    </div>


    {{-- PAGINATION --}}
    @if (method_exists($admissionSessions, 'links'))
        <div class="pt-2">
            {{ $admissionSessions->links() }}
        </div>
    @endif

</div>

@endsection