<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\FeeDepositDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeDepositDetailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = FeeDepositDetail::query()
            ->with('course')
            ->orderBy('fee_category')
            ->orderBy('course_id')
            ->orderBy('sort_order')
            ->orderBy('id');

        /*
         * Category filter.
         */
        if ($request->filled('fee_category')) {
            $query->where(
                'fee_category',
                $request->fee_category
            );
        }

        /*
         * Course filter.
         */
        if ($request->filled('course_id')) {
            $query->where(
                'course_id',
                $request->course_id
            );
        }

        /*
         * Status.
         */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $details = $query
            ->paginate(30)
            ->withQueryString();

        $courses = Course::query()
            ->where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.fee-deposit-details.index',
            compact(
                'details',
                'courses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $courses = Course::query()
            ->where('status', true)
            ->orderByRaw("
                CASE
                    WHEN course_type = 'regular' THEN 1
                    WHEN course_type = 'dit' THEN 2
                    WHEN course_type = 'private' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('title')
            ->get();

        return view(
            'admin.fee-deposit-details.create',
            compact('courses')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'course_id' => [
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            'fee_category' => [
                'required',
                Rule::in([
                    'regular',
                    'dit',
                    'private',
                    'hostel',
                    'readmission',
                ]),
            ],

            'fee_code' => [
                'required',
                'string',
                'max:80',
            ],

            'fee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'mandatory' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Hostel does not belong to a course.
         */
        if ($validated['fee_category'] === 'hostel') {
            $validated['course_id'] = null;
        }

        /*
         * Regular / DIT / Private must match the selected course type.
         */
        if (
            $validated['course_id'] &&
            in_array(
                $validated['fee_category'],
                [
                    'regular',
                    'dit',
                    'private',
                ],
                true
            )
        ) {
            $course = Course::findOrFail(
                $validated['course_id']
            );

            if (
                $course->course_type !==
                $validated['fee_category']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'fee_category' =>
                            'The fee category must match the selected course type.',
                    ]);
            }
        }

        FeeDepositDetail::create([
            'course_id' =>
                $validated['course_id'] ?? null,

            'fee_category' =>
                $validated['fee_category'],

            'fee_code' =>
                $validated['fee_code'],

            'fee_name' =>
                $validated['fee_name'],

            'amount' =>
                $validated['amount'],

            'mandatory' =>
                $request->boolean('mandatory'),

            'sort_order' =>
                $validated['sort_order'] ?? 0,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.fee-deposit-details.index')
            ->with(
                'success',
                'Fee deposit detail created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(FeeDepositDetail $feeDepositDetail)
    {
        $courses = Course::query()
            ->where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.fee-deposit-details.edit',
            [
                'detail' => $feeDepositDetail,
                'courses' => $courses,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        FeeDepositDetail $feeDepositDetail
    ) {
        $validated = $request->validate([

            'course_id' => [
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            'fee_category' => [
                'required',
                Rule::in([
                    'regular',
                    'dit',
                    'private',
                    'hostel',
                    'readmission',
                ]),
            ],

            'fee_code' => [
                'required',
                'string',
                'max:80',
            ],

            'fee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'mandatory' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($validated['fee_category'] === 'hostel') {
            $validated['course_id'] = null;
        }

        if (
            $validated['course_id'] &&
            in_array(
                $validated['fee_category'],
                [
                    'regular',
                    'dit',
                    'private',
                ],
                true
            )
        ) {
            $course = Course::findOrFail(
                $validated['course_id']
            );

            if (
                $course->course_type !==
                $validated['fee_category']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'fee_category' =>
                            'The fee category must match the selected course type.',
                    ]);
            }
        }

        $feeDepositDetail->update([
            'course_id' =>
                $validated['course_id'] ?? null,

            'fee_category' =>
                $validated['fee_category'],

            'fee_code' =>
                $validated['fee_code'],

            'fee_name' =>
                $validated['fee_name'],

            'amount' =>
                $validated['amount'],

            'mandatory' =>
                $request->boolean('mandatory'),

            'sort_order' =>
                $validated['sort_order'] ?? 0,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.fee-deposit-details.index')
            ->with(
                'success',
                'Fee deposit detail updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        FeeDepositDetail $feeDepositDetail
    ) {
        $feeDepositDetail->delete();

        return redirect()
            ->route('admin.fee-deposit-details.index')
            ->with(
                'success',
                'Fee deposit detail deleted successfully.'
            );
    }
}