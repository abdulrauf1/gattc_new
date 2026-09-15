@extends('layouts.admin')

@section('title', 'Create Admission Session')

@section('page-heading', 'Create Admission Session')

@section('content')

<div>

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-900">
            Create Admission Session
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Define the admission period and choose the courses available to applicants.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('admin.admission-sessions.store') }}"
    >

        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


            {{-- =================================================
                 SESSION INFORMATION
            ================================================== --}}

            <div class="lg:col-span-2">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                    <h2 class="mb-5 text-lg font-bold text-slate-900">
                        Session Information
                    </h2>


                    <div class="space-y-5">

                        <div>

                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Session Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="e.g. Fall Admissions 2026"
                                required
                                class="w-full rounded-xl border-slate-300
                                       focus:border-emerald-500 focus:ring-emerald-500"
                            >

                        </div>


                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>

                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Opening Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="opening_date"
                                    value="{{ old('opening_date') }}"
                                    required
                                    class="w-full rounded-xl border-slate-300
                                           focus:border-emerald-500 focus:ring-emerald-500"
                                >

                            </div>


                            <div>

                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Closing Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="closing_date"
                                    value="{{ old('closing_date') }}"
                                    required
                                    class="w-full rounded-xl border-slate-300
                                           focus:border-emerald-500 focus:ring-emerald-500"
                                >

                            </div>

                        </div>


                        <div>

                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                placeholder="Describe this admission session..."
                                class="w-full rounded-xl border-slate-300
                                       focus:border-emerald-500 focus:ring-emerald-500"
                            >{{ old('description') }}</textarea>

                        </div>


                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                            <label class="flex cursor-pointer items-start gap-3">

                                <input
                                    type="checkbox"
                                    name="is_open"
                                    value="1"
                                    {{ old('is_open') ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300
                                           text-emerald-600
                                           focus:ring-emerald-500"
                                >

                                <div>

                                    <span class="block text-sm font-semibold text-emerald-900">
                                        Open this session immediately
                                    </span>

                                    <span class="mt-1 block text-xs text-emerald-700">
                                        Opening this session will automatically close any other open admission session.
                                    </span>

                                </div>

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 COURSES
            ================================================== --}}

            <div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h2 class="text-lg font-bold text-slate-900">
                                Available Courses
                            </h2>

                            <p class="mt-1 text-xs text-slate-500">
                                Select courses for this session.
                            </p>

                        </div>

                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            {{ $courses->count() }}
                        </span>

                    </div>


                    <div class="mt-5 max-h-[520px] space-y-2 overflow-y-auto">

                        @forelse($courses as $course)

                            <label
                                class="flex cursor-pointer items-start gap-3
                                       rounded-xl border border-slate-200 p-3
                                       transition hover:border-emerald-300
                                       hover:bg-emerald-50/50"
                            >

                                <input
                                    type="checkbox"
                                    name="courses[]"
                                    value="{{ $course->id }}"
                                    {{ in_array($course->id, old('courses', [])) ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300
                                           text-emerald-600
                                           focus:ring-emerald-500"
                                >


                                <div class="min-w-0">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $course->title }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-slate-500">

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

                                        ·
                                        Rs. {{ number_format($course->fee_amount, 0) }}

                                    </p>

                                </div>

                            </label>

                        @empty

                            <div class="rounded-xl bg-slate-50 p-5 text-center">

                                <p class="text-sm text-slate-500">
                                    No active courses available.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="mt-6 flex justify-end gap-3">

            <a
                href="{{ route('admin.admission-sessions.index') }}"
                class="rounded-xl bg-white px-5 py-2.5 text-sm
                       font-semibold text-slate-700
                       ring-1 ring-slate-200 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl
                       bg-emerald-500 px-5 py-2.5 text-sm font-semibold
                       text-white shadow-sm hover:bg-emerald-600"
            >

                <i data-lucide="save" class="h-4 w-4"></i>

                Create Session

            </button>

        </div>

    </form>

</div>

@endsection