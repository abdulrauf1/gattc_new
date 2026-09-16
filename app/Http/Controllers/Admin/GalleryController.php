<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query()
            ->withCount('images')
            ->latest();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(
                'title',
                'like',
                "%{$search}%"
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                (bool) $request->status
            );
        }

        $galleries =
            $query
                ->paginate(12)
                ->withQueryString();

        return view(
            'admin.gallery.index',
            compact('galleries')
        );
    }

    public function create()
    {
        return view(
            'admin.gallery.create'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'captions' => [
                'nullable',
                'array',
            ],

            'captions.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $gallery =
            Gallery::create([

                'title' =>
                    $validated['title'],

                'slug' =>
                    $this->uniqueSlug(
                        $validated['title']
                    ),

                'description' =>
                    $validated['description']
                    ?? null,

                'status' =>
                    $request->boolean('status'),
            ]);


        $this->storeImages(
            $request,
            $gallery
        );


        return redirect()
            ->route(
                'admin.gallery.show',
                $gallery
            )
            ->with(
                'success',
                'Gallery created successfully.'
            );
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('images');

        return view(
            'admin.gallery.show',
            compact('gallery')
        );
    }

    public function edit(Gallery $gallery)
    {
        $gallery->load('images');

        return view(
            'admin.gallery.edit',
            compact('gallery')
        );
    }

    public function update(
        Request $request,
        Gallery $gallery
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'captions' => [
                'nullable',
                'array',
            ],

            'captions.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $gallery->update([

            'title' =>
                $validated['title'],

            'slug' =>
                $this->uniqueSlug(
                    $validated['title'],
                    $gallery->id
                ),

            'description' =>
                $validated['description']
                ?? null,

            'status' =>
                $request->boolean('status'),
        ]);

        $this->storeImages(
            $request,
            $gallery
        );

        return redirect()
            ->route(
                'admin.gallery.show',
                $gallery
            )
            ->with(
                'success',
                'Gallery updated successfully.'
            );
    }

    public function destroy(
        Gallery $gallery
    ) {
        $gallery->load('images');

        foreach (
            $gallery->images
            as $image
        ) {
            if (
                $image->image &&
                Storage::disk('public')
                    ->exists($image->image)
            ) {
                Storage::disk('public')
                    ->delete($image->image);
            }
        }

        $gallery->delete();

        return redirect()
            ->route(
                'admin.gallery.index'
            )
            ->with(
                'success',
                'Gallery deleted successfully.'
            );
    }

    public function destroyImage(
        Gallery $gallery,
        GalleryImage $image
    ) {
        abort_unless(
            $image->gallery_id === $gallery->id,
            404
        );

        if (
            $image->image &&
            Storage::disk('public')
                ->exists($image->image)
        ) {
            Storage::disk('public')
                ->delete($image->image);
        }

        $image->delete();

        return back()->with(
            'success',
            'Gallery image deleted successfully.'
        );
    }

    private function storeImages(
        Request $request,
        Gallery $gallery
    ): void {
        $files =
            $request->file('images', []);

        $captions =
            $request->input(
                'captions',
                []
            );

        $nextSort =
            ((int) $gallery
                ->images()
                ->max('sort_order')) + 1;


        foreach (
            $files as $index => $file
        ) {

            $path =
                $file->store(
                    'gallery',
                    'public'
                );

            $gallery->images()->create([

                'image' =>
                    $path,

                'caption' =>
                    $captions[$index]
                    ?? null,

                'sort_order' =>
                    $nextSort++,
            ]);
        }
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
            Gallery::where(
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