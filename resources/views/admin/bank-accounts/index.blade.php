@extends('layouts.admin')

@section('title', 'Bank Accounts')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold">
                Bank Accounts
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                GATTC fee deposit accounts.
            </p>
        </div>

        <a href="{{ route('admin.bank-accounts.create') }}"
           class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">
            + Add Account
        </a>

    </div>


    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

        @forelse($bankAccounts as $account)

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

                <div class="flex items-start justify-between">

                    <div>

                        <h2 class="font-bold text-slate-800">
                            {{ $account->account_title }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $account->bank_name }}
                        </p>

                    </div>

                    @if($account->status)
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            Active
                        </span>
                    @else
                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                            Inactive
                        </span>
                    @endif

                </div>


                <div class="mt-5 space-y-2 text-sm">

                    <div>
                        <span class="font-medium">Account:</span>
                        {{ $account->account_number }}
                    </div>

                    <div>
                        <span class="font-medium">IBAN:</span>
                        {{ $account->iban }}
                    </div>

                    <div>
                        <span class="font-medium">Branch:</span>
                        {{ $account->branch_name }}
                        ({{ $account->branch_code }})
                    </div>

                    <div>
                        <span class="font-medium">Purpose:</span>
                        {{ $account->purpose }}
                    </div>

                </div>


                <div class="mt-5 flex gap-2">

                    <a href="{{ route('admin.bank-accounts.show', $account) }}"
                       class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold">
                        View
                    </a>

                    <a href="{{ route('admin.bank-accounts.edit', $account) }}"
                       class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-700">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('admin.bank-accounts.destroy', $account) }}"
                          onsubmit="return confirm('Delete this account?');">

                        @csrf
                        @method('DELETE')

                        <button class="rounded-lg bg-red-50 px-3 py-2 text-sm font-semibold text-red-700">
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="rounded-xl bg-white p-10 text-center text-slate-500 lg:col-span-2">
                No bank accounts found.
            </div>

        @endforelse

    </div>

</div>

@endsection