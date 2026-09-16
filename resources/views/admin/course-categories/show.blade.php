@extends('layouts.admin')

@section('page-heading', 'Course Category')

@section('content')

<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold">
                {{ $courseCategory->name }}
            </h1>

            <p class="text-xs text-gray-500">
                {{ $courseCategory->description ?: 'No description' }}
            </p>
        </div>

        <a
            href="{{ route(
                'admin.course-categories.index'
            ) }}"
            class="btn-secondary">

            Back

        </a>

    </div>


    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="px-4 py-3 border-b bg-gray-50">
            <h2 class="text-sm font-semibold">
                Courses in this Category
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm min-w-[700px]">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Course</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Fee</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($courseCategory->courses as $course)

                    <tr>

                        <td class="px-4 py-3 font-medium">
                            {{ $course->title }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($course->course_type) }}
                        </td>

                        <td class="px-4 py-3">
                            Rs. {{ number_format(
                                $course->fee_amount,
                                0
                            ) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $course->status
                                ? 'Active'
                                : 'Inactive'
                            }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4"
                            class="px-4 py-8 text-center text-gray-400">
                            No courses assigned.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection