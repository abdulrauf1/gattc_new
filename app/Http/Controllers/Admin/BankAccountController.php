<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts =
            BankAccount::query()
                ->orderBy('purpose')
                ->orderBy('account_title')
                ->get();

        return view(
            'admin.bank-accounts.index',
            compact('bankAccounts')
        );
    }

    public function show(
        BankAccount $bankAccount
    ) {
        return view(
            'admin.bank-accounts.show',
            compact('bankAccount')
        );
    }

    public function print(
        BankAccount $bankAccount
    ) {
        return view(
            'admin.bank-accounts.print',
            compact('bankAccount')
        );
    }
}