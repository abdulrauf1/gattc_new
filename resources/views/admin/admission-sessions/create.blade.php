@extends('layouts.admin')

@section('title', 'New Admission Session')

@section('page-title', 'New Admission Session')

@section('content')

<div class="mx-auto max-w-3xl">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Create Admission Session
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Define the period during which applicants can apply.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('admin.admission-sessions.store') }}"
        class="space-y-6 rounded-2xl border border-slate-200
               bg-white p-6 shadow-sm"
    >

        @csrf


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Session Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="e.g. GATTC Admissions 2026"
                required
                class="w-full rounded-xl border-slate-300
                       focus:border-emerald-500
                       focus:ring-emerald-500"
            >

        </div>


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Session Code
            </label>

            <input
                type="text"
                name="session_code"
                value="{{ old('session_code') }}"
                placeholder="e.g. GATTC-2026-01"
                required
                class="w-full rounded-xl border-slate-300
                       focus:border-emerald-500
                       focus:ring-emerald-500"
            >

        </div>


        <div class="grid gap-5 md:grid-cols-2">

            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Opening Date & Time
                </label>

                <input
                    type="datetime-local"
                    name="opening_date"
                    value="{{ old('opening_date') }}"
                    required
                    class="w-full rounded-xl border-slate-300
                           focus:border-emerald-500
                           focus:ring-emerald-500"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Closing Date & Time
                </label>

                <input
                    type="datetime-local"
                    name="closing_date"
                    value="{{ old('closing_date') }}"
                    required
                    class="w-full rounded-xl border-slate-300
                           focus:border-emerald-500
                           focus:ring-emerald-500"
                >

            </div>

        </div>


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full rounded-xl border-slate-300
                       focus:border-emerald-500
                       focus:ring-emerald-500"
            >{{ old('description') }}</textarea>

        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">
                Available Courses
            </label>

            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($courses as $course)

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-indigo-400 hover:bg-indigo-50">

                        <input
                            type="checkbox"
                            name="courses[]"
                            value="{{ $course->id }}"
                            class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            {{ in_array($course->id, old('courses', [])) ? 'checked' : '' }}
                        >

                        <div>
                            <div class="font-semibold text-slate-900">
                                {{ $course->title }}
                            </div>

                            @if($course->code)
                                <div class="text-xs text-slate-500">
                                    {{ $course->code }}
                                </div>
                            @endif

                            @if($course->duration)
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $course->duration }}
                                </div>
                            @endif
                        </div>

                    </label>

                @endforeach

            </div>
        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.admission-sessions.index') }}"
                class="rounded-xl border border-slate-300
                       px-5 py-3 text-sm font-semibold
                       hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-emerald-600
                       px-5 py-3 text-sm font-semibold text-white
                       hover:bg-emerald-700"
            >
                Create Session
            </button>

        </div>

    </form>

</div>

@endsection