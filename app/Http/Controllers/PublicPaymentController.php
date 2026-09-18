<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublicPaymentController extends Controller
{
    /**
     * Submit deposited bank payment details and slip.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_id' => [
                'required',
                'exists:vouchers,id',
            ],

            'deposit_slip_no' => [
                'required',
                'string',
                'max:100',
            ],

            'bank_transaction_no' => [
                'nullable',
                'string',
                'max:150',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:50',
            ],

            'payment_slip' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:4096',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $voucher = Voucher::query()
            ->with('course')
            ->findOrFail($validated['voucher_id']);

        /*
        |--------------------------------------------------------------------------
        | Do not allow a payment to be submitted for an already-paid voucher.
        |--------------------------------------------------------------------------
        */

        if ($voucher->status === 'paid') {
            return back()->with(
                'error',
                'This voucher has already been marked as paid.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Do not duplicate an already pending payment.
        |--------------------------------------------------------------------------
        */

        $existingPending = FeePayment::query()
            ->where('voucher_id', $voucher->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return back()->with(
                'error',
                'A payment for this voucher is already pending verification.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Amount always comes from the voucher.
        |--------------------------------------------------------------------------
        */

        $amount = $voucher->amount;

        /*
        |--------------------------------------------------------------------------
        | Store slip.
        |--------------------------------------------------------------------------
        */

        $paymentSlip = $request
            ->file('payment_slip')
            ->store('payments/slips', 'public');

        try {

            DB::transaction(function () use (
                $voucher,
                $validated,
                $paymentSlip,
                $amount
            ) {

                FeePayment::create([
                    'voucher_id' => $voucher->id,

                    'deposit_slip_no' =>
                        $validated['deposit_slip_no'],

                    'bank_transaction_no' =>
                        $validated['bank_transaction_no'] ?? null,

                    'payment_date' =>
                        $validated['payment_date'],

                    'amount' => $amount,

                    'payment_method' =>
                        $validated['payment_method'] ?? 'bank',

                    'payment_slip' =>
                        $paymentSlip,

                    'status' => 'pending',

                    'verified_at' => null,

                    'remarks' =>
                        $validated['remarks'] ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Voucher remains generated until admin approves payment.
                |--------------------------------------------------------------------------
                */

                if ($voucher->status !== 'generated') {
                    $voucher->update([
                        'status' => 'generated',
                    ]);
                }
            });

        } catch (\Throwable $exception) {

            Storage::disk('public')->delete($paymentSlip);

            throw $exception;
        }

        return redirect()
            ->route(
                'public.admission.voucher',
                $voucher
            )
            ->with(
                'success',
                'Your payment details and bank slip have been submitted. GATTC administration will verify the payment.'
            );
    }
}