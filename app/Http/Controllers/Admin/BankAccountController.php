<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::query()
            ->withCount(['courses', 'vouchers'])
            ->orderBy('account_title')
            ->paginate(20);

        return view(
            'admin.bank-accounts.index',
            compact('accounts')
        );
    }

    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateAccount($request);

        BankAccount::create($validated);

        return redirect()
            ->route('admin.bank-accounts.index')
            ->with(
                'success',
                'Bank account created successfully.'
            );
    }

    public function edit(BankAccount $bankAccount)
    {
        return view(
            'admin.bank-accounts.edit',
            compact('bankAccount')
        );
    }

    public function update(
        Request $request,
        BankAccount $bankAccount
    ) {
        $validated = $this->validateAccount($request);

        $bankAccount->update($validated);

        return redirect()
            ->route('admin.bank-accounts.index')
            ->with(
                'success',
                'Bank account updated successfully.'
            );
    }

    public function destroy(
        BankAccount $bankAccount
    ) {
        if (
            $bankAccount->courses()->exists() ||
            $bankAccount->vouchers()->exists()
        ) {
            return back()->with(
                'error',
                'This bank account cannot be deleted because it is already in use.'
            );
        }

        $bankAccount->delete();

        return back()->with(
            'success',
            'Bank account deleted successfully.'
        );
    }

    private function validateAccount(
        Request $request
    ): array {
        return $request->validate([
            'account_title' => [
                'required',
                'string',
                'max:150',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:100',
            ],

            'branch_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'branch_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'iban' => [
                'nullable',
                'string',
                'max:100',
            ],

            'purpose' => [
                'required',
                'string',
                'max:150',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}