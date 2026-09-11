<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::withCount('courses')
            ->latest()
            ->paginate(15);

        return view(
            'admin.course-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.course-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:course_categories,name'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status');

        CourseCategory::create($validated);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', 'Course category created successfully.');
    }

    public function edit(CourseCategory $courseCategory)
    {
        return view(
            'admin.course-categories.edit',
            compact('courseCategory')
        );
    }

    public function update(
        Request $request,
        CourseCategory $courseCategory
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('course_categories', 'name')
                    ->ignore($courseCategory->id),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status');

        $courseCategory->update($validated);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', 'Course category updated successfully.');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        if ($courseCategory->courses()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because it contains courses.'
            );
        }

        $courseCategory->delete();

        return back()->with(
            'success',
            'Course category deleted successfully.'
        );
    }
}