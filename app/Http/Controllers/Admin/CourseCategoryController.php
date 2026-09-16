<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query()
            ->withCount('courses')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                (bool) $request->status
            );
        }

        $categories = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.course-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view(
            'admin.course-categories.create'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:course_categories,name',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        CourseCategory::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'description' =>
                $validated['description'] ?? null,
            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.course-categories.index')
            ->with(
                'success',
                'Course category created successfully.'
            );
    }

    public function show(
        CourseCategory $courseCategory
    ) {
        $courseCategory->load([
            'courses' => function ($query) {
                $query
                    ->with('bankAccount')
                    ->orderBy('title');
            },
        ]);

        return view(
            'admin.course-categories.show',
            compact('courseCategory')
        );
    }

    public function edit(
        CourseCategory $courseCategory
    ) {
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
                'unique:course_categories,name,' .
                    $courseCategory->id,
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $courseCategory->update([
            'name' =>
                $validated['name'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['name'],
                    $courseCategory->id
                ),

            'description' =>
                $validated['description'] ?? null,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route(
                'admin.course-categories.index'
            )
            ->with(
                'success',
                'Course category updated successfully.'
            );
    }

    public function destroy(
        CourseCategory $courseCategory
    ) {
        if (
            $courseCategory->courses()->exists()
        ) {
            return back()->withErrors([
                'category' =>
                    'This category cannot be deleted because courses are assigned to it.',
            ]);
        }

        $courseCategory->delete();

        return redirect()
            ->route(
                'admin.course-categories.index'
            )
            ->with(
                'success',
                'Course category deleted successfully.'
            );
    }

    private function uniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            CourseCategory::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($q) => $q->where(
                        'id',
                        '!=',
                        $ignoreId
                    )
                )
                ->exists()
        ) {
            $slug =
                $base .
                '-' .
                $counter++;
        }

        return $slug;
    }
}