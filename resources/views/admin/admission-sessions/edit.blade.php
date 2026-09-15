@extends('layouts.admin')

@section('title', 'Edit Admission Session')

@section('page-heading', 'Edit Admission Session')

@section('content')

<div>

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-900">
            Edit Admission Session
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Update the admission period and available courses.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('admin.admission-sessions.update', $admissionSession) }}"
    >

        @csrf
        @method('PUT')


        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


            {{-- Session details --}}

            <div class="lg:col-span-2">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                    <h2 class="mb-5 text-lg font-bold">
                        Session Information
                    </h2>


                    <div class="space-y-5">

                        <div>

                            <label class="mb-1.5 block text-sm font-semibold">
                                Session Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $admissionSession->title) }}"
                                required
                                class="w-full rounded-xl border-slate-300
                                       focus:border-emerald-500
                                       focus:ring-emerald-500"
                            >

                        </div>


                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>

                                <label class="mb-1.5 block text-sm font-semibold">
                                    Opening Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="opening_date"
                                    value="{{ old(
                                        'opening_date',
                                        $admissionSession->opening_date?->format('Y-m-d\TH:i')
                                    ) }}"
                                    required
                                    class="w-full rounded-xl border-slate-300
                                           focus:border-emerald-500
                                           focus:ring-emerald-500"
                                >

                            </div>


                            <div>

                                <label class="mb-1.5 block text-sm font-semibold">
                                    Closing Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="closing_date"
                                    value="{{ old(
                                        'closing_date',
                                        $admissionSession->closing_date?->format('Y-m-d\TH:i')
                                    ) }}"
                                    required
                                    class="w-full rounded-xl border-slate-300
                                           focus:border-emerald-500
                                           focus:ring-emerald-500"
                                >

                            </div>

                        </div>


                        <div>

                            <label class="mb-1.5 block text-sm font-semibold">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="w-full rounded-xl border-slate-300
                                       focus:border-emerald-500
                                       focus:ring-emerald-500"
                            >{{ old('description', $admissionSession->description) }}</textarea>

                        </div>


                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                            <label class="flex cursor-pointer items-start gap-3">

                                <input
                                    type="checkbox"
                                    name="is_open"
                                    value="1"
                                    {{ old('is_open', $admissionSession->is_open) ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300
                                           text-emerald-600
                                           focus:ring-emerald-500"
                                >

                                <div>

                                    <span class="block text-sm font-semibold text-emerald-900">
                                        Session is open
                                    </span>

                                    <span class="mt-1 block text-xs text-emerald-700">
                                        Only one admission session can be open at a time.
                                    </span>

                                </div>

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Courses --}}

            <div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                    <h2 class="text-lg font-bold">
                        Available Courses
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Select courses offered in this session.
                    </p>


                    <div class="mt-5 max-h-[520px] space-y-2 overflow-y-auto">

                        @foreach($courses as $course)

                            <label
                                class="flex cursor-pointer items-start gap-3
                                       rounded-xl border border-slate-200 p-3
                                       hover:border-emerald-300
                                       hover:bg-emerald-50/50"
                            >

                                <input
                                    type="checkbox"
                                    name="courses[]"
                                    value="{{ $course->id }}"
                                    {{ in_array($course->id, old('courses', $selectedCourses)) ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300
                                           text-emerald-600"
                                >

                                <div>

                                    <p class="text-sm font-semibold">
                                        {{ $course->title }}
                                    </p>

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

                                        · Rs. {{ number_format($course->fee_amount, 0) }}

                                    </p>

                                </div>

                            </label>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>


        <div class="mt-6 flex justify-end gap-3">

            <a
                href="{{ route('admin.admission-sessions.show', $admissionSession) }}"
                class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold
                       text-slate-700 ring-1 ring-slate-200"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl
                       bg-emerald-500 px-5 py-2.5 text-sm font-semibold
                       text-white hover:bg-emerald-600"
            >

                <i data-lucide="save" class="h-4 w-4"></i>

                Save Changes

            </button>

        </div>

    </form>

</div>

@endsection