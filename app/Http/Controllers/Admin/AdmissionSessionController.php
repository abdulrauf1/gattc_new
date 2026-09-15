<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSession;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionSessionController extends Controller
{
    /**
     * Display all admission sessions.
     */
    public function index()
    {
        $admissionSessions = AdmissionSession::query()
            ->withCount('courses')
            ->withCount('admissions')
            ->withCount('vouchers')
            ->orderByDesc('opening_date')
            ->get();

        return view(
            'admin.admission-sessions.index',
            compact('admissionSessions')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $courses = Course::query()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view(
            'admin.admission-sessions.create',
            compact('courses')
        );
    }

    /**
     * Store a new admission session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'opening_date' => [
                'required',
                'date',
            ],

            'closing_date' => [
                'required',
                'date',
                'after:opening_date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'courses' => [
                'nullable',
                'array',
            ],

            'courses.*' => [
                'integer',
                'exists:courses,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Session
        |--------------------------------------------------------------------------
        */
        $session = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | If this session is created as open, close all other sessions.
            |--------------------------------------------------------------------------
            */
            if ($request->boolean('is_open')) {
                AdmissionSession::query()
                    ->where('is_open', true)
                    ->update([
                        'is_open' => false,
                    ]);
            }

            $session = AdmissionSession::create([
                'title' => $validated['title'],
                'opening_date' => $validated['opening_date'],
                'closing_date' => $validated['closing_date'],
                'is_open' => $request->boolean('is_open'),
                'description' => $validated['description'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Attach selected courses
            |--------------------------------------------------------------------------
            */
            $courseIds = $validated['courses'] ?? [];

            if (!empty($courseIds)) {
                $session->courses()->sync($courseIds);
            }

            return $session;
        });

        return redirect()
            ->route('admin.admission-sessions.show', $session)
            ->with(
                'success',
                'Admission session created successfully.'
            );
    }

    /**
     * Display a specific admission session.
     */
    public function show(AdmissionSession $admissionSession)
    {
        $admissionSession->load([
            'courses' => function ($query) {
                $query->orderBy('sort_order')
                    ->orderBy('title');
            },
        ]);

        $admissionCount = $admissionSession->admissions()->count();

        $voucherCount = $admissionSession->vouchers()->count();

        return view(
            'admin.admission-sessions.show',
            compact(
                'admissionSession',
                'admissionCount',
                'voucherCount'
            )
        );
    }

    /**
     * Show edit form.
     */
    public function edit(AdmissionSession $admissionSession)
    {
        $courses = Course::query()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $admissionSession->load('courses');

        $selectedCourses = $admissionSession
            ->courses
            ->pluck('id')
            ->toArray();

        return view(
            'admin.admission-sessions.edit',
            compact(
                'admissionSession',
                'courses',
                'selectedCourses'
            )
        );
    }

    /**
     * Update an admission session.
     */
    public function update(
        Request $request,
        AdmissionSession $admissionSession
    ) {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'opening_date' => [
                'required',
                'date',
            ],

            'closing_date' => [
                'required',
                'date',
                'after:opening_date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'courses' => [
                'nullable',
                'array',
            ],

            'courses.*' => [
                'integer',
                'exists:courses,id',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated,
            $admissionSession
        ) {

            /*
            |--------------------------------------------------------------------------
            | If this session is being opened, close all others.
            |--------------------------------------------------------------------------
            */
            if ($request->boolean('is_open')) {

                AdmissionSession::query()
                    ->where('id', '!=', $admissionSession->id)
                    ->where('is_open', true)
                    ->update([
                        'is_open' => false,
                    ]);
            }

            $admissionSession->update([
                'title' => $validated['title'],
                'opening_date' => $validated['opening_date'],
                'closing_date' => $validated['closing_date'],
                'is_open' => $request->boolean('is_open'),
                'description' => $validated['description'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update session courses
            |--------------------------------------------------------------------------
            */
            $admissionSession->courses()->sync(
                $validated['courses'] ?? []
            );
        });

        return redirect()
            ->route(
                'admin.admission-sessions.show',
                $admissionSession
            )
            ->with(
                'success',
                'Admission session updated successfully.'
            );
    }

    /**
     * Delete admission session.
     */
    public function destroy(AdmissionSession $admissionSession)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deletion if the session has admissions/vouchers.
        |--------------------------------------------------------------------------
        */
        if ($admissionSession->admissions()->exists()) {

            return back()->with(
                'error',
                'This admission session cannot be deleted because admissions already exist for it.'
            );
        }

        if ($admissionSession->vouchers()->exists()) {

            return back()->with(
                'error',
                'This admission session cannot be deleted because vouchers already exist for it.'
            );
        }

        DB::transaction(function () use ($admissionSession) {

            /*
            | Detach courses first.
            */
            $admissionSession->courses()->detach();

            $admissionSession->delete();
        });

        return redirect()
            ->route('admin.admission-sessions.index')
            ->with(
                'success',
                'Admission session deleted successfully.'
            );
    }

    /**
     * Open admission session.
     *
     * Only one session can be open at a time.
     */
    public function open(AdmissionSession $admissionSession)
    {
        DB::transaction(function () use ($admissionSession) {

            AdmissionSession::query()
                ->where('id', '!=', $admissionSession->id)
                ->where('is_open', true)
                ->update([
                    'is_open' => false,
                ]);

            $admissionSession->update([
                'is_open' => true,
            ]);
        });

        return back()->with(
            'success',
            'Admission session opened successfully. Any previously open session has been closed.'
        );
    }

    /**
     * Close admission session.
     */
    public function close(AdmissionSession $admissionSession)
    {
        $admissionSession->update([
            'is_open' => false,
        ]);

        return back()->with(
            'success',
            'Admission session closed successfully.'
        );
    }
}