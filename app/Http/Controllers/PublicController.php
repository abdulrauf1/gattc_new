<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionSession;
use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Home page.
     */
    public function home()
    {
        $settings = $this->settings();

        $activeSession = $this->activeAdmissionSession();

        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | The current courses table does not have a "featured" column.
        |
        */

        $courses = Course::query()
            ->where('status', true)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        */

        $announcements = Announcement::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->latest('id')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        $events = Event::query()
            ->where('status', true)
            ->orderBy('event_date')
            ->orderBy('id')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Gallery
        |--------------------------------------------------------------------------
        */

        $galleryImages = collect();

        $galleries = Gallery::query()
            ->where('status', true)
            ->with([
                'images' => function ($query) {
                    $query
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->take(8);
                },
            ])
            ->latest('id')
            ->take(4)
            ->get();

        foreach ($galleries as $gallery) {
            foreach ($gallery->images as $image) {
                $galleryImages->push($image);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Public statistics
        |--------------------------------------------------------------------------
        */

        $alumniCount = Alumni::query()
            ->where('status', true)
            ->count();

        $studentCount = Admission::query()
            ->where('status', 'approved')
            ->count();

        $courseCount = Course::query()
            ->where('status', true)
            ->count();

        return view('public.home', [
            'settings' => $settings,
            'activeSession' => $activeSession,
            'courses' => $courses,
            'announcements' => $announcements,
            'events' => $events,
            'galleryImages' => $galleryImages->take(8),
            'alumniCount' => $alumniCount,
            'studentCount' => $studentCount,
            'courseCount' => $courseCount,
        ]);
    }

    /**
     * Public course listing.
     */
    public function courses()
    {
        $courses = Course::query()
            ->where('status', true)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('public.courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Single public course.
     */
    public function course(string $slug)
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with('category')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Related courses
        |--------------------------------------------------------------------------
        */

        $relatedCourses = Course::query()
            ->where('status', true)
            ->where('id', '!=', $course->id)
            ->when(
                $course->course_category_id,
                function ($query) use ($course) {
                    $query->where(
                        'course_category_id',
                        $course->course_category_id
                    );
                }
            )
            ->orderBy('sort_order')
            ->orderBy('title')
            ->take(3)
            ->get();

        return view('public.course-details', [
            'course' => $course,
            'relatedCourses' => $relatedCourses,
        ]);
    }

    /**
     * Facilities page.
     */
    public function facilities()
    {
        return view('public.facilities');
    }

    /**
     * Gallery page.
     */
    public function gallery()
    {
        $galleries = Gallery::query()
            ->where('status', true)
            ->with([
                'images' => function ($query) {
                    $query
                        ->orderBy('sort_order')
                        ->orderBy('id');
                },
            ])
            ->latest('id')
            ->get();

        return view('public.gallery', [
            'galleries' => $galleries,
        ]);
    }

    /**
     * Events page.
     */
    public function events()
    {
        $events = Event::query()
            ->where('status', true)
            ->orderBy('event_date')
            ->orderBy('id')
            ->get();

        return view('public.events', [
            'events' => $events,
        ]);
    }

    /**
     * Announcements page.
     */
    public function announcements()
    {
        $announcements = Announcement::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->latest('id')
            ->paginate(9);

        return view('public.announcements', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Alumni page.
     */
    public function alumni()
    {
        $alumni = Alumni::query()
            ->where('status', true)
            ->orderByDesc('graduation_year')
            ->orderBy('name')
            ->paginate(12);

        return view('public.alumni', [
            'alumni' => $alumni,
        ]);
    }

    /**
     * Alumni registration page.
     */
    public function alumniRegister()
    {
        return view('public.alumni-register');
    }

    /**
     * Store alumni registration.
     */
    public function storeAlumni(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:alumni,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'course' => [
                'required',
                'string',
                'max:255',
            ],

            'graduation_year' => [
                'required',
                'integer',
                'min:1950',
                'max:' . (now()->year + 2),
            ],

            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('alumni', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | New alumni registrations require admin approval.
        |--------------------------------------------------------------------------
        */

        $validated['status'] = false;

        Alumni::create($validated);

        return redirect()
            ->route('public.alumni.register')
            ->with(
                'success',
                'Your alumni registration has been submitted successfully and is awaiting administrative approval.'
            );
    }

    /**
     * Contact page.
     */
    public function contact()
    {
        return view('public.contact', [
            'settings' => $this->settings(),
        ]);
    }

    /**
     * Store contact message.
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('public.contact')
            ->with(
                'success',
                'Thank you. Your message has been sent to GATTC administration.'
            );
    }

    /**
     * Get website settings.
     */
    private function settings()
    {
        return WebsiteSetting::query()
            ->pluck('value', 'key');
    }

    /**
     * Get currently active admission session.
     */
    private function activeAdmissionSession(): ?AdmissionSession
    {
        return AdmissionSession::query()
            ->where('is_open', true)
            ->whereDate('opening_date', '<=', today())
            ->whereDate('closing_date', '>=', today())
            ->latest('id')
            ->first();
    }
}