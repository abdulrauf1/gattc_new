<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\FeeConfiguration;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeConfigurationController extends Controller
{
    public function index()
    {
        $configurations = FeeConfiguration::with([
            'feeType',
            'bankAccount',
            'admissionSession',
            'course',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.fee-configurations.index',
            compact('configurations')
        );
    }

    public function create()
    {
        $feeTypes = FeeType::where('status', true)
            ->orderBy('name')
            ->get();

        $bankAccounts = BankAccount::where('status', true)
            ->orderBy('bank_name')
            ->get();

        $sessions = AdmissionSession::latest()->get();

        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.fee-configurations.create',
            compact(
                'feeTypes',
                'bankAccounts',
                'sessions',
                'courses'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_type_id' => [
                'required',
                'exists:fee_types,id',
            ],

            'bank_account_id' => [
                'required',
                'exists:bank_accounts,id',
            ],

            'admission_session_id' => [
                'nullable',
                'exists:admission_sessions,id',
            ],

            'course_id' => [
                'nullable',
                'exists:courses,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'effective_until' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['mandatory'] =
            $request->boolean('mandatory');

        $validated['status'] =
            $request->boolean('status');

        FeeConfiguration::create($validated);

        return redirect()
            ->route('admin.fee-configurations.index')
            ->with(
                'success',
                'Fee configuration created successfully.'
            );
    }

    public function edit(FeeConfiguration $feeConfiguration)
    {
        $feeTypes = FeeType::where('status', true)
            ->orderBy('name')
            ->get();

        $bankAccounts = BankAccount::where('status', true)
            ->orderBy('bank_name')
            ->get();

        $sessions = AdmissionSession::latest()->get();

        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.fee-configurations.edit',
            compact(
                'feeConfiguration',
                'feeTypes',
                'bankAccounts',
                'sessions',
                'courses'
            )
        );
    }

    public function update(
        Request $request,
        FeeConfiguration $feeConfiguration
    ) {
        $validated = $request->validate([
            'fee_type_id' => [
                'required',
                'exists:fee_types,id',
            ],

            'bank_account_id' => [
                'required',
                'exists:bank_accounts,id',
            ],

            'admission_session_id' => [
                'nullable',
                'exists:admission_sessions,id',
            ],

            'course_id' => [
                'nullable',
                'exists:courses,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'effective_until' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],
        ]);

        $validated['mandatory'] =
            $request->boolean('mandatory');

        $validated['status'] =
            $request->boolean('status');

        $feeConfiguration->update($validated);

        return redirect()
            ->route('admin.fee-configurations.index')
            ->with(
                'success',
                'Fee configuration updated successfully.'
            );
    }

    public function destroy(
        FeeConfiguration $feeConfiguration
    ) {
        if ($feeConfiguration->vouchers()->exists()) {
            return back()->with(
                'error',
                'This configuration cannot be deleted because vouchers already exist.'
            );
        }

        $feeConfiguration->delete();

        return back()->with(
            'success',
            'Fee configuration deleted successfully.'
        );
    }
}