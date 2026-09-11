@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Edit Course Category
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update course category information.
            </p>
        </div>

        <a href="{{ route('admin.course-categories.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">

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
        action="{{ route('admin.course-categories.update', $courseCategory) }}"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf
        @method('PUT')

        <div class="space-y-6 p-6 sm:p-8">

            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Category Name
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $courseCategory->name) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('description', $courseCategory->description) }}</textarea>

            </div>


            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="status"
                        value="1"
                        {{ old('status', $courseCategory->status) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Active Category
                        </span>

                        <span class="block text-xs text-slate-500">
                            Allow this category to be used for courses.
                        </span>
                    </span>

                </label>

            </div>

        </div>


        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">

            <a href="{{ route('admin.course-categories.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white"
            >

                <i data-lucide="save" class="h-4 w-4"></i>

                Update Category

            </button>

        </div>

    </form>

</div>

@endsection