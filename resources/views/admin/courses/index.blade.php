@extends('layouts.admin')

@section('title', 'Courses')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Courses
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage GATTC technical and vocational courses.
            </p>
        </div>

        <a href="{{ route('admin.courses.create') }}"
           class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
            + Add Course
        </a>

    </div>


    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Course
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Category
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Type
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Fee
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Bank Account
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                        Status
                    </th>

                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                        Actions
                    </th>

                </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                @forelse($courses as $course)

                    <tr class="hover:bg-slate-50">

                        <td class="px-5 py-4">

                            <div class="font-semibold text-slate-800">
                                {{ $course->title }}
                            </div>

                            <div class="text-xs text-slate-500">
                                {{ $course->slug }}
                            </div>

                        </td>


                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $course->category?->name ?? 'N/A' }}
                        </td>


                        <td class="px-5 py-4">

                            @php
                                $type = match($course->course_type) {
                                    'dit' => 'DIT / 2nd Shift',
                                    'private' => 'Private / IMC',
                                    default => 'Regular',
                                };
                            @endphp

                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $type }}
                            </span>

                        </td>


                        <td class="px-5 py-4 text-sm font-semibold text-slate-800">
                            Rs. {{ number_format($course->fee_amount, 0) }}
                        </td>


                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $course->bankAccount?->account_number ?? 'N/A' }}
                        </td>


                        <td class="px-5 py-4">

                            @if($course->status)

                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    Active
                                </span>

                            @else

                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                    Inactive
                                </span>

                            @endif

                        </td>


                        <td class="px-5 py-4">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('admin.courses.show', $course) }}"
                                   class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                    View
                                </a>

                                <a href="{{ route('admin.courses.edit', $course) }}"
                                   class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.courses.destroy', $course) }}"
                                      onsubmit="return confirm('Delete this course?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="px-5 py-10 text-center text-sm text-slate-500">
                            No courses found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection