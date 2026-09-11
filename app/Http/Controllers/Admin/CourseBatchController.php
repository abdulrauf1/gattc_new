<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseBatch;
use Illuminate\Http\Request;

class CourseBatchController extends Controller
{
    public function index(Request $request)
    {
        $courseBatches = CourseBatch::with('course')
            ->when($request->search, function ($query, $search) {
                $query->where('batch_name', 'like', "%{$search}%");
            })
            ->when($request->course_id, function ($query, $courseId) {
                $query->where('course_id', $courseId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view('admin.course-batches.index', compact(
            'courseBatches',
            'courses'
        ));
    }

    public function create()
    {
        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.course-batches.create',
            compact('courses')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'batch_name' => [
                'required',
                'string',
                'max:255',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:upcoming,open,ongoing,completed,cancelled',
            ],
        ]);

        CourseBatch::create($validated);

        return redirect()
            ->route('admin.course-batches.index')
            ->with('success', 'Course batch created successfully.');
    }

    public function edit(CourseBatch $courseBatch)
    {
        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        return view(
            'admin.course-batches.edit',
            compact('courseBatch', 'courses')
        );
    }

    public function update(
        Request $request,
        CourseBatch $courseBatch
    ) {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'batch_name' => [
                'required',
                'string',
                'max:255',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:upcoming,open,ongoing,completed,cancelled',
            ],
        ]);

        $courseBatch->update($validated);

        return redirect()
            ->route('admin.course-batches.index')
            ->with('success', 'Course batch updated successfully.');
    }

    public function destroy(CourseBatch $courseBatch)
    {
        $courseBatch->delete();

        return back()->with(
            'success',
            'Course batch deleted successfully.'
        );
    }
}