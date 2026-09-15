<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query()
            ->with(['category', 'bankAccount'])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere(
                                'course_type',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('type'),
                fn ($query) =>
                    $query->where(
                        'course_type',
                        $request->type
                    )
            )
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::where('status', true)
            ->orderBy('name')
            ->get();

        $bankAccounts = BankAccount::where('status', true)
            ->orderBy('account_title')
            ->get();

        return view('admin.courses.create', compact(
            'categories',
            'bankAccounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCourse($request);

        $validated['slug'] = $this->uniqueSlug(
            $validated['title']
        );

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

        $bankAccounts = BankAccount::where('status', true)
            ->orderBy('account_title')
            ->get();

        return view('admin.courses.edit', compact(
            'course',
            'categories',
            'bankAccounts'
        ));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $this->validateCourse(
            $request,
            $course
        );

        /*
         * Update slug only if title changed.
         */
        if ($course->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug(
                $validated['title'],
                $course->id
            );
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->vouchers()->exists()) {
            return back()->with(
                'error',
                'This course cannot be deleted because vouchers already exist for it.'
            );
        }

        $course->delete();

        return back()->with(
            'success',
            'Course deleted successfully.'
        );
    }

    private function validateCourse(
        Request $request,
        ?Course $course = null
    ): array {
        return $request->validate([
            'course_category_id' => [
                'nullable',
                'exists:course_categories,id',
            ],

            'bank_account_id' => [
                'required',
                'exists:bank_accounts,id',
            ],

            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'course_type' => [
                'required',
                Rule::in([
                    'regular',
                    'dit',
                    'private',
                ]),
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

            'eligibility' => [
                'nullable',
                'string',
            ],

            'fee_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'image' => [
                'nullable',
                'string',
                'max:255',
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
    }

    private function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (
            Course::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) =>
                        $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}