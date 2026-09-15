@extends('layouts.admin')

@section('page-heading', 'Admissions')

@section('content')
<div class="space-y-4">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admissions</h1>
        <p class="mt-1 text-sm text-gray-500">
            Review and manage student admission records.
        </p>
    </div>

    {{-- Compact Statistics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        {{-- Total --}}
        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                        Total
                    </p>
                    <p class="mt-1 text-xl font-bold text-gray-900">
                        {{ $totalAdmissions }}
                    </p>
                </div>

                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-gray-500"></i>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                        Pending
                    </p>
                    <p class="mt-1 text-xl font-bold text-amber-600">
                        {{ $pendingAdmissions }}
                    </p>
                </div>

                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
                </div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                        Approved
                    </p>
                    <p class="mt-1 text-xl font-bold text-emerald-600">
                        {{ $approvedAdmissions }}
                    </p>
                </div>

                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-4 h-4 text-emerald-500"></i>
                </div>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                        Rejected
                    </p>
                    <p class="mt-1 text-xl font-bold text-red-600">
                        {{ $rejectedAdmissions }}
                    </p>
                </div>

                <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                    <i data-lucide="circle-x" class="w-4 h-4 text-red-500"></i>
                </div>
            </div>
        </div>

    </div>

   {{-- Compact Filters --}}
    <div class="bg-white border border-gray-200 rounded-xl p-3">
        <form method="GET" action="{{ route('admin.admissions.index') }}">

            <div class="flex items-center gap-2">

                {{-- Search --}}
                <div class="flex-1 min-w-0">
                    <div class="relative">
                        <i data-lucide="search"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400">
                        </i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search name, admission no, CNIC or phone..."
                            class="w-full h-9 rounded-lg border border-gray-300
                                pl-9 pr-3 text-sm
                                focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        >
                    </div>
                </div>

                {{-- Status --}}
                <div class="w-36 shrink-0">
                    <select
                        name="status"
                        class="w-full h-9 rounded-lg border border-gray-300
                            px-3 text-sm
                            focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>
                            Pending
                        </option>
                        <option value="approved" @selected(request('status') === 'approved')>
                            Approved
                        </option>
                        <option value="rejected" @selected(request('status') === 'rejected')>
                            Rejected
                        </option>
                    </select>
                </div>

                {{-- Course --}}
                <div class="w-60 shrink-0">
                    <select
                        name="course_id"
                        class="w-full h-9 rounded-lg border border-gray-300
                            px-3 text-sm
                            focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Courses</option>

                        @foreach($courses as $course)
                            <option
                                value="{{ $course->id }}"
                                @selected((string) request('course_id') === (string) $course->id)
                            >
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter --}}
                <button
                    type="submit"
                    class="h-9 px-3 shrink-0 rounded-lg
                        bg-emerald-500 hover:bg-emerald-600
                        text-white text-sm font-medium
                        inline-flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Filter</span>
                </button>

                {{-- Reset --}}
                <a
                    href="{{ route('admin.admissions.index') }}"
                    title="Reset filters"
                    class="h-9 w-9 shrink-0 rounded-lg
                        bg-gray-100 hover:bg-gray-200
                        text-gray-600
                        inline-flex items-center justify-center">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>

            </div>

        </form>
    </div>


    {{-- Admissions Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Admission No
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Student
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            CNIC
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Phone
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Course
                        </th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Status
                        </th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($admissions as $admission)

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $admission->admission_no }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ $admission->student_name }}
                                </div>

                                @if($admission->father_name)
                                    <div class="text-xs text-gray-500">
                                        S/O {{ $admission->father_name }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $admission->cnic }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $admission->phone }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="text-gray-800">
                                    {{ $admission->course?->title ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'approved' => 'bg-emerald-50 text-emerald-700',
                                        'rejected' => 'bg-red-50 text-red-700',
                                    ];
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $statusClasses[$admission->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($admission->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <a
                                    href="{{ route('admin.admissions.show', $admission) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                           bg-gray-100 hover:bg-gray-200 text-gray-600">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2"></i>
                                    <p class="text-sm font-medium text-gray-500">
                                        No admissions found.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        @if($admissions->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $admissions->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush