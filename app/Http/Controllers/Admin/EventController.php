<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        | Your current events table does not contain:
        | - short_description
        | - location
        |
        | Therefore we only search the confirmed "title" column here.
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where('title', 'like', "%{$search}%");
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', (bool) $request->status);
        }

        $events = $query
            ->latest('event_date')
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Tab Counts
        |--------------------------------------------------------------------------
        */
        $eventTotal = Event::count();

        $eventPublished = Event::where('status', true)->count();

        $eventHidden = Event::where('status', false)->count();

        return view('admin.events.index', compact(
            'events',
            'eventTotal',
            'eventPublished',
            'eventHidden'
        ));
    }


    public function create()
    {
        return view(
            'admin.events.create'
        );
    }


    public function store(Request $request)
    {
        $validated =
            $this->validateEvent(
                $request
            );

        Event::create([

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title']
                ),

            'short_description' =>
                $validated['short_description']
                ?? null,

            'description' =>
                $validated['description']
                ?? null,

            'event_date' =>
                $validated['event_date'],

            'location' =>
                $validated['location']
                ?? null,

            'status' =>
                $request->boolean(
                    'status'
                ),
        ]);

        return redirect()
            ->route(
                'admin.events.index'
            )
            ->with(
                'success',
                'Event created successfully.'
            );
    }


    public function show(Event $event)
    {
        return view(
            'admin.events.show',
            compact('event')
        );
    }


    public function edit(Event $event)
    {
        return view(
            'admin.events.edit',
            compact('event')
        );
    }


    public function update(
        Request $request,
        Event $event
    ) {
        $validated =
            $this->validateEvent(
                $request
            );

        $event->update([

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title'],
                    $event->id
                ),

            'short_description' =>
                $validated['short_description']
                ?? null,

            'description' =>
                $validated['description']
                ?? null,

            'event_date' =>
                $validated['event_date'],

            'location' =>
                $validated['location']
                ?? null,

            'status' =>
                $request->boolean(
                    'status'
                ),
        ]);

        return redirect()
            ->route(
                'admin.events.index'
            )
            ->with(
                'success',
                'Event updated successfully.'
            );
    }


    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route(
                'admin.events.index'
            )
            ->with(
                'success',
                'Event deleted successfully.'
            );
    }


    private function validateEvent(
        Request $request
    ): array {

        return $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
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

        $base =
            Str::slug($title);

        $slug =
            $base;

        $counter = 1;


        while (
            Event::where(
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