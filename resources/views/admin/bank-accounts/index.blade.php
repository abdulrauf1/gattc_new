@extends('layouts.admin')

@section('page-heading', 'Bank Accounts')

@section('content')

<div class="space-y-4">

    <div>

        <h1 class="text-xl font-bold">
            Bank Accounts
        </h1>

        <p class="text-xs text-gray-500 mt-1">
            View GATTC Bank of Khyber receiving accounts. Account details are managed by authorized administration.
        </p>

    </div>


    <div class="grid grid-cols-1
                md:grid-cols-2
                xl:grid-cols-4 gap-4">

        @forelse($bankAccounts as $account)

            <div class="bg-white border
                        rounded-xl overflow-hidden">

                <div class="p-4">

                    <div class="flex
                                items-center
                                justify-between">

                        <span
                            class="px-2 py-1 rounded-full
                                   bg-emerald-50
                                   text-emerald-700
                                   text-[10px]
                                   font-semibold">

                            {{ $account->status
                                ? 'ACTIVE'
                                : 'INACTIVE'
                            }}

                        </span>

                        <i data-lucide="landmark"
                           class="w-5 h-5
                                  text-gray-400"></i>

                    </div>


                    <p class="text-xs
                              text-gray-500
                              mt-4">

                        {{ $account->purpose }}

                    </p>


                    <h2 class="font-semibold
                               text-gray-900
                               mt-1">

                        {{ $account->account_title }}

                    </h2>


                    <p class="text-lg
                              font-bold
                              tracking-wide
                              mt-3">

                        {{ $account->account_number }}

                    </p>


                    <p class="text-xs
                              text-gray-500
                              mt-1">

                        {{ $account->bank_name }}

                    </p>


                    <div class="mt-4
                                flex gap-2">

                        <a
                            href="{{ route(
                                'admin.bank-accounts.show',
                                $account
                            ) }}"
                            class="flex-1 h-8
                                   rounded-lg
                                   bg-gray-100
                                   hover:bg-gray-200
                                   text-gray-700
                                   text-xs
                                   font-semibold
                                   inline-flex
                                   items-center
                                   justify-center">

                            View

                        </a>


                        <a
                            href="{{ route(
                                'admin.bank-accounts.print',
                                $account
                            ) }}"
                            target="_blank"
                            class="w-8 h-8
                                   rounded-lg
                                   bg-blue-50
                                   text-blue-600
                                   inline-flex
                                   items-center
                                   justify-center">

                            <i data-lucide="printer"
                               class="w-4 h-4"></i>

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-span-full
                        bg-white border rounded-xl
                        px-6 py-10 text-center
                        text-gray-400">

                No bank accounts found.

            </div>

        @endforelse

    </div>

</div>

@endsection

