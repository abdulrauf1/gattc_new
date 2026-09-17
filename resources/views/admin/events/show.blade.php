@extends('layouts.admin')

@section('title', 'Event Details')
@section('page-heading', 'Event Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                {{ $event->title }}
            </h1>
            <p class="text-xs text-slate-500">
                Event details
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.events.edit', $event) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white">
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Edit
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>

    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-4">

        <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Event Description
                </h2>
            </div>

            <div class="p-5">

                <h2 class="text-xl font-semibold text-slate-900">
                    {{ $event->title }}
                </h2>

                @if($event->short_description)
                    <p class="mt-2 text-sm text-slate-500">
                        {{ $event->short_description }}
                    </p>
                @endif

                <div class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-700">
                    {{ $event->description }}
                </div>

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Event Information
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase text-slate-400">
                        Date
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $event->event_date?->format('d M Y') ?: '—' }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase text-slate-400">
                        Location
                    </p>
                    <p class="mt-1 text-sm text-slate-700">
                        {{ $event->location ?: 'Not specified' }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[11px] uppercase text-slate-400">
                        Status
                    </p>

                    <div class="mt-1">
                        @if($event->status)
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
                    <p class="text-[11px] uppercase text-slate-400">
                        Slug
                    </p>
                    <p class="mt-1 break-all text-xs text-slate-600">
                        {{ $event->slug }}
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection