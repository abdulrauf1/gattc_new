@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Edit Course Batch
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update batch information.
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
        action="{{ route('admin.course-batches.update', $courseBatch) }}"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf
        @method('PUT')

        <div class="grid gap-6 p-6 sm:p-8 md:grid-cols-2">

            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course *
                </label>

                <select
                    name="course_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                    @foreach($courses as $course)

                        <option
                            value="{{ $course->id }}"
                            {{ old('course_id', $courseBatch->course_id) == $course->id ? 'selected' : '' }}
                        >
                            {{ $course->title }}
                            @if($course->code)
                                ({{ $course->code }})
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Batch Name *
                </label>

                <input
                    type="text"
                    name="batch_name"
                    required
                    value="{{ old('batch_name', $courseBatch->batch_name) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Capacity
                </label>

                <input
                    type="number"
                    name="capacity"
                    min="1"
                    value="{{ old('capacity', $courseBatch->capacity) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Start Date
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', optional($courseBatch->start_date)->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    End Date
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ old('end_date', optional($courseBatch->end_date)->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                    @foreach([
                        'upcoming' => 'Upcoming',
                        'open' => 'Open',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            {{ old('status', $courseBatch->status) === $value ? 'selected' : '' }}
                        >
                            {{ $label }}
                        </option>

                    @endforeach

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
                Update Batch
            </button>

        </div>

    </form>

</div>

@endsection