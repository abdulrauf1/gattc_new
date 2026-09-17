<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query()
            ->with([
                'images' => function ($q) {
                    $q->orderBy('sort_order')->limit(4);
                },
            ])
            ->withCount('images');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (bool) $request->status);
        }

        $galleries = $query
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $galleryTotal = Gallery::count();

        $galleryPublished = Gallery::where('status', true)->count();

        $galleryHidden = Gallery::where('status', false)->count();

        return view('admin.gallery.index', compact(
            'galleries',
            'galleryTotal',
            'galleryPublished',
            'galleryHidden'
        ));
    }


    public function create()
    {
        return view(
            'admin.gallery.create'
        );
    }


    public function store(Request $request)
    {
        $validated = $this->validateGallery(
            $request,
            true
        );


        $gallery = DB::transaction(
            function () use (
                $request,
                $validated
            ) {

                $gallery = Gallery::create([

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
                        $request->boolean(
                            'status'
                        ),
                ]);


                $this->saveImages(
                    $request,
                    $gallery
                );


                return $gallery;
            }
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


    public function show(
        Gallery $gallery
    ) {
        $gallery->load('images');

        return view(
            'admin.gallery.show',
            compact('gallery')
        );
    }


    public function edit(
        Gallery $gallery
    ) {
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
        $validated = $this->validateGallery(
            $request,
            false
        );


        DB::transaction(
            function () use (
                $request,
                $validated,
                $gallery
            ) {

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
                        $request->boolean(
                            'status'
                        ),
                ]);


                $this->saveImages(
                    $request,
                    $gallery
                );
            }
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
            $gallery->images as $image
        ) {

            $this->deleteStoredImage(
                $image->image
            );
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
            $image->gallery_id ===
            $gallery->id,
            404
        );


        $this->deleteStoredImage(
            $image->image
        );


        $image->delete();


        return back()->with(
            'success',
            'Gallery image deleted successfully.'
        );
    }


    private function validateGallery(
        Request $request,
        bool $creating
    ): array {

        return $request->validate([

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
                'max:5120',
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
    }


    private function saveImages(
        Request $request,
        Gallery $gallery
    ): void {

        if (!$request->hasFile('images')) {
            return;
        }


        $nextSort =
            ((int) $gallery
                ->images()
                ->max('sort_order')) + 1;


        $captions =
            $request->input(
                'captions',
                []
            );


        foreach (
            $request->file('images')
            as $index => $file
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


    private function deleteStoredImage(
        ?string $path
    ): void {

        if (
            $path &&
            Storage::disk('public')
                ->exists($path)
        ) {

            Storage::disk('public')
                ->delete($path);
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