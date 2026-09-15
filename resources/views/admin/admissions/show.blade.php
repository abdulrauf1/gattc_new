@extends('layouts.admin')

@section('title', 'Admission Details')

@section('page-heading', 'Admission Review')

@section('content')

<div>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-bold text-slate-900">
                    {{ $admission->admission_no }}
                </h1>


                @php

                    $statusClass = match($admission->status) {

                        'approved' =>
                            'bg-emerald-100 text-emerald-700',

                        'rejected' =>
                            'bg-red-100 text-red-700',

                        default =>
                            'bg-amber-100 text-amber-700',

                    };

                @endphp


                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}"
                >
                    {{ ucfirst($admission->status) }}
                </span>

            </div>


            <p class="mt-1 text-sm text-slate-500">
                Review the application and update its status after physical verification.
            </p>

        </div>


        <a
            href="{{ route('admin.admissions.index') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl bg-white px-4 py-2.5
                   text-sm font-semibold text-slate-700
                   ring-1 ring-slate-200 hover:bg-slate-50"
        >

            <i data-lucide="arrow-left" class="h-4 w-4"></i>

            Back

        </a>

    </div>


    {{-- =========================================================
         IMPORTANT OFFICE VERIFICATION NOTICE
    ========================================================== --}}

    @if($admission->status === 'pending')

        <div class="mb-6 rounded-2xl border border-amber-200
                    bg-amber-50 p-4">

            <div class="flex items-start gap-3">

                <div class="rounded-xl bg-amber-100 p-2.5">

                    <i
                        data-lucide="file-check-2"
                        class="h-5 w-5 text-amber-700"
                    ></i>

                </div>


                <div>

                    <h2 class="font-semibold text-amber-900">
                        Pending Manual Verification
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-amber-800">
                        Verify the student's submitted application form,
                        deposited bank slip and required documents at the GATTC office
                        before approving or rejecting this admission.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         DETAILS
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


        {{-- =====================================================
             STUDENT INFORMATION
        ====================================================== --}}

        <div class="xl:col-span-2 rounded-2xl bg-white p-6
                    shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">

                <div class="rounded-xl bg-emerald-100 p-3">

                    <i
                        data-lucide="user"
                        class="h-5 w-5 text-emerald-700"
                    ></i>

                </div>

                <div>

                    <h2 class="font-bold text-slate-900">
                        Student Information
                    </h2>

                    <p class="text-sm text-slate-500">
                        Details submitted with the application.
                    </p>

                </div>

            </div>


            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Student Name
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admission->student_name }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Father Name
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admission->father_name }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        CNIC
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $admission->cnic ?: '—' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Date of Birth
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $admission->date_of_birth?->format('d M Y') ?? '—' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Gender
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $admission->gender ? ucfirst($admission->gender) : '—' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Phone
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $admission->phone ?: '—' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Email
                    </p>

                    <p class="mt-1 break-all font-medium text-slate-700">
                        {{ $admission->email ?: '—' }}
                    </p>

                </div>


                <div class="sm:col-span-2">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Address
                    </p>

                    <p class="mt-1 leading-6 text-slate-700">
                        {{ $admission->address ?: '—' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ADMISSION SUMMARY
        ====================================================== --}}

        <div class="rounded-2xl bg-white p-6 shadow-sm
                    ring-1 ring-slate-200">

            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">

                <div class="rounded-xl bg-indigo-100 p-3">

                    <i
                        data-lucide="graduation-cap"
                        class="h-5 w-5 text-indigo-700"
                    ></i>

                </div>

                <div>

                    <h2 class="font-bold text-slate-900">
                        Admission
                    </h2>

                    <p class="text-sm text-slate-500">
                        Academic information.
                    </p>

                </div>

            </div>


            <div class="mt-5 space-y-4">


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Admission Number
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admission->admission_no }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Course
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $admission->course?->title ?? 'N/A' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Course Type
                    </p>

                    <p class="mt-1 text-sm text-slate-700">

                        @switch($admission->course?->course_type)

                            @case('dit')
                                DIT / 2nd Shift
                                @break

                            @case('private')
                                Private / IMC
                                @break

                            @default
                                Regular

                        @endswitch

                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Admission Session
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-700">
                        {{ $admission->session?->title ?? 'N/A' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Registration Date
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-700">
                        {{ $admission->created_at?->format('d M Y, h:i A') }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         STATUS UPDATE
    ========================================================== --}}

    <div class="mt-6 rounded-2xl bg-white shadow-sm
                ring-1 ring-slate-200">

        <div class="border-b border-slate-100 p-6">

            <div class="flex items-center gap-3">

                <div class="rounded-xl bg-amber-100 p-3">

                    <i
                        data-lucide="clipboard-check"
                        class="h-5 w-5 text-amber-700"
                    ></i>

                </div>


                <div>

                    <h2 class="font-bold text-slate-900">
                        Manual Admission Verification
                    </h2>

                    <p class="text-sm text-slate-500">
                        Update the admission status after checking the physical documents.
                    </p>

                </div>

            </div>

        </div>


        <div class="p-6">

            <form
                method="POST"
                action="{{ route('admin.admissions.status', $admission) }}"
            >

                @csrf

                @method('PATCH')


                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


                    {{-- Status --}}

                    <div>

                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Admission Status
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-xl border-slate-300
                                   py-2.5
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                            <option value="pending"
                                @selected($admission->status === 'pending')}>
                                Pending Verification
                            </option>

                            <option value="approved"
                                @selected($admission->status === 'approved')}>
                                Approved / Admitted
                            </option>

                            <option value="rejected"
                                @selected($admission->status === 'rejected')}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- Remarks --}}

                    <div class="md:col-span-2">

                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Administrative Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            placeholder="e.g. Original application and deposited bank slip verified."
                            class="w-full rounded-xl border-slate-300
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >{{ old('remarks', $admission->remarks) }}</textarea>

                    </div>

                </div>


                {{-- Quick guidance --}}

                <div class="mt-5 rounded-xl bg-slate-50 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Verification guidance
                    </p>

                    <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600 sm:grid-cols-3">

                        <div class="flex items-center gap-2">

                            <i data-lucide="check-square" class="h-4 w-4 text-emerald-600"></i>

                            Application form checked

                        </div>


                        <div class="flex items-center gap-2">

                            <i data-lucide="check-square" class="h-4 w-4 text-emerald-600"></i>

                            Bank slip checked

                        </div>


                        <div class="flex items-center gap-2">

                            <i data-lucide="check-square" class="h-4 w-4 text-emerald-600"></i>

                            Student documents checked

                        </div>

                    </div>

                </div>


                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('admin.admissions.index') }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl bg-slate-100 px-5 py-2.5
                               text-sm font-semibold text-slate-700
                               hover:bg-slate-200"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl bg-emerald-500 px-5 py-2.5
                               text-sm font-semibold text-white
                               shadow-sm hover:bg-emerald-600"
                    >

                        <i data-lucide="save" class="h-4 w-4"></i>

                        Update Status

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         RELATED VOUCHERS
    ========================================================== --}}

    <div class="mt-6 rounded-2xl bg-white shadow-sm
                ring-1 ring-slate-200">

        <div class="border-b border-slate-100 p-6">

            <div class="flex items-center gap-3">

                <div class="rounded-xl bg-slate-100 p-3">

                    <i
                        data-lucide="file-text"
                        class="h-5 w-5 text-slate-600"
                    ></i>

                </div>

                <div>

                    <h2 class="font-bold text-slate-900">
                        Related Vouchers
                    </h2>

                    <p class="text-sm text-slate-500">
                        Vouchers associated with this student admission.
                    </p>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-100">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wide text-slate-500">
                            Voucher
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wide text-slate-500">
                            Type
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wide text-slate-500">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($admission->vouchers as $voucher)

                        <tr>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $voucher->voucher_no }}
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-600">

                                @switch($voucher->voucher_type)

                                    @case('hostel')
                                        Hostel
                                        @break

                                    @case('readmission')
                                        Re-admission
                                        @break

                                    @default
                                        Admission

                                @endswitch

                            </td>


                            <td class="px-6 py-4 font-semibold text-slate-800">
                                Rs. {{ number_format($voucher->amount, 0) }}
                            </td>


                            <td class="px-6 py-4">

                                @php

                                    $voucherClass = match($voucher->status) {

                                        'paid' =>
                                            'bg-emerald-100 text-emerald-700',

                                        'cancelled' =>
                                            'bg-red-100 text-red-700',

                                        default =>
                                            'bg-amber-100 text-amber-700',

                                    };

                                @endphp

                                <span
                                    class="rounded-full px-2.5 py-1 text-xs
                                           font-semibold {{ $voucherClass }}"
                                >
                                    {{ ucfirst($voucher->status) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.vouchers.show', $voucher) }}"
                                    class="inline-flex items-center gap-1.5
                                           rounded-lg bg-indigo-50 px-3 py-2
                                           text-xs font-semibold text-indigo-700
                                           hover:bg-indigo-100"
                                >

                                    <i data-lucide="eye" class="h-4 w-4"></i>

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-sm text-slate-500"
                            >
                                No vouchers linked to this admission.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         CURRENT REMARKS
    ========================================================== --}}

    @if($admission->remarks)

        <div class="mt-6 rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

            <div class="flex items-start gap-3">

                <i
                    data-lucide="message-square-text"
                    class="mt-0.5 h-5 w-5 text-slate-400"
                ></i>

                <div>

                    <h2 class="font-semibold text-slate-800">
                        Current Remarks
                    </h2>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                        {{ $admission->remarks }}
                    </p>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection