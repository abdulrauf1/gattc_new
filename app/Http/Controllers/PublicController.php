<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Event;
use App\Models\AdmissionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function home()
    {
        $courses = Course::query()
            ->where('status', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = CourseCategory::query()
            ->where('status', true)
            ->latest()
            ->take(6)
            ->get();

        $announcements = Announcement::query()
            ->where('status', true)
            ->latest()
            ->take(3)
            ->get();

        $events = Event::query()
            ->where('status', true)
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact(
            'courses',
            'categories',
            'announcements',
            'events'
        ));
    }

    public function courses()
    {
        $courses = Course::query()
            ->where('status', true)
            ->latest()
            ->paginate(9);

        $categories = CourseCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('public.courses.index', compact(
            'courses',
            'categories'
        ));
    }

    public function facilities()
    {
        return view('public.facilities');
    }

    public function gallery()
    {
        $images = collect();

        if (Schema::hasTable('gallery_images')) {
            $images = DB::table('gallery_images')
                ->latest()
                ->paginate(12);
        }

        return view('public.gallery', compact('images'));
    }

    public function events()
    {
        $events = Event::query()
            ->where('status', true)
            ->latest()
            ->paginate(9);

        return view('public.events', compact('events'));
    }

    public function announcements()
    {
        $announcements = Announcement::query()
            ->where('status', true)
            ->latest()
            ->paginate(10);

        return view('public.announcements', compact('announcements'));
    }

    public function alumni()
    {
        $alumni = Alumni::query()
            ->where('status', true)
            ->latest()
            ->paginate(9);

        return view('public.alumni', compact('alumni'));
    }

    public function alumniRegister()
    {
        return view('public.alumni-register');
    }

    public function storeAlumni(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:alumnis,email'],
            'phone' => ['required', 'string', 'max:30'],
            'course' => ['required', 'string', 'max:150'],
            'graduation_year' => ['required', 'integer', 'min:1950', 'max:' . now()->year],
            'organization' => ['nullable', 'string', 'max:150'],
            'designation' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('alumni', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Pending registration
        |--------------------------------------------------------------------------
        |
        | status = false means the admin must approve the profile first.
        |
        */

        $validated['status'] = false;

        Alumni::create($validated);

        return redirect()
            ->route('public.alumni.register')
            ->with(
                'success',
                'Your alumni registration has been submitted for approval.'
            );
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        if (Schema::hasTable('contact_messages')) {
            DB::table('contact_messages')->insert([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with(
            'success',
            'Thank you. Your message has been submitted successfully.'
        );
    }

    public function admission()
    {
        $activeSession = AdmissionSession::query()
            ->where('is_open', true)
            ->whereDate('opening_date', '<=', now())
            ->whereDate('closing_date', '>=', now())
            ->latest()
            ->first();

        return view('public.admission', compact('activeSession'));
    }
}