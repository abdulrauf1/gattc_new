<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with('category')
            ->withCount('batches')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->course_category_id, function ($query, $categoryId) {
                $query->where('course_category_id', $categoryId);
            })
            ->when($request->has('status') && $request->status !== '', function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        $categories = CourseCategory::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.courses.index', compact(
            'courses',
            'categories'
        ));
    }

    public function create()
    {
        $categories = CourseCategory::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.courses.create',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_category_id' => [
                'nullable',
                'exists:course_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                'unique:courses,code',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'duration' => [
                'nullable',
                'string',
                'max:100',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['featured'] = $request->boolean('featured');
        $validated['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $categories = CourseCategory::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.courses.edit',
            compact('course', 'categories')
        );
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_category_id' => [
                'nullable',
                'exists:course_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('courses', 'code')
                    ->ignore($course->id),
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'duration' => [
                'nullable',
                'string',
                'max:100',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['featured'] = $request->boolean('featured');
        $validated['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->batches()->exists()) {
            return back()->with(
                'error',
                'This course cannot be deleted because it has batches.'
            );
        }

        if ($course->admissionSessions()->exists()) {
            return back()->with(
                'error',
                'This course cannot be deleted because it is assigned to an admission session.'
            );
        }

        $course->delete();

        return back()->with(
            'success',
            'Course deleted successfully.'
        );
    }
}