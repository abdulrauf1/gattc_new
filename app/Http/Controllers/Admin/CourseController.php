<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->with([
                'category',
                'bankAccount',
            ])
            ->withCount('admissions')
            ->orderBy('sort_order')
            ->orderBy('title');

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'slug',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('course_type')) {

            $query->where(
                'course_type',
                $request->course_type
            );
        }

        if ($request->filled('category_id')) {

            $query->where(
                'course_category_id',
                $request->category_id
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                (bool) $request->status
            );
        }

        $courses =
            $query
                ->paginate(15)
                ->withQueryString();

        $categories =
            CourseCategory::where(
                'status',
                true
            )->orderBy('name')->get();

        return view(
            'admin.courses.index',
            compact(
                'courses',
                'categories'
            )
        );
    }

    public function create()
    {
        $categories =
            CourseCategory::where(
                'status',
                true
            )->orderBy('name')->get();

        $bankAccounts =
            BankAccount::where(
                'status',
                true
            )
            ->orderBy('purpose')
            ->get();

        return view(
            'admin.courses.create',
            compact(
                'categories',
                'bankAccounts'
            )
        );
    }

    public function store(Request $request)
    {
        $validated =
            $this->validateCourse(
                $request
            );

        Course::create([

            'course_category_id' =>
                $validated['course_category_id'],

            'bank_account_id' =>
                $validated['bank_account_id'],

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title']
                ),

            'course_type' =>
                $validated['course_type'],

            'description' =>
                $validated['description']
                ?? null,

            'duration' =>
                $validated['duration']
                ?? null,

            'eligibility' =>
                $validated['eligibility']
                ?? null,

            'fee_amount' =>
                $validated['fee_amount'],

            'image' =>
                $this->uploadImage(
                    $request
                ),

            'sort_order' =>
                $validated['sort_order']
                ?? 0,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route(
                'admin.courses.index'
            )
            ->with(
                'success',
                'Course created successfully.'
            );
    }

    public function show(Course $course)
    {
        $course->load([
            'category',
            'bankAccount',
            'sessions',
            'admissions' => function ($query) {
                $query->latest()->limit(20);
            },
        ]);

        return view(
            'admin.courses.show',
            compact('course')
        );
    }

    public function edit(Course $course)
    {
        $categories =
            CourseCategory::where(
                'status',
                true
            )->orderBy('name')->get();

        $bankAccounts =
            BankAccount::where(
                'status',
                true
            )->orderBy('purpose')->get();

        return view(
            'admin.courses.edit',
            compact(
                'course',
                'categories',
                'bankAccounts'
            )
        );
    }

    public function update(
        Request $request,
        Course $course
    ) {
        $validated =
            $this->validateCourse(
                $request
            );

        $image =
            $course->image;

        if ($request->hasFile('image')) {

            if (
                $image &&
                Storage::disk('public')
                    ->exists($image)
            ) {
                Storage::disk('public')
                    ->delete($image);
            }

            $image =
                $this->uploadImage(
                    $request
                );
        }

        $course->update([

            'course_category_id' =>
                $validated['course_category_id'],

            'bank_account_id' =>
                $validated['bank_account_id'],

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title'],
                    $course->id
                ),

            'course_type' =>
                $validated['course_type'],

            'description' =>
                $validated['description']
                ?? null,

            'duration' =>
                $validated['duration']
                ?? null,

            'eligibility' =>
                $validated['eligibility']
                ?? null,

            'fee_amount' =>
                $validated['fee_amount'],

            'image' =>
                $image,

            'sort_order' =>
                $validated['sort_order']
                ?? 0,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route(
                'admin.courses.index'
            )
            ->with(
                'success',
                'Course updated successfully.'
            );
    }

    public function destroy(Course $course)
    {
        if (
            $course->admissions()->exists() ||
            $course->vouchers()->exists()
        ) {
            return back()->withErrors([
                'course' =>
                    'This course cannot be deleted because it is already used in admissions or vouchers. Deactivate it instead.',
            ]);
        }

        if (
            $course->image &&
            Storage::disk('public')
                ->exists($course->image)
        ) {
            Storage::disk('public')
                ->delete($course->image);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with(
                'success',
                'Course deleted successfully.'
            );
    }

    private function validateCourse(
        Request $request
    ): array {
        return $request->validate([

            'course_category_id' => [
                'required',
                'exists:course_categories,id',
            ],

            'bank_account_id' => [
                'required',
                'exists:bank_accounts,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'course_type' => [
                'required',
                'in:regular,dit,private',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'duration' => [
                'nullable',
                'string',
                'max:100',
            ],

            'eligibility' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'fee_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
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

    private function uploadImage(
        Request $request
    ): ?string {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $request
            ->file('image')
            ->store(
                'courses',
                'public'
            );
    }

    private function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $base =
            Str::slug($title);

        $slug =
            $base;

        $counter = 1;

        while (
            Course::where(
                'slug',
                $slug
            )
            ->when(
                $ignoreId,
                fn ($q) =>
                    $q->where(
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