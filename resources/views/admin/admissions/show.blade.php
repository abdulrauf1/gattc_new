@extends('layouts.admin')

@section('page-heading', 'Admission Details')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    @php
        $currentCard =
            $admission->studentCards
                ->where('status', true)
                ->sortByDesc('id')
                ->first();
    @endphp


    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-xl font-bold text-gray-900">
                    Admission Details
                </h1>

                <span class="status-badge
                    @if($admission->status === 'approved')
                        approved
                    @elseif($admission->status === 'rejected')
                        rejected
                    @else
                        pending
                    @endif">

                    {{ ucfirst(
                        $admission->status
                    ) }}

                </span>

            </div>

            <p class="text-xs
                      text-gray-500 mt-1">

                Admission:
                <strong>
                    {{ $admission->admission_no ?: 'Not Assigned' }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route(
                'admin.admissions.index'
            ) }}"
            class="h-9 px-3
                   rounded-lg
                   bg-gray-100
                   hover:bg-gray-200
                   text-gray-700
                   text-sm
                   inline-flex
                   items-center gap-1.5">

            <i data-lucide="arrow-left"
               class="w-4 h-4">
            </i>

            Back

        </a>

    </div>


    {{-- Student Information --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-4 py-3
                    border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Student Information
            </h2>

        </div>


        <div class="p-4 grid
                    grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-4 gap-4">

            <div>
                <p class="label">
                    Student Name
                </p>

                <p class="value">
                    {{ $admission->student_name }}
                </p>
            </div>


            <div>
                <p class="label">
                    Father Name
                </p>

                <p class="value">
                    {{ $admission->father_name }}
                </p>
            </div>


            <div>
                <p class="label">
                    CNIC
                </p>

                <p class="value">
                    {{ $admission->cnic }}
                </p>
            </div>


            <div>
                <p class="label">
                    Contact
                </p>

                <p class="value">
                    {{ $admission->phone }}
                </p>
            </div>


            <div>
                <p class="label">
                    Date of Birth
                </p>

                <p class="value">
                    {{ optional(
                        $admission->date_of_birth
                    )->format('d M Y') ?: '—' }}
                </p>
            </div>


            <div>
                <p class="label">
                    Gender
                </p>

                <p class="value">
                    {{ $admission->gender ?: '—' }}
                </p>
            </div>


            <div class="lg:col-span-2">

                <p class="label">
                    Address
                </p>

                <p class="value">
                    {{ $admission->address ?: '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- Academic --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-4 py-3
                    border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Academic Information
            </h2>

        </div>


        <div class="p-4 grid
                    grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-4 gap-4">

            <div>
                <p class="label">
                    Course
                </p>

                <p class="value">
                    {{ $admission->course?->title }}
                </p>
            </div>


            <div>
                <p class="label">
                    Course Type
                </p>

                <p class="value">
                    {{ $admission->course
                        ? ucfirst(
                            $admission->course->course_type
                        )
                        : '—'
                    }}
                </p>
            </div>


            <div>
                <p class="label">
                    Session
                </p>

                <p class="value">
                    {{ $admission->session?->title }}
                </p>
            </div>


            <div>
                <p class="label">
                    Admission No.
                </p>

                <p class="value font-semibold">
                    {{ $admission->admission_no ?: 'Pending' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Voucher / Payment --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-4 py-3
                    border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Related Vouchers & Payments
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Voucher
                        </th>

                        <th class="px-4 py-3 text-left">
                            Type
                        </th>

                        <th class="px-4 py-3 text-left">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left">
                            Payment
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse(
                    $admission->vouchers
                    as $voucher
                )

                    <tr>

                        <td class="px-4 py-3">

                            <a
                                href="{{ route(
                                    'admin.fee-payments.voucher',
                                    $voucher
                                ) }}"
                                class="font-semibold
                                       text-blue-600
                                       hover:underline">

                                {{ $voucher->voucher_no }}

                            </a>

                        </td>


                        <td class="px-4 py-3">

                            {{ $voucher->voucher_type_label }}

                        </td>


                        <td class="px-4 py-3
                                   font-semibold">

                            Rs.
                            {{ number_format(
                                $voucher->amount,
                                0
                            ) }}

                        </td>


                        <td class="px-4 py-3">

                            @if($voucher->payment)

                                {{ $voucher->payment->deposit_slip_no ?: '—' }}

                            @else

                                <span class="text-gray-400">
                                    Not Submitted
                                </span>

                            @endif

                        </td>


                        <td class="px-4 py-3">

                            @if(
                                $voucher->payment?->status === 'approved'
                            )

                                <span
                                    class="badge-green">
                                    Verified
                                </span>

                            @elseif(
                                $voucher->payment?->status === 'pending'
                            )

                                <span
                                    class="badge-amber">
                                    Pending
                                </span>

                            @elseif(
                                $voucher->payment?->status === 'rejected'
                            )

                                <span
                                    class="badge-red">
                                    Rejected
                                </span>

                            @else

                                <span
                                    class="badge-gray">
                                    Not Submitted
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-4 py-8
                                   text-center
                                   text-sm
                                   text-gray-400">

                            No related vouchers found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Admission Status --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-4 py-3
                    border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Admission Verification
            </h2>

            <p class="text-[11px]
                      text-gray-500 mt-0.5">

                Payment approval and admission approval
                are separate administrative steps.

            </p>

        </div>


        <div class="p-4">

            <form
                method="POST"
                action="{{ route(
                    'admin.admissions.status',
                    $admission
                ) }}">

                @csrf
                @method('PATCH')


                <div class="grid
                            grid-cols-1
                            md:grid-cols-3 gap-3">

                    <div>

                        <label class="form-label">
                            Admission Status
                        </label>

                        <select
                            name="status"
                            class="form-input">

                            <option value="pending"
                                @selected(
                                    $admission->status === 'pending'
                                )>
                                Pending
                            </option>

                            <option value="approved"
                                @selected(
                                    $admission->status === 'approved'
                                )>
                                Approved
                            </option>

                            <option value="rejected"
                                @selected(
                                    $admission->status === 'rejected'
                                )>
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="md:col-span-2">

                        <label class="form-label">
                            Verification Remarks
                        </label>

                        <input
                            type="text"
                            name="remarks"
                            value="{{ $admission->remarks }}"
                            class="form-input"
                            placeholder="Physical application/document verification remarks">

                    </div>

                </div>


                <div class="mt-3 flex justify-end">

                    <button
                        type="submit"
                        class="h-9 px-4
                               rounded-lg
                               bg-emerald-500
                               hover:bg-emerald-600
                               text-white
                               text-sm
                               font-semibold">

                        Update Admission Status

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Student Card --}}
    @if($admission->status === 'approved')

        <div class="bg-white
                    border
                    border-blue-200
                    rounded-xl
                    overflow-hidden">

            <div class="px-4 py-3
                        border-b
                        bg-blue-50">

                <div class="flex items-center
                            justify-between">

                    <div>

                        <h2 class="text-sm
                                   font-semibold
                                   text-blue-900">

                            Student Card

                        </h2>

                        <p class="text-[11px]
                                  text-blue-700
                                  mt-0.5">

                            Student cards are generated
                            only from the Admissions page
                            after payment verification.

                        </p>

                    </div>


                    @if($currentCard)

                        <span class="badge-green">
                            Card Issued
                        </span>

                    @endif

                </div>

            </div>


            <div class="p-4">


                @if($currentCard)

                    <div class="grid
                                grid-cols-1
                                md:grid-cols-3 gap-4">

                        <div>

                            <p class="label">
                                Card No.
                            </p>

                            <p class="value
                                      font-semibold">

                                {{ $currentCard->card_no }}

                            </p>

                        </div>


                        <div>

                            <p class="label">
                                Issue Date
                            </p>

                            <p class="value">

                                {{ optional(
                                    $currentCard->issued_at
                                )->format('d M Y') }}

                            </p>

                        </div>


                        <div>

                            <p class="label">
                                Expiry Date
                            </p>

                            <p class="value">

                                {{ optional(
                                    $currentCard->expiry_date
                                )->format('d M Y')
                                }}

                            </p>

                        </div>

                    </div>


                    <div class="mt-4 flex
                                justify-end">

                        <a
                            href="{{ route(
                                'admin.student-cards.print',
                                $currentCard
                            ) }}"
                            target="_blank"
                            class="h-9 px-4
                                   rounded-lg
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-sm
                                   font-semibold
                                   inline-flex
                                   items-center
                                   gap-1.5">

                            <i data-lucide="printer"
                               class="w-4 h-4">
                            </i>

                            Print Student Card

                        </a>

                    </div>

                @else

                    @if($canGenerateCard)

                        <form
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{ route(
                                'admin.admissions.student-card',
                                $admission
                            ) }}">

                            @csrf


                            <div class="grid
                                        grid-cols-1
                                        md:grid-cols-3 gap-3">


                                {{-- Photo --}}
                                <div>

                                    <label class="form-label">
                                        Student Photo *
                                    </label>

                                    <input
                                        type="file"
                                        name="student_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required
                                        class="block w-full
                                               text-xs
                                               border
                                               border-gray-300
                                               rounded-lg
                                               bg-white p-2">

                                    <p class="text-[10px]
                                              text-gray-500 mt-1">

                                        JPG, PNG or WEBP,
                                        maximum 2 MB.

                                    </p>

                                </div>


                                {{-- Expiry --}}
                                <div>

                                    <label class="form-label">
                                        Card Expiry
                                    </label>

                                    <input
                                        type="date"
                                        name="expiry_date"
                                        value="{{ now()
                                            ->addYear()
                                            ->format('Y-m-d') }}"
                                        class="form-input">

                                </div>


                                {{-- Remarks --}}
                                <div>

                                    <label class="form-label">
                                        Remarks
                                    </label>

                                    <input
                                        type="text"
                                        name="remarks"
                                        class="form-input"
                                        placeholder="Optional remarks">

                                </div>

                            </div>


                            <div class="mt-4 flex
                                        items-center
                                        justify-between">

                                <div>

                                    <p class="text-xs
                                              text-gray-500">

                                        Verified payment:

                                        <strong>
                                            {{ $paidAdmissionVoucher?->voucher_no }}
                                        </strong>

                                    </p>

                                    <p class="text-[11px]
                                              text-gray-400 mt-0.5">

                                        Admission No.:

                                        <strong>
                                            {{ $admission->admission_no ?: 'Will be generated' }}
                                        </strong>

                                    </p>

                                </div>


                                <button
                                    type="submit"
                                    class="h-9 px-4
                                           rounded-lg
                                           bg-blue-600
                                           hover:bg-blue-700
                                           text-white
                                           text-sm
                                           font-semibold
                                           inline-flex
                                           items-center
                                           gap-1.5">

                                    <i data-lucide="id-card"
                                       class="w-4 h-4">
                                    </i>

                                    Generate Student Card

                                </button>

                            </div>

                        </form>

                    @else

                        <div class="rounded-lg
                                    bg-amber-50
                                    border
                                    border-amber-200
                                    p-3">

                            <p class="text-sm
                                      font-semibold
                                      text-amber-800">

                                Payment verification required

                            </p>

                            <p class="text-xs
                                      text-amber-700 mt-1">

                                The student card will become
                                available after the admission/readmission
                                payment is approved.

                            </p>

                        </div>

                    @endif

                @endif

            </div>

        </div>

    @endif


    {{-- Previous card history --}}
    @if($admission->studentCards->count())

        <div class="bg-white border
                    rounded-xl overflow-hidden">

            <div class="px-4 py-3
                        border-b bg-gray-50">

                <h2 class="text-sm font-semibold">
                    Student Card History
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full
                              min-w-[650px]
                              text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-4 py-3 text-left">
                                Card No.
                            </th>

                            <th class="px-4 py-3 text-left">
                                Issued
                            </th>

                            <th class="px-4 py-3 text-left">
                                Expiry
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

                    @foreach(
                        $admission->studentCards
                        ->sortByDesc('id')
                        as $card
                    )

                        <tr>

                            <td class="px-4 py-3
                                       font-semibold">

                                {{ $card->card_no }}

                            </td>


                            <td class="px-4 py-3">

                                {{ optional(
                                    $card->issued_at
                                )->format('d M Y') }}

                            </td>


                            <td class="px-4 py-3">

                                {{ optional(
                                    $card->expiry_date
                                )->format('d M Y')
                                }}

                            </td>


                            <td class="px-4 py-3">

                                @if($card->status)

                                    <span class="badge-green">
                                        Active
                                    </span>

                                @else

                                    <span class="badge-gray">
                                        Superseded
                                    </span>

                                @endif

                            </td>


                            <td class="px-4 py-3 text-right">

                                <a
                                    href="{{ route(
                                        'admin.student-cards.print',
                                        $card
                                    ) }}"
                                    target="_blank"
                                    class="inline-flex
                                           items-center
                                           justify-center
                                           w-8 h-8
                                           rounded-lg
                                           bg-gray-100
                                           hover:bg-gray-200">

                                    <i data-lucide="printer"
                                       class="w-4 h-4
                                              text-gray-600">
                                    </i>

                                </a>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>

@endsection


@push('styles')
<style>

    .label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: rgb(107 114 128);
        font-weight: 600;
    }

    .value {
        margin-top: 0.2rem;
        font-size: 0.8125rem;
        color: rgb(31 41 55);
    }

    .form-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgb(55 65 81);
    }

    .form-input {
        display: block;
        width: 100%;
        height: 2.25rem;
        border-radius: 0.5rem;
        border: 1px solid rgb(209 213 219);
        background: #fff;
        padding: 0 0.75rem;
        font-size: 0.8125rem;
        outline: none;
    }

    .status-badge,
    .badge-green,
    .badge-amber,
    .badge-red,
    .badge-gray {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .status-badge.approved,
    .badge-green {
        background: rgb(236 253 245);
        color: rgb(4 120 87);
    }

    .status-badge.pending,
    .badge-amber {
        background: rgb(255 247 237);
        color: rgb(180 83 9);
    }

    .status-badge.rejected,
    .badge-red {
        background: rgb(254 242 242);
        color: rgb(185 28 28);
    }

    .badge-gray {
        background: rgb(243 244 246);
        color: rgb(75 85 99);
    }

</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (window.lucide) {
        lucide.createIcons();
    }

});
</script>
@endpush