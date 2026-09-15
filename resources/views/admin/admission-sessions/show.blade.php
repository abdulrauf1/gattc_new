@extends('layouts.admin')

@section('title', $admissionSession->title)

@section('page-heading', 'Admission Session Details')

@section('content')

<div>

    {{-- Header --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-bold text-slate-900">
                    {{ $admissionSession->title }}
                </h1>

                @if($admissionSession->is_open)

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                        OPEN
                    </span>

                @else

                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                        CLOSED
                    </span>

                @endif

            </div>

            <p class="mt-1 text-sm text-slate-500">
                Admission session details and assigned courses.
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('admin.admission-sessions.edit', $admissionSession) }}"
                class="inline-flex items-center gap-2 rounded-xl
                       bg-indigo-50 px-4 py-2.5 text-sm font-semibold
                       text-indigo-700"
            >

                <i data-lucide="pencil" class="h-4 w-4"></i>

                Edit

            </a>

            <a
                href="{{ route('admin.admission-sessions.index') }}"
                class="inline-flex items-center gap-2 rounded-xl
                       bg-white px-4 py-2.5 text-sm font-semibold
                       text-slate-700 ring-1 ring-slate-200"
            >

                <i data-lucide="arrow-left" class="h-4 w-4"></i>

                Back

            </a>

        </div>

    </div>


    {{-- Summary cards --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <p class="text-sm text-slate-500">
                Assigned Courses
            </p>

            <p class="mt-2 text-3xl font-bold text-indigo-600">
                {{ $admissionSession->courses->count() }}
            </p>

        </div>


        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <p class="text-sm text-slate-500">
                Applications
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-600">
                {{ $admissionCount }}
            </p>

        </div>


        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <p class="text-sm text-slate-500">
                Vouchers
            </p>

            <p class="mt-2 text-3xl font-bold text-amber-600">
                {{ $voucherCount }}
            </p>

        </div>

    </div>


    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- Session Information --}}

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="text-lg font-bold">
                Session Information
            </h2>


            <div class="mt-5 space-y-4 text-sm">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Title
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admissionSession->title }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Opening
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admissionSession->opening_date?->format('d M Y, h:i A') }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Closing
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admissionSession->closing_date?->format('d M Y, h:i A') }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Status
                    </p>

                    <p class="mt-1 font-semibold
                        {{ $admissionSession->is_open ? 'text-emerald-600' : 'text-slate-600' }}">
                        {{ $admissionSession->is_open ? 'Open' : 'Closed' }}
                    </p>

                </div>


                @if($admissionSession->description)

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Description
                        </p>

                        <p class="mt-1 leading-6 text-slate-600">
                            {{ $admissionSession->description }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Courses --}}

        <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-bold">
                        Courses Offered
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Courses available for this admission session.
                    </p>

                </div>

                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                    {{ $admissionSession->courses->count() }}
                </span>

            </div>


            <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2">

                @forelse($admissionSession->courses as $course)

                    <div class="rounded-xl border border-slate-200 p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    {{ $course->title }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">

                                    @switch($course->course_type)

                                        @case('dit')
                                            DIT / 2nd Shift
                                            @break

                                        @case('private')
                                            Private / IMC
                                            @break

                                        @default
                                            Regular

                                    @endswitch

                                </p>

                            </div>


                            <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                                Rs. {{ number_format($course->fee_amount, 0) }}
                            </span>

                        </div>


                        <div class="mt-3 text-xs text-slate-500">

                            {{ $course->duration ?? 'Duration not specified' }}

                            @if($course->eligibility)
                                · {{ $course->eligibility }}
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="rounded-xl bg-slate-50 p-8 text-center md:col-span-2">

                        <p class="text-sm text-slate-500">
                            No courses have been assigned to this session.
                        </p>

                        <a
                            href="{{ route('admin.admission-sessions.edit', $admissionSession) }}"
                            class="mt-3 inline-block text-sm font-semibold text-indigo-600"
                        >
                            Assign Courses
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection