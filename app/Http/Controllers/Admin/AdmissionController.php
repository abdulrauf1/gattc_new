<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AdmissionSession;
use App\Models\Course;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $admissions = Admission::query()
            ->with([
                'course',
                'session',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'admission_no',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'student_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'cnic',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($q) =>
                    $q->where(
                        'status',
                        $request->status
                    )
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.admissions.index',
            compact('admissions')
        );
    }

    public function show(Admission $admission)
    {
        $admission->load([
            'course',
            'session',
            'vouchers',
        ]);

        return view(
            'admin.admissions.show',
            compact('admission')
        );
    }

    /**
     * Convert a paid admission voucher into an admission.
     */
    public function createFromVoucher(
        Voucher $voucher
    ) {
        if ($voucher->voucher_type !== 'admission') {
            return back()->with(
                'error',
                'This voucher is not an admission voucher.'
            );
        }

        if ($voucher->status !== 'paid') {
            return back()->with(
                'error',
                'Only paid vouchers can be converted into admissions.'
            );
        }

        if ($voucher->admission_id) {
            return redirect()
                ->route(
                    'admin.admissions.show',
                    $voucher->admission_id
                )
                ->with(
                    'info',
                    'An admission already exists for this voucher.'
                );
        }

        $admission = DB::transaction(
            function () use ($voucher) {
                $admission = Admission::create([
                    'admission_no' =>
                        $this->generateAdmissionNumber(),

                    'admission_session_id' =>
                        $voucher->admission_session_id,

                    'course_id' =>
                        $voucher->course_id,

                    'student_name' =>
                        $voucher->applicant_name,

                    'father_name' =>
                        $voucher->father_name,

                    'cnic' =>
                        $voucher->cnic,

                    'date_of_birth' =>
                        $voucher->date_of_birth,

                    'gender' =>
                        $voucher->gender,

                    'phone' =>
                        $voucher->phone,

                    'email' =>
                        $voucher->email,

                    'address' =>
                        $voucher->address,

                    'status' => 'approved',

                    'remarks' =>
                        'Admission created after payment verification.',
                ]);

                $voucher->update([
                    'admission_id' => $admission->id,
                ]);

                return $admission;
            }
        );

        return redirect()
            ->route(
                'admin.admissions.show',
                $admission
            )
            ->with(
                'success',
                'Admission created successfully.'
            );
    }

    private function generateAdmissionNumber(): string
    {
        do {
            $number =
                'GATTC-ADM-' .
                now()->format('Y') .
                '-' .
                strtoupper(Str::random(6));
        } while (
            Admission::where(
                'admission_no',
                $number
            )->exists()
        );

        return $number;
    }
}