@extends('layouts.admin')

@section('page-heading', 'Bank Account')

@section('content')

<div class="max-w-4xl mx-auto space-y-4">

    <div class="flex items-center
                justify-between">

        <div>

            <h1 class="text-xl font-bold">
                Bank Account Details
            </h1>

            <p class="text-xs
                      text-gray-500 mt-1">

                {{ $bankAccount->purpose }}

            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route(
                    'admin.bank-accounts.index'
                ) }}"
                class="btn-secondary">

                Back

            </a>

            <a
                href="{{ route(
                    'admin.bank-accounts.print',
                    $bankAccount
                ) }}"
                target="_blank"
                class="btn-primary">

                <i data-lucide="printer"
                   class="w-4 h-4"></i>

                Print

            </a>

        </div>

    </div>


    <div class="bg-white border
                rounded-xl overflow-hidden">

        <div class="px-5 py-4
                    border-b bg-gray-50">

            <p class="text-xs
                      font-semibold
                      text-gray-400
                      uppercase">

                {{ $bankAccount->purpose }}

            </p>

            <h2 class="text-lg font-bold mt-1">
                {{ $bankAccount->account_title }}
            </h2>

        </div>


        <div class="p-5 grid
                    grid-cols-1
                    md:grid-cols-2 gap-5">

            <div>
                <p class="label">
                    Bank
                </p>

                <p class="value">
                    {{ $bankAccount->bank_name }}
                </p>
            </div>


            <div>
                <p class="label">
                    Account Number
                </p>

                <p class="value">
                    {{ $bankAccount->account_number }}
                </p>
            </div>


            <div>
                <p class="label">
                    Account Title
                </p>

                <p class="value">
                    {{ $bankAccount->account_title }}
                </p>
            </div>


            <div>
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

        </div>


        <div class="px-5 py-4
                    border-t bg-gray-50">

            <p class="text-xs
                      text-gray-500">

                Account purpose

            </p>

            <p class="text-sm
                      font-semibold mt-1">

                {{ $bankAccount->purpose }}

            </p>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .label {
        font-size:.65rem;
        text-transform:uppercase;
        letter-spacing:.04em;
        font-weight:600;
        color:#6b7280;
    }

    .value {
        margin-top:.25rem;
        font-size:.875rem;
        color:#1f2937;
        font-weight:500;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endpush