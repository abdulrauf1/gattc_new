<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::latest()->paginate(10);

        return view('admin.bank-accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_title' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:100'],
            'account_type' => ['nullable', 'string', 'max:100'],
            'purpose' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['status'] = $request->boolean('status');

        BankAccount::create($validated);

        return redirect()
            ->route('admin.bank-accounts.index')
            ->with('success', 'Bank account added successfully.');
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
        $validated = $request->validate([
            'account_title' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:100'],
            'account_type' => ['nullable', 'string', 'max:100'],
            'purpose' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['status'] = $request->boolean('status');

        $bankAccount->update($validated);

        return redirect()
            ->route('admin.bank-accounts.index')
            ->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        if ($bankAccount->feeConfigurations()->exists()) {
            return back()->with(
                'error',
                'This bank account cannot be deleted because it is being used by fee configurations.'
            );
        }

        $bankAccount->delete();

        return back()->with(
            'success',
            'Bank account deleted successfully.'
        );
    }
}