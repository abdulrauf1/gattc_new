@extends('layouts.admin')

@section('title', 'Announcement Details')
@section('page-heading', 'Announcement Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                {{ $announcement->title }}
            </h1>
            <p class="text-xs text-slate-500">
                Announcement details
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.announcements.edit', $announcement) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white">
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Edit
            </a>

            <a href="{{ route('admin.announcements.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-4">

        <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Announcement Content
                </h2>
            </div>

            <div class="p-5">

                <article class="whitespace-pre-line text-sm leading-7 text-slate-700">
                    {{ $announcement->content }}
                </article>

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Information
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase text-slate-400">Status</p>

                    <div class="mt-1">
                        @if($announcement->status)
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
                    <p class="text-[11px] uppercase text-slate-400">Published</p>
                    <p class="mt-1 text-xs text-slate-600">
                        {{ $announcement->published_at
                            ? $announcement->published_at->format('d M Y, h:i A')
                            : 'Not published' }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase text-slate-400">Slug</p>
                    <p class="mt-1 break-all text-xs text-slate-600">
                        {{ $announcement->slug }}
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection