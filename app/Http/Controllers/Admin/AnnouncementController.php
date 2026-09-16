<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query()
            ->latest('published_at')
            ->latest('id');

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'content',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                (bool) $request->status
            );
        }

        $announcements =
            $query
                ->paginate(15)
                ->withQueryString();

        return view(
            'admin.announcements.index',
            compact('announcements')
        );
    }

    public function create()
    {
        return view(
            'admin.announcements.create'
        );
    }

    public function store(Request $request)
    {
        $validated =
            $this->validateAnnouncement(
                $request
            );

        Announcement::create([

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title']
                ),

            'content' =>
                $validated['content'],

            'status' =>
                $request->boolean('status'),

            'published_at' =>
                $validated['published_at']
                ?? null,
        ]);

        return redirect()
            ->route(
                'admin.announcements.index'
            )
            ->with(
                'success',
                'Announcement created successfully.'
            );
    }

    public function show(
        Announcement $announcement
    ) {
        return view(
            'admin.announcements.show',
            compact('announcement')
        );
    }

    public function edit(
        Announcement $announcement
    ) {
        return view(
            'admin.announcements.edit',
            compact('announcement')
        );
    }

    public function update(
        Request $request,
        Announcement $announcement
    ) {
        $validated =
            $this->validateAnnouncement(
                $request
            );

        $announcement->update([

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title'],
                    $announcement->id
                ),

            'content' =>
                $validated['content'],

            'status' =>
                $request->boolean('status'),

            'published_at' =>
                $validated['published_at']
                ?? null,
        ]);

        return redirect()
            ->route(
                'admin.announcements.index'
            )
            ->with(
                'success',
                'Announcement updated successfully.'
            );
    }

    public function destroy(
        Announcement $announcement
    ) {
        $announcement->delete();

        return redirect()
            ->route(
                'admin.announcements.index'
            )
            ->with(
                'success',
                'Announcement deleted successfully.'
            );
    }

    private function validateAnnouncement(
        Request $request
    ): array {
        return $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
            ],

            'published_at' => [
                'nullable',
                'date',
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
            Announcement::where(
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