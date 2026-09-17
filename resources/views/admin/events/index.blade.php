@extends('layouts.admin')

@section('title', 'Events')
@section('page-heading', 'Events')

@section('content')
<div class="space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100">
                    <i data-lucide="calendar-days" class="h-5 w-5 text-slate-700"></i>
                </div>

                <div>
                    <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Events
                    </h1>
                    <p class="text-xs text-slate-500">
                        Manage GATTC events and activities.
                    </p>
                </div>

            </div>
        </div>

        <a href="{{ route('admin.events.create') }}"
           class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white hover:bg-slate-800">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add Event
        </a>

    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto border-b border-slate-200">
            <div class="flex min-w-max px-2">

                <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ !request()->filled('status')
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    All
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $eventTotal }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '1', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '1'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Published
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $eventPublished }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '0', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '0'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Hidden
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $eventHidden }})
                    </span>
                </a>

            </div>
        </div>


        <div class="p-3 border-b border-gray-200">

            <form method="GET" action="{{ route('admin.events.index') }}">

                <div class="flex flex-col lg:flex-row gap-2">

                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">

                            <i data-lucide="search"
                               class="absolute left-3 top-1/2
                                      -translate-y-1/2
                                      w-4 h-4 text-gray-400">
                            </i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search event, location or description..."
                                class="w-full h-9 rounded-lg border border-gray-300
                                       pl-9 pr-3 text-sm
                                       focus:border-blue-500
                                       focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>


                    {{-- Status --}}
                    <div class="w-full lg:w-36">

                        <select
                            name="status"
                            class="w-full h-9 rounded-lg border border-gray-300
                                   px-3 text-sm">

                            <option value="">All Status</option>
                            <option value="1" @selected(request('status') === '1')>Published</option>
                            <option value="0" @selected(request('status') === '0')>Hidden</option>


                        </select>

                    </div>



                    {{-- Filter --}}
                    <button
                        type="submit"
                        class="h-9 px-3 rounded-lg
                               bg-emerald-500 hover:bg-emerald-600
                               text-white text-sm font-medium
                               inline-flex items-center justify-center gap-1.5">

                        <i data-lucide="filter"
                           class="w-4 h-4"></i>

                        Filter

                    </button>


                    {{-- Reset --}}
                    <a
                        href="{{ route('admin.events.index') }}"
                        title="Reset filters"
                        class="h-9 w-9 rounded-lg
                               bg-gray-100 hover:bg-gray-200
                               flex items-center justify-center">

                        <i data-lucide="rotate-ccw"
                           class="w-4 h-4 text-gray-600"></i>

                    </a>

                </div>

            </form>

        </div>


        <div class="hidden md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-xs font-semibold text-slate-700">
                            <th class="px-5 py-3">Event</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($events as $event)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-3.5">
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $event->title }}
                                    </p>

                                    <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                                        {{ $event->short_description }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-slate-500">
                                    {{ $event->event_date
                                        ? $event->event_date->format('d M Y')
                                        : '—' }}
                                </td>

                                <td class="px-4 py-3.5 text-sm text-slate-600">
                                    {{ $event->location ?: '—' }}
                                </td>

                                <td class="px-4 py-3.5">

                                    @if($event->status)
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                            Published
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            Hidden
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-3.5">

                                    <div class="flex justify-end gap-1.5">

                                        <a href="{{ route('admin.events.show', $event) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a href="{{ route('admin.events.edit', $event) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <form action="{{ route('admin.events.destroy', $event) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this event?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <i data-lucide="calendar-x" class="mx-auto h-9 w-9 text-slate-300"></i>
                                    <p class="mt-3 text-sm font-medium text-slate-700">
                                        No events found
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="divide-y divide-slate-200 md:hidden">

            @forelse($events as $event)

                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0 flex-1">

                            <h3 class="text-sm font-semibold text-slate-900">
                                {{ $event->title }}
                            </h3>

                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                {{ $event->short_description }}
                            </p>

                            <div class="mt-2 grid grid-cols-2 gap-2 text-[11px]">
                                <div>
                                    <span class="text-slate-400">Date</span>
                                    <p class="font-medium text-slate-700">
                                        {{ $event->event_date?->format('d M Y') ?: '—' }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-slate-400">Location</span>
                                    <p class="truncate font-medium text-slate-700">
                                        {{ $event->location ?: '—' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        @if($event->status)
                            <span class="shrink-0 rounded-full bg-blue-50 px-2 py-1 text-[10px] font-medium text-blue-700">
                                Published
                            </span>
                        @else
                            <span class="shrink-0 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-600">
                                Hidden
                            </span>
                        @endif

                    </div>

                    <div class="mt-3 flex gap-2 border-t border-slate-100 pt-3">

                        <a href="{{ route('admin.events.show', $event) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            View
                        </a>

                        <a href="{{ route('admin.events.edit', $event) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            Edit
                        </a>

                    </div>

                </div>

            @empty

                <div class="px-5 py-14 text-center">
                    <p class="text-sm text-slate-600">No events found.</p>
                </div>

            @endforelse

        </div>

        @if($events->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $events->links() }}
            </div>
        @endif

    </div>

</div>
@endsection