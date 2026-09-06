<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdmissionService
{
    public function __construct(
        protected NumberService $numberService
    ) {
    }

    /**
     * Generate application number.
     */
    public function generateApplicationNumber(): string
    {
        return $this->numberService->generate(
            'application',
            'GATTC-A'
        );
    }

    /**
     * Finalize an admission after verified payment.
     */
    public function finalize(
        Admission $admission,
        User $user
    ): Admission {

        return DB::transaction(function () use (
            $admission,
            $user
        ) {

            if ($admission->admission_status === 'admitted') {
                return $admission;
            }

            if (!$admission->fee_verified_at) {
                throw ValidationException::withMessages([
                    'admission' =>
                        'Admission cannot be finalized before fee verification.',
                ]);
            }

            if (!$admission->admission_no) {
                $admission->admission_no =
                    $this->numberService->generate(
                        'admission',
                        'GATTC'
                    );
            }

            $admission->update([
                'admission_no' => $admission->admission_no,

                'admission_status' => 'admitted',

                'admitted_at' => now(),

                'admitted_by' => $user->id,

                'status' => 'enrolled',
            ]);

            return $admission->fresh();
        });
    }
}