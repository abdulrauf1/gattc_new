<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\AdmissionSession;
use Illuminate\Http\Request;

class AdmissionSessionController extends Controller
{
    public function index()
    {
        $sessions = AdmissionSession::latest()->paginate(10);

        return view('admin.admission-sessions.index', compact('sessions'));
    }

    public function create()
    {
        $courses = Course::where('status', true)
        ->orderBy('title')
        ->get();

        return view(
            'admin.admission-sessions.create',
            compact('courses')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'session_code' => ['required', 'string', 'max:100', 'unique:admission_sessions,session_code'],
            'opening_date' => ['required', 'date'],
            'closing_date' => ['required', 'date', 'after:opening_date'],
            'description' => ['nullable', 'string'],
            'courses' => ['nullable', 'array'],
            'courses.*' => ['exists:courses,id'],
        ]);

        $courseIds = $validated['courses'] ?? [];

        unset($validated['courses']);

        $validated['is_open'] = false;

        $session = AdmissionSession::create($validated);

        $session->courses()->sync($courseIds);

        return redirect()
            ->route('admin.admission-sessions.index')
            ->with('success', 'Admission session created successfully.');
    }

    public function edit(AdmissionSession $admissionSession)
    {
        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        $selectedCourses = $admissionSession
            ->courses
            ->pluck('id')
            ->toArray();

        return view(
            'admin.admission-sessions.edit',
            compact('admissionSession', 'courses', 'selectedCourses')
        );
    }

    public function update(Request $request, AdmissionSession $admissionSession)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'session_code' => [
                'required',
                'string',
                'max:100',
                'unique:admission_sessions,session_code,' . $admissionSession->id,
            ],
            'opening_date' => ['required', 'date'],
            'closing_date' => ['required', 'date', 'after:opening_date'],
            'description' => ['nullable', 'string'],
            'courses' => ['nullable', 'array'],
            'courses.*' => ['exists:courses,id'],
        ]);

        $courseIds = $validated['courses'] ?? [];

        unset($validated['courses']);

        $admissionSession->update($validated);

        $admissionSession->courses()->sync($courseIds);

        return redirect()
            ->route('admin.admission-sessions.index')
            ->with('success', 'Admission session updated successfully.');
    }

    public function destroy(AdmissionSession $admissionSession)
    {
        if ($admissionSession->admissions()->exists()) {
            return back()->with(
                'error',
                'This admission session cannot be deleted because applications already exist.'
            );
        }

        $admissionSession->delete();

        return back()->with(
            'success',
            'Admission session deleted successfully.'
        );
    }

    /**
     * Open admission session.
     */
    public function open(AdmissionSession $admissionSession)
    {
        /*
         * Close all other sessions first.
         */
        AdmissionSession::where('id', '!=', $admissionSession->id)
            ->update(['is_open' => false]);

        $admissionSession->update([
            'is_open' => true,
        ]);

        return back()->with(
            'success',
            'Admissions are now open for ' . $admissionSession->name . '.'
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
            'Admissions have been closed.'
        );
    }
}