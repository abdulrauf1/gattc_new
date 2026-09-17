@extends('layouts.admin')

@section('title', 'Edit Gallery')
@section('page-heading', 'Edit Gallery')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">Edit Gallery</h1>
            <p class="text-xs text-slate-500">{{ $gallery->title }}</p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.gallery.show', $gallery) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700 hover:bg-slate-200">
                <i data-lucide="eye" class="h-4 w-4"></i>
                View
            </a>

            <a href="{{ route('admin.gallery.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700 hover:bg-slate-200">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <ul class="list-disc space-y-1 pl-5 text-xs text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.gallery.update', $gallery) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Gallery Information
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Gallery Title *
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title', $gallery->title) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="7"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">{{ old('description', $gallery->description) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Publishing
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Status
                        </label>

                        <select name="status"
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm">
                            <option value="1" @selected(old('status', $gallery->status) == '1')>
                                Published
                            </option>
                            <option value="0" @selected(old('status', $gallery->status) == '0')>
                                Hidden
                            </option>
                        </select>
                    </div>

                    <div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-500">
                        Total images:
                        <strong class="text-slate-700">
                            {{ $gallery->images->count() }}
                        </strong>
                    </div>

                </div>
            </div>

        </div>

        {{-- Existing Images --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Existing Images
                </h2>
            </div>

            <div class="p-5">

                @if($gallery->images->count())

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

                        @foreach($gallery->images as $image)

                            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">

                                <div class="relative aspect-[4/3] bg-slate-100">

                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         alt="{{ $image->caption ?: $gallery->title }}"
                                         class="h-full w-full object-cover">

                                    <form action="{{ route('admin.gallery.images.destroy', [$gallery, $image]) }}"
                                          method="POST"
                                          class="absolute right-2 top-2"
                                          onsubmit="return confirm('Delete this image?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 text-white shadow hover:bg-red-700">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>

                                    </form>

                                </div>

                                <div class="p-2.5">
                                    <p class="truncate text-xs font-medium text-slate-700">
                                        {{ $image->caption ?: 'No caption' }}
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Order {{ $image->sort_order }}
                                    </p>
                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="py-10 text-center text-xs text-slate-400">
                        No images have been uploaded.
                    </div>

                @endif

            </div>
        </div>

        {{-- New Images --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Add More Images
                </h2>
            </div>

            <div class="p-5">

                <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">

                    <input type="file"
                           name="images[]"
                           multiple
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden"
                           onchange="document.getElementById('new-gallery-file-name').textContent = this.files.length ? this.files.length + ' image(s) selected' : 'No images selected';">

                    <i data-lucide="upload-cloud" class="h-7 w-7 text-slate-400"></i>

                    <p class="mt-2 text-sm font-medium text-slate-700">
                        Select additional images
                    </p>

                    <p id="new-gallery-file-name"
                       class="mt-1 text-xs text-slate-400">
                        No images selected
                    </p>

                </label>

            </div>
        </div>

        <div class="flex justify-end gap-2">

            <a href="{{ route('admin.gallery.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white hover:bg-emerald-600">
                <i data-lucide="save" class="h-4 w-4"></i>
                Update Gallery
            </button>

        </div>

    </form>
</div>
@endsection