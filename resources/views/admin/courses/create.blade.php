@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Add Course
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Add a technical or vocational course offered by GATTC.
            </p>
        </div>

        <a href="{{ route('admin.courses.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">

            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back
        </a>

    </div>


    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.courses.store') }}"
        enctype="multipart/form-data"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf

        <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-2">

            {{-- Category --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Category
                </label>

                <select
                    name="course_category_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                    <option value="">
                        Select Category
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('course_category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Course title --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Title
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    placeholder="e.g. Computer Operator"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Code --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Code
                </label>

                <input
                    type="text"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="e.g. CO-L2"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm uppercase focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Duration --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Duration
                </label>

                <input
                    type="text"
                    name="duration"
                    value="{{ old('duration') }}"
                    placeholder="e.g. 6 Months"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Qualification --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Required Qualification
                </label>

                <input
                    type="text"
                    name="qualification"
                    value="{{ old('qualification') }}"
                    placeholder="e.g. Matric"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Fee --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Fee
                </label>

                <input
                    type="number"
                    name="fee"
                    value="{{ old('fee') }}"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Short description --}}
            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Short Description
                </label>

                <textarea
                    name="short_description"
                    rows="3"
                    placeholder="Short description displayed on course cards..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('short_description') }}</textarea>

            </div>


            {{-- Description --}}
            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Full Description
                </label>

                <textarea
                    name="description"
                    rows="6"
                    placeholder="Detailed course description..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('description') }}</textarea>

            </div>


            {{-- Image --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Course Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full rounded-xl border border-slate-300 bg-white text-sm file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm"
                >

                <p class="mt-1 text-xs text-slate-500">
                    Maximum size: 2 MB.
                </p>

            </div>


            {{-- Options --}}
            <div class="space-y-3">

                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <input
                        type="checkbox"
                        name="featured"
                        value="1"
                        {{ old('featured') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Featured Course
                        </span>

                        <span class="block text-xs text-slate-500">
                            Show this course prominently on the website.
                        </span>
                    </span>

                </label>


                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <input
                        type="checkbox"
                        name="status"
                        value="1"
                        checked
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Active Course
                        </span>

                        <span class="block text-xs text-slate-500">
                            Active courses can be offered in admission sessions.
                        </span>
                    </span>

                </label>

            </div>

        </div>


        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">

            <a href="{{ route('admin.courses.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white"
            >
                <i data-lucide="save" class="h-4 w-4"></i>
                Save Course
            </button>

        </div>

    </form>

</div>

@endsection