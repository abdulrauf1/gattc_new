@extends('layouts.admin')

@section('page-heading', 'Bank Account')

@section('content')

<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex flex-col
                sm:flex-row
                sm:items-center
                sm:justify-between gap-3">

        <div>

            <h1 class="text-xl font-bold">
                Bank Account Details
            </h1>

            <p class="text-xs text-gray-500 mt-1">
                {{ $bankAccount->purpose }}
            </p>

        </div>


        <div class="flex items-center gap-2">

            <a
                href="{{ route(
                    'admin.bank-accounts.index'
                ) }}"
                class="h-9 px-3 rounded-lg
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


            <a
                href="{{ route(
                    'admin.bank-accounts.print',
                    $bankAccount
                ) }}"
                target="_blank"
                class="h-9 px-3 rounded-lg
                       bg-blue-600
                       hover:bg-blue-700
                       text-white
                       text-sm
                       font-semibold
                       inline-flex
                       items-center gap-1.5">

                <i data-lucide="printer"
                   class="w-4 h-4">
                </i>

                Print 

            </a>

        </div>

        

    </div>


    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-5 py-4
                    border-b bg-gray-50">

            <div class="flex
                        items-start
                        justify-between gap-3">

                <div>

                    <p class="text-[10px]
                              uppercase
                              tracking-wider
                              font-bold
                              text-gray-400">

                        {{ $bankAccount->purpose }}

                    </p>

                    <h2 class="text-lg
                               font-bold mt-1">

                        {{ $bankAccount->account_title }}

                    </h2>

                </div>


                @if($bankAccount->status)

                    <span class="badge-green">
                        Active
                    </span>

                @else

                    <span class="badge-gray">
                        Inactive
                    </span>

                @endif

            </div>

        </div>


        <div class="p-5 grid
                    grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-3 gap-5">

            <div>

                <p class="label">Bank</p>

                <p class="value">
                    {{ $bankAccount->bank_name }}
                </p>

            </div>


            <div class="lg:col-span-2">

                <p class="label">
                    Account Title
                </p>

                <p class="value">
                    {{ $bankAccount->account_title }}
                </p>

            </div>


            <div>

                <p class="label">
                    Account Number
                </p>

                <p class="value font-semibold">
                    {{ $bankAccount->account_number }}
                </p>

            </div>


            <div class="lg:col-span-2">

                <p class="label">
                    IBAN
                </p>

                <p class="value">
                    {{ $bankAccount->iban ?: '—' }}
                </p>

            </div>


            <div>

                <p class="label">
                    Branch
                </p>

                <p class="value">
                    {{ $bankAccount->branch_name ?: '—' }}
                </p>

            </div>


            <div>

                <p class="label">
                    Branch Code
                </p>

                <p class="value">
                    {{ $bankAccount->branch_code ?: '—' }}
                </p>

            </div>


            <div class="sm:col-span-2
                        lg:col-span-3">

                <p class="label">
                    Purpose
                </p>

                <p class="value">
                    {{ $bankAccount->purpose }}
                </p>

            </div>

        </div>

    </div>


    {{-- Courses using this account --}}
    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-5 py-4
                    border-b bg-gray-50">

            <h2 class="text-sm font-semibold">
                Courses Using This Account
            </h2>

        </div>


        <div class="p-5">

            @php
                $courses =
                    $bankAccount
                        ->courses()
                        ->orderBy('title')
                        ->get();
            @endphp


            @if($courses->count())

                <div class="flex flex-wrap gap-2">

                    @foreach($courses as $course)

                        <span
                            class="inline-flex
                                   px-3 py-1.5
                                   rounded-lg
                                   bg-slate-100
                                   text-slate-700
                                   text-xs
                                   font-medium">

                            {{ $course->title }}

                        </span>

                    @endforeach

                </div>

            @else

                <p class="text-xs text-gray-400">
                    No courses are currently assigned to this account.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>

    .label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
        color: #9ca3af;
    }

    .value {
        margin-top: .25rem;
        font-size: .875rem;
        color: #1f2937;
    }

</style>
@endpush