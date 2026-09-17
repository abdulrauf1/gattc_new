@extends('layouts.admin')

@section('title', 'Create Gallery')
@section('page-heading', 'Create Gallery')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">Create Gallery</h1>
            <p class="text-xs text-slate-500">Create a new website gallery.</p>
        </div>

        <a href="{{ route('admin.gallery.index') }}"
           class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700 hover:bg-slate-200">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back
        </a>

    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="flex gap-3">
                <i data-lucide="circle-alert" class="h-5 w-5 shrink-0 text-red-600"></i>

                <div>
                    <p class="text-sm font-semibold text-red-800">
                        Please correct the following errors:
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-xs text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.gallery.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

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
                            Gallery Title
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="Enter gallery title"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">

                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="7"
                                  placeholder="Enter a short description..."
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="space-y-4">

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Publishing
                        </h2>
                    </div>

                    <div class="p-5">
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Status
                        </label>

                        <select name="status"
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm outline-none focus:border-slate-400">
                            <option value="1" @selected(old('status', '1') == '1')>
                                Published
                            </option>
                            <option value="0" @selected(old('status') === '0')>
                                Hidden
                            </option>
                        </select>
                    </div>

                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Gallery Images
                        </h2>
                    </div>

                    <div class="p-5">

                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center hover:bg-slate-100">

                            <input type="file"
                                   name="images[]"
                                   multiple
                                   accept="image/jpeg,image/png,image/webp"
                                   class="hidden"
                                   onchange="document.getElementById('gallery-file-name').textContent = this.files.length ? this.files.length + ' image(s) selected' : 'No images selected';">

                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white">
                                <i data-lucide="upload-cloud" class="h-6 w-6 text-slate-500"></i>
                            </div>

                            <p class="mt-3 text-sm font-medium text-slate-700">
                                Select images
                            </p>

                            <p id="gallery-file-name"
                               class="mt-1 text-xs text-slate-400">
                                JPG, PNG or WEBP
                            </p>

                        </label>

                    </div>
                </div>

            </div>

        </div>

        <div class="flex justify-end gap-2">

            <a href="{{ route('admin.gallery.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700 hover:bg-slate-200">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white hover:bg-emerald-600">
                <i data-lucide="save" class="h-4 w-4"></i>
                Save Gallery
            </button>

        </div>

    </form>
</div>
@endsection