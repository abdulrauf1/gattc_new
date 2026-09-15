<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::withCount('courses')
            ->orderBy('name')
            ->paginate(20);

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
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['slug'] = $this->uniqueSlug(
            $validated['name']
        );

        CourseCategory::create($validated);

        return redirect()
            ->route('admin.course-categories.index')
            ->with(
                'success',
                'Course category created successfully.'
            );
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
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($courseCategory->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug(
                $validated['name'],
                $courseCategory->id
            );
        }

        $courseCategory->update($validated);

        return redirect()
            ->route('admin.course-categories.index')
            ->with(
                'success',
                'Course category updated successfully.'
            );
    }

    public function destroy(
        CourseCategory $courseCategory
    ) {
        if ($courseCategory->courses()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because courses are using it.'
            );
        }

        $courseCategory->delete();

        return back()->with(
            'success',
            'Course category deleted successfully.'
        );
    }

    private function uniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (
            CourseCategory::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($q) =>
                        $q->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}