<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSession;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionSessionController extends Controller
{
    public function index()
    {
        $sessions = AdmissionSession::withCount('courses')
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.admission-sessions.index',
            compact('sessions')
        );
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
        $validated = $this->validateSession($request);

        $courseIds = $request->input('courses', []);

        DB::transaction(function () use (
            $validated,
            $courseIds
        ) {
            $session = AdmissionSession::create($validated);

            $session->courses()->sync($courseIds);

            /*
             * Automatically make this session the open session
             * when requested.
             */
            if ($session->is_open) {
                AdmissionSession::where('id', '!=', $session->id)
                    ->update(['is_open' => false]);
            }
        });

        return redirect()
            ->route('admin.admission-sessions.index')
            ->with(
                'success',
                'Admission session created successfully.'
            );
    }

    public function edit(AdmissionSession $admissionSession)
    {
        $courses = Course::where('status', true)
            ->orderBy('title')
            ->get();

        $selectedCourses = $admissionSession
            ->courses()
            ->pluck('courses.id')
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

    public function update(
        Request $request,
        AdmissionSession $admissionSession
    ) {
        $validated = $this->validateSession($request);

        $courseIds = $request->input('courses', []);

        DB::transaction(function () use (
            $validated,
            $courseIds,
            $admissionSession
        ) {
            $admissionSession->update($validated);

            $admissionSession->courses()->sync($courseIds);

            if ($admissionSession->is_open) {
                AdmissionSession::where(
                    'id',
                    '!=',
                    $admissionSession->id
                )->update([
                    'is_open' => false,
                ]);
            }
        });

        return redirect()
            ->route('admin.admission-sessions.index')
            ->with(
                'success',
                'Admission session updated successfully.'
            );
    }

    public function destroy(
        AdmissionSession $admissionSession
    ) {
        if (
            $admissionSession->admissions()->exists() ||
            $admissionSession->vouchers()->exists()
        ) {
            return back()->with(
                'error',
                'This admission session cannot be deleted because it has admissions or vouchers.'
            );
        }

        $admissionSession->delete();

        return back()->with(
            'success',
            'Admission session deleted successfully.'
        );
    }

    public function open(
        AdmissionSession $admissionSession
    ) {
        DB::transaction(function () use (
            $admissionSession
        ) {
            AdmissionSession::where(
                'id',
                '!=',
                $admissionSession->id
            )->update([
                'is_open' => false,
            ]);

            $admissionSession->update([
                'is_open' => true,
            ]);
        });

        return back()->with(
            'success',
            'Admission session opened successfully.'
        );
    }

    public function close(
        AdmissionSession $admissionSession
    ) {
        $admissionSession->update([
            'is_open' => false,
        ]);

        return back()->with(
            'success',
            'Admission session closed successfully.'
        );
    }

    private function validateSession(
        Request $request
    ): array {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'opening_date' => [
                'required',
                'date',
            ],

            'closing_date' => [
                'required',
                'date',
                'after_or_equal:opening_date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_open' => [
                'nullable',
                'boolean',
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
    }
}