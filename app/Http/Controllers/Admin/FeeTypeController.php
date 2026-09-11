<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $feeTypes = FeeType::withCount('feeConfigurations')
            ->latest()
            ->paginate(10);

        return view('admin.fee-types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('admin.fee-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:fee_types,code',
            ],
            'description' => ['nullable', 'string'],
        ]);

        $validated['status'] = $request->boolean('status');

        FeeType::create($validated);

        return redirect()
            ->route('admin.fee-types.index')
            ->with('success', 'Fee type created successfully.');
    }

    public function edit(FeeType $feeType)
    {
        return view(
            'admin.fee-types.edit',
            compact('feeType')
        );
    }

    public function update(
        Request $request,
        FeeType $feeType
    ) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:100',
                'unique:fee_types,code,' . $feeType->id,
            ],

            'description' => ['nullable', 'string'],
        ]);

        $validated['status'] = $request->boolean('status');

        $feeType->update($validated);

        return redirect()
            ->route('admin.fee-types.index')
            ->with('success', 'Fee type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        if ($feeType->feeConfigurations()->exists()) {
            return back()->with(
                'error',
                'This fee type cannot be deleted because it is being used.'
            );
        }

        $feeType->delete();

        return back()->with(
            'success',
            'Fee type deleted successfully.'
        );
    }
}