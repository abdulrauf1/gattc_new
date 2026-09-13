<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Event;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $courses = collect();
        $categories = collect();
        $announcements = collect();
        $events = collect();

        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('courses')) {
            $courses = Course::query()
                ->where('status', true)
                ->latest()
                ->take(6)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Course Categories
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('course_categories')) {
            $categories = CourseCategory::query()
                ->where('status', true)
                ->withCount([
                    'courses' => function ($query) {
                        $query->where('status', true);
                    }
                ])
                ->orderBy('name')
                ->take(6)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('announcements')) {
            $announcements = Announcement::query()
                ->where('status', true)
                ->latest()
                ->take(3)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('events')) {
            $events = Event::query()
                ->where('status', true)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('welcome', [
            'courses' => $courses,
            'categories' => $categories,
            'announcements' => $announcements,
            'events' => $events,
        ]);
    }
}