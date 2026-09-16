@extends('layouts.admin')

@section('page-heading', 'Admissions')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div>

        <h1 class="text-xl font-bold text-gray-900">
            Admissions
        </h1>

        <p class="text-xs text-gray-500 mt-1">
            Review applications, verify admissions and generate student cards.
        </p>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-2
                md:grid-cols-4 gap-3">

        <div class="bg-white border rounded-xl px-4 py-3">

            <p class="text-[11px]
                      uppercase font-semibold
                      text-gray-400">
                Total
            </p>

            <p class="text-xl font-bold">
                {{ $totalAdmissions }}
            </p>

        </div>


        <div class="bg-white border rounded-xl px-4 py-3">

            <p class="text-[11px]
                      uppercase font-semibold
                      text-gray-400">
                Pending
            </p>

            <p class="text-xl font-bold text-amber-600">
                {{ $pendingAdmissions }}
            </p>

        </div>


        <div class="bg-white border rounded-xl px-4 py-3">

            <p class="text-[11px]
                      uppercase font-semibold
                      text-gray-400">
                Approved
            </p>

            <p class="text-xl font-bold text-emerald-600">
                {{ $approvedAdmissions }}
            </p>

        </div>


        <div class="bg-white border rounded-xl px-4 py-3">

            <p class="text-[11px]
                      uppercase font-semibold
                      text-gray-400">
                Rejected
            </p>

            <p class="text-xl font-bold text-red-600">
                {{ $rejectedAdmissions }}
            </p>

        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white border rounded-xl p-3">

        <form method="GET"
              action="{{ route(
                  'admin.admissions.index'
              ) }}">

            <div class="flex items-center gap-2">

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
                            placeholder="Search name, admission no, CNIC or phone..."
                            class="w-full h-9
                                   rounded-lg
                                   border border-gray-300
                                   pl-9 pr-3
                                   text-sm">

                    </div>

                </div>


                {{-- Status --}}
                <div class="w-36 shrink-0">

                    <select
                        name="status"
                        class="w-full h-9 rounded-lg
                               border border-gray-300
                               px-3 text-sm">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending"
                            @selected(
                                request('status') === 'pending'
                            )>
                            Pending
                        </option>

                        <option value="approved"
                            @selected(
                                request('status') === 'approved'
                            )>
                            Approved
                        </option>

                        <option value="rejected"
                            @selected(
                                request('status') === 'rejected'
                            )>
                            Rejected
                        </option>

                    </select>

                </div>


                {{-- Course --}}
                <div class="w-56 shrink-0">

                    <select
                        name="course_id"
                        class="w-full h-9 rounded-lg
                               border border-gray-300
                               px-3 text-sm">

                        <option value="">
                            All Courses
                        </option>

                        @foreach($courses as $course)

                            <option
                                value="{{ $course->id }}"
                                @selected(
                                    (string) request(
                                        'course_id'
                                    ) ===
                                    (string) $course->id
                                )>

                                {{ $course->title }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Filter --}}
                <button
                    type="submit"
                    class="h-9 px-3
                           rounded-lg
                           bg-emerald-500
                           hover:bg-emerald-600
                           text-white text-sm
                           font-semibold
                           inline-flex
                           items-center
                           gap-1.5">

                    <i data-lucide="filter"
                       class="w-4 h-4">
                    </i>

                    Filter

                </button>


                {{-- Reset --}}
                <a
                    href="{{ route(
                        'admin.admissions.index'
                    ) }}"
                    title="Reset filters"
                    class="h-9 w-9
                           shrink-0
                           rounded-lg
                           bg-gray-100
                           hover:bg-gray-200
                           flex items-center
                           justify-center">

                    <i data-lucide="rotate-ccw"
                       class="w-4 h-4
                              text-gray-600">
                    </i>

                </a>

            </div>

        </form>

    </div>


    {{-- Admissions table --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full
                          min-w-[1100px]
                          text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Admission No.
                        </th>

                        <th class="px-4 py-3 text-left">
                            Student
                        </th>

                        <th class="px-4 py-3 text-left">
                            CNIC
                        </th>

                        <th class="px-4 py-3 text-left">
                            Phone
                        </th>

                        <th class="px-4 py-3 text-left">
                            Course
                        </th>

                        <th class="px-4 py-3 text-left">
                            Session
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                        <th class="px-4 py-3 text-left">
                            Payment
                        </th>

                        <th class="px-4 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse($admissions as $admission)

                    @php
                        $approvedPayment =
                            $admission->vouchers
                                ->first(
                                    fn ($voucher) =>
                                        $voucher->payment &&
                                        $voucher->payment->status === 'approved'
                                );
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">

                            <span class="font-semibold">
                                {{ $admission->admission_no ?: 'Pending' }}
                            </span>

                        </td>


                        <td class="px-4 py-3">

                            <div class="font-medium">
                                {{ $admission->student_name }}
                            </div>

                            <div class="text-[11px]
                                        text-gray-500">

                                S/O
                                {{ $admission->father_name }}

                            </div>

                        </td>


                        <td class="px-4 py-3">
                            {{ $admission->cnic }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $admission->phone }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $admission->course?->title ?: '—' }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $admission->session?->title ?: '—' }}
                        </td>


                        <td class="px-4 py-3">

                            @php
                                $statusClass =
                                    match ($admission->status) {

                                        'approved' =>
                                            'bg-emerald-50 text-emerald-700',

                                        'rejected' =>
                                            'bg-red-50 text-red-700',

                                        default =>
                                            'bg-amber-50 text-amber-700',
                                    };
                            @endphp

                            <span
                                class="inline-flex
                                       px-2 py-1
                                       rounded-full
                                       text-[11px]
                                       font-semibold
                                       {{ $statusClass }}">

                                {{ ucfirst(
                                    $admission->status
                                ) }}

                            </span>

                        </td>


                        <td class="px-4 py-3">

                            @if($approvedPayment)

                                <span
                                    class="inline-flex
                                           px-2 py-1
                                           rounded-full
                                           bg-emerald-50
                                           text-emerald-700
                                           text-[11px]
                                           font-semibold">

                                    Verified

                                </span>

                            @else

                                <span class="text-[11px]
                                             text-gray-400">

                                    Not Verified

                                </span>

                            @endif

                        </td>


                        <td class="px-4 py-3 text-right">

                            <a
                                href="{{ route(
                                    'admin.admissions.show',
                                    $admission
                                ) }}"
                                class="inline-flex
                                       items-center
                                       gap-1.5
                                       h-8 px-3
                                       rounded-lg
                                       bg-gray-100
                                       hover:bg-gray-200
                                       text-gray-700
                                       text-xs
                                       font-semibold">

                                <i data-lucide="eye"
                                   class="w-3.5 h-3.5">
                                </i>

                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9"
                            class="px-6 py-12
                                   text-center">

                            <i data-lucide="inbox"
                               class="w-10 h-10
                                      mx-auto
                                      text-gray-300">
                            </i>

                            <p class="mt-2
                                      text-sm
                                      text-gray-500">

                                No admissions found.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($admissions->hasPages())

            <div class="border-t
                        px-4 py-3">

                {{ $admissions
                    ->withQueryString()
                    ->links() }}

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