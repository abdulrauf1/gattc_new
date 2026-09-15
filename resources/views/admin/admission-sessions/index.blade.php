@extends('layouts.admin')

@section('title', 'Admission Sessions')

@section('page-heading', 'Admission Sessions')

@section('content')

<div>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <i data-lucide="calendar-days" class="h-5 w-5"></i>
                </div>

                <div>

                    <h1 class="text-2xl font-bold text-slate-900">
                        Admission Sessions
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Manage the twice-yearly GATTC admission periods.
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('admin.admission-sessions.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-emerald-500 px-4 py-2.5 text-sm font-semibold
                   text-white shadow-sm transition hover:bg-emerald-600"
        >

            <i data-lucide="plus" class="h-5 w-5"></i>

            Create Session

        </a>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    @php
        $openSessions = $admissionSessions->where('is_open', true)->count();
        $closedSessions = $admissionSessions->where('is_open', false)->count();
        $totalCourses = $admissionSessions->sum('courses_count');
    @endphp


    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Sessions
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $admissionSessions->count() }}
                    </p>

                </div>

                <div class="rounded-xl bg-slate-100 p-3">
                    <i data-lucide="calendar-range" class="h-6 w-6 text-slate-600"></i>
                </div>

            </div>

        </div>


        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Open Sessions
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ $openSessions }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-100 p-3">
                    <i data-lucide="circle-check" class="h-6 w-6 text-emerald-600"></i>
                </div>

            </div>

        </div>


        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Courses Assigned
                    </p>

                    <p class="mt-2 text-3xl font-bold text-indigo-600">
                        {{ $totalCourses }}
                    </p>

                </div>

                <div class="rounded-xl bg-indigo-100 p-3">
                    <i data-lucide="book-open" class="h-6 w-6 text-indigo-600"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SESSIONS
    ========================================================== --}}

    <div class="space-y-5">

        @forelse($admissionSessions as $session)

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                {{-- Top --}}

                <div class="border-b border-slate-100 p-5">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <div class="flex items-start gap-4">

                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center
                                       rounded-xl
                                       {{ $session->is_open
                                            ? 'bg-emerald-100 text-emerald-600'
                                            : 'bg-slate-100 text-slate-500' }}"
                            >

                                <i data-lucide="calendar-days" class="h-6 w-6"></i>

                            </div>


                            <div>

                                <div class="flex flex-wrap items-center gap-2">

                                    <h2 class="text-lg font-bold text-slate-900">
                                        {{ $session->title }}
                                    </h2>


                                    @if($session->is_open)

                                        <span
                                            class="inline-flex items-center gap-1 rounded-full
                                                   bg-emerald-100 px-2.5 py-1
                                                   text-xs font-semibold text-emerald-700"
                                        >
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            OPEN
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-full
                                                   bg-slate-100 px-2.5 py-1
                                                   text-xs font-semibold text-slate-600"
                                        >
                                            CLOSED
                                        </span>

                                    @endif

                                </div>


                                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">

                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="h-4 w-4"></i>
                                        {{ $session->opening_date?->format('d M Y') }}
                                        —
                                        {{ $session->closing_date?->format('d M Y') }}
                                    </span>


                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="book-open" class="h-4 w-4"></i>
                                        {{ $session->courses_count }} courses
                                    </span>


                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="users" class="h-4 w-4"></i>
                                        {{ $session->admissions_count }} admissions
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Actions --}}

                        <div class="flex flex-wrap gap-2">

                            <a
                                href="{{ route('admin.admission-sessions.show', $session) }}"
                                class="inline-flex items-center gap-2 rounded-lg
                                       bg-slate-100 px-3 py-2 text-sm font-semibold
                                       text-slate-700 hover:bg-slate-200"
                            >
                                <i data-lucide="eye" class="h-4 w-4"></i>
                                View
                            </a>


                            <a
                                href="{{ route('admin.admission-sessions.edit', $session) }}"
                                class="inline-flex items-center gap-2 rounded-lg
                                       bg-indigo-50 px-3 py-2 text-sm font-semibold
                                       text-indigo-700 hover:bg-indigo-100"
                            >
                                <i data-lucide="pencil" class="h-4 w-4"></i>
                                Edit
                            </a>


                            @if($session->is_open)

                                <form
                                    method="POST"
                                    action="{{ route('admin.admission-sessions.close', $session) }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg
                                               bg-amber-50 px-3 py-2 text-sm font-semibold
                                               text-amber-700 hover:bg-amber-100"
                                    >

                                        <i data-lucide="lock" class="h-4 w-4"></i>

                                        Close

                                    </button>

                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('admin.admission-sessions.open', $session) }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg
                                               bg-emerald-50 px-3 py-2 text-sm font-semibold
                                               text-emerald-700 hover:bg-emerald-100"
                                    >

                                        <i data-lucide="lock-open" class="h-4 w-4"></i>

                                        Open

                                    </button>

                                </form>

                            @endif


                            <form
                                method="POST"
                                action="{{ route('admin.admission-sessions.destroy', $session) }}"
                                onsubmit="return confirm('Are you sure you want to delete this admission session?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg
                                           bg-red-50 px-3 py-2 text-sm font-semibold
                                           text-red-700 hover:bg-red-100"
                                >

                                    <i data-lucide="trash-2" class="h-4 w-4"></i>

                                    Delete

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- Description --}}

                @if($session->description)

                    <div class="border-b border-slate-100 px-5 py-4">

                        <p class="text-sm leading-6 text-slate-600">
                            {{ $session->description }}
                        </p>

                    </div>

                @endif


                {{-- Footer stats --}}

                <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                    <div class="p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Admission Window
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-700">

                            {{ $session->opening_date?->format('d M Y, h:i A') }}

                        </p>

                        <p class="mt-1 text-xs text-slate-500">

                            to

                            {{ $session->closing_date?->format('d M Y, h:i A') }}

                        </p>

                    </div>


                    <div class="p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Courses
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $session->courses_count }} available
                        </p>

                    </div>


                    <div class="p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Admissions
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $session->admissions_count }} applications
                        </p>

                    </div>

                </div>

            </div>

        @empty

            <div class="rounded-2xl bg-white px-6 py-16 text-center shadow-sm ring-1 ring-slate-200">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">

                    <i data-lucide="calendar-x" class="h-7 w-7 text-slate-400"></i>

                </div>

                <h2 class="mt-4 text-lg font-bold text-slate-800">
                    No Admission Sessions
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    Create an admission session and assign the courses that should be available to applicants.
                </p>

                <a
                    href="{{ route('admin.admission-sessions.create') }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl
                           bg-emerald-500 px-4 py-2.5 text-sm font-semibold
                           text-white hover:bg-emerald-600"
                >
                    <i data-lucide="plus" class="h-5 w-5"></i>
                    Create First Session
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection