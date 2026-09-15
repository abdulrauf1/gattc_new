<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Course;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admissions List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Admission::query()
            ->with([
                'course',
                'session',
            ])
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('admission_no', 'like', "%{$search}%")
                    ->orWhere('student_name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('cnic', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Course
        |--------------------------------------------------------------------------
        */
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $admissions = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalAdmissions = Admission::count();

        $approvedAdmissions = Admission::where(
            'status',
            'approved'
        )->count();

        $pendingAdmissions = Admission::where(
            'status',
            'pending'
        )->count();

        $rejectedAdmissions = Admission::where(
            'status',
            'rejected'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Course Filter
        |--------------------------------------------------------------------------
        */
        $courses = Course::query()
            ->orderBy('title')
            ->get([
                'id',
                'title',
            ]);

        return view(
            'admin.admissions.index',
            compact(
                'admissions',
                'courses',
                'totalAdmissions',
                'approvedAdmissions',
                'pendingAdmissions',
                'rejectedAdmissions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Admission
    |--------------------------------------------------------------------------
    */
    public function show(Admission $admission)
    {
        $admission->load([
            'course.bankAccount',
            'course.category',
            'session',
            'vouchers' => function ($query) {
                $query->latest();
            },
        ]);

        return view(
            'admin.admissions.show',
            compact('admission')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Admission Status
    |--------------------------------------------------------------------------
    |
    | Used by administration after manually checking:
    |
    | - Application form
    | - Deposited bank slip
    | - Student documents
    |
    */
    public function updateStatus(
        Request $request,
        Admission $admission
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,approved,rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $admission->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.admissions.show',
                $admission
            )
            ->with(
                'success',
                'Admission status updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Admission From Paid Voucher
    |--------------------------------------------------------------------------
    */
    public function createFromVoucher(
        Request $request,
        Voucher $voucher
    ) {
        /*
        |--------------------------------------------------------------------------
        | Only admission vouchers
        |--------------------------------------------------------------------------
        */
        if ($voucher->voucher_type !== 'admission') {

            return back()->with(
                'error',
                'Only admission vouchers can be converted into an admission.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment must be approved
        |--------------------------------------------------------------------------
        */
        if ($voucher->status !== 'paid') {

            return back()->with(
                'error',
                'This voucher cannot be finalized because its payment has not been approved.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate admission
        |--------------------------------------------------------------------------
        */
        if ($voucher->admission_id) {

            return redirect()
                ->route(
                    'admin.admissions.show',
                    $voucher->admission_id
                )
                ->with(
                    'error',
                    'This voucher has already been converted into an admission.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Required relationships
        |--------------------------------------------------------------------------
        */
        if (!$voucher->course_id) {

            return back()->with(
                'error',
                'The voucher does not have a course assigned.'
            );
        }

        if (!$voucher->admission_session_id) {

            return back()->with(
                'error',
                'The voucher does not have an admission session assigned.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create admission
        |--------------------------------------------------------------------------
        */
        $admission = DB::transaction(function () use ($voucher) {

            /*
            |--------------------------------------------------------------------------
            | Generate admission number
            |--------------------------------------------------------------------------
            */
            $admissionNo = 'GATTC-' .
                now()->format('Y') .
                '-' .
                str_pad(
                    (string) $voucher->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );

            while (
                Admission::where(
                    'admission_no',
                    $admissionNo
                )->exists()
            ) {

                $admissionNo = 'GATTC-' .
                    now()->format('Y') .
                    '-' .
                    str_pad(
                        (string) random_int(
                            1,
                            999999
                        ),
                        6,
                        '0',
                        STR_PAD_LEFT
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Create admission
            |--------------------------------------------------------------------------
            |
            | It starts as pending so the administration can manually
            | verify the physical application and deposited bank slip.
            |
            */
            $admission = Admission::create([

                'admission_no' =>
                    $admissionNo,

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

                'status' =>
                    'pending',

                'remarks' =>
                    'Admission created from paid voucher ' .
                    $voucher->voucher_no .
                    '. Awaiting manual document verification.',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Link voucher
            |--------------------------------------------------------------------------
            */
            $voucher->update([
                'admission_id' => $admission->id,
            ]);

            return $admission;
        });

        return redirect()
            ->route(
                'admin.admissions.show',
                $admission
            )
            ->with(
                'success',
                'Admission record created successfully and is awaiting administrative verification.'
            );
    }
}