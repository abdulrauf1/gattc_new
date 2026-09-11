@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Edit Course
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update {{ $course->title }}.
            </p>
        </div>

        <a href="{{ route('admin.courses.index') }}"
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
        action="{{ route('admin.courses.update', $course) }}"
        enctype="multipart/form-data"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf
        @method('PUT')

        <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-2">

            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Category
                </label>

                <select
                    name="course_category_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                    <option value="">Select Category</option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('course_category_id', $course->course_category_id) == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Title *
                </label>

                <input
                    type="text"
                    name="title"
                    required
                    value="{{ old('title', $course->title) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Code
                </label>

                <input
                    type="text"
                    name="code"
                    value="{{ old('code', $course->code) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Duration
                </label>

                <input
                    type="text"
                    name="duration"
                    value="{{ old('duration', $course->duration) }}"
                    placeholder="e.g. 6 Months"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Required Qualification
                </label>

                <input
                    type="text"
                    name="qualification"
                    value="{{ old('qualification', $course->qualification) }}"
                    placeholder="e.g. Matric"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Fee
                </label>

                <input
                    type="number"
                    name="fee"
                    min="0"
                    step="0.01"
                    value="{{ old('fee', $course->fee) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >

            </div>


            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Short Description
                </label>

                <textarea
                    name="short_description"
                    rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >{{ old('short_description', $course->short_description) }}</textarea>

            </div>


            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Full Description
                </label>

                <textarea
                    name="description"
                    rows="6"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                >{{ old('description', $course->description) }}</textarea>

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full rounded-xl border border-slate-300 bg-white text-sm"
                >

                @if($course->image)

                    <p class="mt-2 text-xs text-slate-500">
                        Current image:
                        {{ $course->image }}
                    </p>

                @endif

            </div>


            <div class="space-y-3">

                <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <input
                        type="checkbox"
                        name="featured"
                        value="1"
                        {{ old('featured', $course->featured) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600"
                    >

                    <span class="text-sm font-semibold text-slate-800">
                        Featured Course
                    </span>

                </label>


                <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <input
                        type="checkbox"
                        name="status"
                        value="1"
                        {{ old('status', $course->status) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600"
                    >

                    <span class="text-sm font-semibold text-slate-800">
                        Active Course
                    </span>

                </label>

            </div>

        </div>


        <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

            <a href="{{ route('admin.courses.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white"
            >
                Update Course
            </button>

        </div>

    </form>

</div>

@endsection