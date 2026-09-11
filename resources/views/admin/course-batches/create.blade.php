@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Add Course Batch
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Create a new batch for a GATTC course.
            </p>
        </div>

        <a href="{{ route('admin.course-batches.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">
            Back
        </a>

    </div>


    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <ul class="list-disc pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.course-batches.store') }}"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf

        <div class="grid gap-6 p-6 sm:p-8 md:grid-cols-2">

            {{-- Course --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="course_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                    <option value="">
                        Select Course
                    </option>

                    @foreach($courses as $course)

                        <option
                            value="{{ $course->id }}"
                            {{ old('course_id') == $course->id ? 'selected' : '' }}
                        >
                            {{ $course->title }}
                            @if($course->code)
                                ({{ $course->code }})
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Batch --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Batch Name
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="batch_name"
                    value="{{ old('batch_name') }}"
                    required
                    placeholder="e.g. Batch 01 - 2026"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            {{-- Capacity --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Capacity
                </label>

                <input
                    type="number"
                    name="capacity"
                    value="{{ old('capacity') }}"
                    min="1"
                    placeholder="e.g. 30"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            {{-- Start --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Start Date
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            {{-- End --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    End Date
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ old('end_date') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            {{-- Status --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Batch Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                    <option value="upcoming">
                        Upcoming
                    </option>

                    <option value="open"
                        {{ old('status') === 'open' ? 'selected' : '' }}>
                        Open
                    </option>

                    <option value="ongoing"
                        {{ old('status') === 'ongoing' ? 'selected' : '' }}>
                        Ongoing
                    </option>

                    <option value="completed"
                        {{ old('status') === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="cancelled"
                        {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                </select>

            </div>

        </div>


        <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

            <a
                href="{{ route('admin.course-batches.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white"
            >
                Save Batch
            </button>

        </div>

    </form>

</div>

@endsection