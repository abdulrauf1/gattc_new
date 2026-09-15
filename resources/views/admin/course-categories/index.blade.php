@extends('layouts.admin')

@section('title', 'Course Categories')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold">
                Course Categories
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage course classifications.
            </p>
        </div>

        <a href="{{ route('admin.course-categories.create') }}"
           class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">
            + Add Category
        </a>

    </div>


    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

            <tr>

                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                    Name
                </th>

                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                    Slug
                </th>

                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">
                    Status
                </th>

                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">
                    Actions
                </th>

            </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

            @forelse($categories as $category)

                <tr>

                    <td class="px-5 py-4 font-semibold">
                        {{ $category->name }}
                    </td>

                    <td class="px-5 py-4 text-sm text-slate-500">
                        {{ $category->slug }}
                    </td>

                    <td class="px-5 py-4">

                        @if($category->status)

                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                Active
                            </span>

                        @else

                            <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td class="px-5 py-4 text-right">

                        <a href="{{ route('admin.course-categories.edit', $category) }}"
                           class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.course-categories.destroy', $category) }}"
                              class="inline"
                              onsubmit="return confirm('Delete this category?');">

                            @csrf
                            @method('DELETE')

                            <button class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4"
                        class="px-5 py-10 text-center text-slate-500">
                        No categories found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection