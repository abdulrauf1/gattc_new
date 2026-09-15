@extends('layouts.admin')

@section('page-heading', 'Vouchers')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Vouchers
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Generate and manage admission, hostel and readmission vouchers.
            </p>
        </div>

        <a
            href="{{ route('admin.vouchers.create') }}"
            class="inline-flex items-center justify-center gap-2
                   px-4 py-2 rounded-lg
                   bg-emerald-500 hover:bg-emerald-600
                   text-white text-sm font-semibold">

            <i data-lucide="plus" class="w-4 h-4"></i>

            Generate Voucher
        </a>

    </div>


    {{-- Compact Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-xs text-gray-500">All Vouchers</p>
            <p class="text-xl font-bold text-gray-900">
                {{ $totalVouchers }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-xs text-gray-500">Admission</p>
            <p class="text-xl font-bold text-blue-600">
                {{ $admissionVouchers }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-xs text-gray-500">Hostel</p>
            <p class="text-xl font-bold text-purple-600">
                {{ $hostelVouchers }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-4 py-3">
            <p class="text-xs text-gray-500">Readmission</p>
            <p class="text-xl font-bold text-amber-600">
                {{ $readmissionVouchers }}
            </p>
        </div>

    </div>


    {{-- Voucher Tabs --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        <div class="border-b border-gray-200 px-3">

            <nav class="flex items-center gap-1 overflow-x-auto">

                @php
                    $tabs = [
                        '' => ['label' => 'All', 'count' => $totalVouchers],
                        'admission' => ['label' => 'Admission', 'count' => $admissionVouchers],
                        'hostel' => ['label' => 'Hostel', 'count' => $hostelVouchers],
                        'readmission' => ['label' => 'Readmission', 'count' => $readmissionVouchers],
                    ];
                @endphp

                @foreach($tabs as $tabType => $tab)

                    <a
                        href="{{ route('admin.vouchers.index', $tabType ? ['type' => $tabType] : []) }}"
                        class="px-4 py-3 text-sm font-medium whitespace-nowrap
                        border-b-2
                        {{ ($type ?? '') === $tabType
                            ? 'border-emerald-500 text-emerald-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }}">

                        {{ $tab['label'] }}

                        <span class="ml-1 text-xs">
                            ({{ $tab['count'] }})
                        </span>

                    </a>

                @endforeach

            </nav>

        </div>


        {{-- Filters --}}
        <div class="p-3 border-b border-gray-200">

            <form method="GET"
                  action="{{ route('admin.vouchers.index') }}">

                @if($type)
                    <input
                        type="hidden"
                        name="type"
                        value="{{ $type }}">
                @endif

                <div class="flex flex-col lg:flex-row gap-2">

                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">

                            <i data-lucide="search"
                               class="absolute left-3 top-1/2
                                      -translate-y-1/2
                                      w-4 h-4 text-gray-400">
                            </i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search voucher, student, CNIC or phone..."
                                class="w-full h-9 rounded-lg border border-gray-300
                                       pl-9 pr-3 text-sm
                                       focus:border-blue-500
                                       focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>


                    {{-- Status --}}
                    <div class="w-full lg:w-36">

                        <select
                            name="status"
                            class="w-full h-9 rounded-lg border border-gray-300
                                   px-3 text-sm">

                            <option value="">
                                All Status
                            </option>

                            <option value="generated"
                                @selected(request('status') === 'generated')}>
                                Generated
                            </option>

                            <option value="paid"
                                @selected(request('status') === 'paid')}>
                                Paid
                            </option>

                            <option value="cancelled"
                                @selected(request('status') === 'cancelled')}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Course --}}
                    <div class="w-full lg:w-56">

                        <select
                            name="course_id"
                            class="w-full h-9 rounded-lg border border-gray-300
                                   px-3 text-sm">

                            <option value="">
                                All Courses
                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    @selected((string)request('course_id') === (string)$course->id)>
                                    {{ $course->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Filter --}}
                    <button
                        type="submit"
                        class="h-9 px-3 rounded-lg
                               bg-emerald-500 hover:bg-emerald-600
                               text-white text-sm font-medium
                               inline-flex items-center justify-center gap-1.5">

                        <i data-lucide="filter"
                           class="w-4 h-4"></i>

                        Filter

                    </button>


                    {{-- Reset --}}
                    <a
                        href="{{ $type
                            ? route('admin.vouchers.index', ['type' => $type])
                            : route('admin.vouchers.index')
                        }}"
                        title="Reset filters"
                        class="h-9 w-9 rounded-lg
                               bg-gray-100 hover:bg-gray-200
                               flex items-center justify-center">

                        <i data-lucide="rotate-ccw"
                           class="w-4 h-4 text-gray-600"></i>

                    </a>

                </div>

            </form>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Voucher
                        </th>

                        <th class="px-4 py-3 text-left">
                            Applicant
                        </th>

                        <th class="px-4 py-3 text-left">
                            Type
                        </th>

                        <th class="px-4 py-3 text-left">
                            Course
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left">
                            Due Date
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                        <th class="px-4 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse($vouchers as $voucher)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">

                            <div class="font-semibold text-gray-900">
                                {{ $voucher->voucher_no }}
                            </div>

                            @if($voucher->admission)
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $voucher->admission->admission_no }}
                                </div>
                            @endif

                        </td>


                        <td class="px-4 py-3">

                            <div class="font-medium text-gray-900">
                                {{ $voucher->applicant_name }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $voucher->cnic }}
                            </div>

                        </td>


                        <td class="px-4 py-3">

                            @php
                                $typeClass = match($voucher->voucher_type) {
                                    'admission' => 'bg-blue-50 text-blue-700',
                                    'hostel' => 'bg-purple-50 text-purple-700',
                                    'readmission' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <span class="inline-flex px-2 py-1
                                         rounded-full text-xs font-semibold
                                         {{ $typeClass }}">

                                {{ $voucher->voucher_type_label }}

                            </span>

                        </td>


                        <td class="px-4 py-3">

                            {{ $voucher->course?->title ?? '—' }}

                        </td>


                        <td class="px-4 py-3 font-semibold">

                            Rs. {{ number_format($voucher->amount, 0) }}

                        </td>


                        <td class="px-4 py-3 text-gray-600">

                            {{ optional($voucher->due_date)->format('d M Y') }}

                        </td>


                        <td class="px-4 py-3">

                            @php
                                $statusClass = match($voucher->status) {
                                    'generated' => 'bg-blue-50 text-blue-700',
                                    'paid' => 'bg-emerald-50 text-emerald-700',
                                    'cancelled' => 'bg-red-50 text-red-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <span class="inline-flex px-2 py-1
                                         rounded-full text-xs font-semibold
                                         {{ $statusClass }}">

                                {{ ucfirst($voucher->status) }}

                            </span>

                        </td>


                        <td class="px-4 py-3 text-right">

                            <a
                                href="{{ route('admin.vouchers.show', $voucher) }}"
                                class="inline-flex items-center justify-center
                                       w-8 h-8 rounded-lg
                                       bg-gray-100 hover:bg-gray-200">

                                <i data-lucide="eye"
                                   class="w-4 h-4 text-gray-600"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="px-6 py-12 text-center">

                            <i data-lucide="file-text"
                               class="w-10 h-10 mx-auto text-gray-300">
                            </i>

                            <p class="mt-2 text-sm text-gray-500">
                                No vouchers found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($vouchers->hasPages())

            <div class="px-4 py-3 border-t">
                {{ $vouchers->links() }}
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