@extends('layouts.admin')

@section('title', 'Gallery Details')
@section('page-heading', 'Gallery Details')

@section('content')
<div class="space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                {{ $gallery->title }}
            </h1>
            <p class="text-xs text-slate-500">
                Gallery details and images
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.gallery.edit', $gallery) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white hover:bg-slate-800">
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Edit
            </a>

            <a href="{{ route('admin.gallery.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700 hover:bg-slate-200">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-4">

        <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Gallery Images
                    </h2>

                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                        {{ $gallery->images->count() }} images
                    </span>
                </div>
            </div>

            <div class="p-5">

                @if($gallery->images->count())

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach($gallery->images as $image)

                            <div class="overflow-hidden rounded-xl border border-slate-200">

                                <div class="aspect-[4/3] bg-slate-100">
                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         alt="{{ $image->caption ?: $gallery->title }}"
                                         class="h-full w-full object-cover">
                                </div>

                                <div class="p-3">
                                    <p class="text-xs font-medium text-slate-700">
                                        {{ $image->caption ?: 'No caption' }}
                                    </p>
                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="py-14 text-center">
                        <i data-lucide="image-off" class="mx-auto h-8 w-8 text-slate-300"></i>
                        <p class="mt-3 text-sm text-slate-500">
                            No images found.
                        </p>
                    </div>

                @endif
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Gallery Information
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Title
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $gallery->title }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Status
                    </p>

                    <div class="mt-1">
                        @if($gallery->status)
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                Published
                            </span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                Hidden
                            </span>
                        @endif
                    </div>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Images
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $gallery->images->count() }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Created
                    </p>
                    <p class="mt-1 text-xs text-slate-600">
                        {{ $gallery->created_at?->format('d M Y, h:i A') }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                Description
            </h2>
        </div>

        <div class="p-5">
            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                {{ $gallery->description ?: 'No description available.' }}
            </p>
        </div>

    </div>

</div>
@endsection