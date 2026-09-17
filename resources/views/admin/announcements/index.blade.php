@extends('layouts.admin')

@section('title', 'Announcements')
@section('page-heading', 'Announcements')

@section('content')
<div class="space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100">
                    <i data-lucide="megaphone" class="h-5 w-5 text-slate-700"></i>
                </div>

                <div>
                    <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Announcements
                    </h1>
                    <p class="text-xs text-slate-500">
                        Manage public notices and announcements.
                    </p>
                </div>

            </div>
        </div>

        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white hover:bg-slate-800">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add Announcement
        </a>

    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Tabs --}}
        <div class="overflow-x-auto border-b border-slate-200">
            <div class="flex min-w-max px-2">

                <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ !request()->filled('status')
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    All
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $announcementTotal }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '1', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '1'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Published
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $announcementPublished }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '0', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '0'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Hidden
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $announcementHidden }})
                    </span>
                </a>

            </div>
        </div>

        {{-- Filter --}}
        
        <div class="p-3 border-b border-gray-200">

            <form method="GET" action="{{ route('admin.announcements.index') }}">

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
                                placeholder="Search announcement, title or content..."
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
                        href="{{ route('admin.announcements.index') }}"
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


        {{-- Table --}}
        <div class="hidden md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-xs font-semibold text-slate-700">
                            <th class="px-5 py-3">Announcement</th>
                            <th class="px-4 py-3">Published</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($announcements as $announcement)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-3.5">
                                    <div class="max-w-[650px]">
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $announcement->title }}
                                        </p>

                                        <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                                            {{ strip_tags($announcement->content) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-slate-500">
                                    {{ $announcement->published_at
                                        ? $announcement->published_at->format('d M Y, h:i A')
                                        : 'Not published' }}
                                </td>

                                <td class="px-4 py-3.5">

                                    @if($announcement->status)
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

                                        <a href="{{ route('admin.announcements.show', $announcement) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
                                           title="View">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
                                           title="Edit">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this announcement?');">

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
                                <td colspan="4" class="px-5 py-16 text-center">

                                    <i data-lucide="megaphone" class="mx-auto h-9 w-9 text-slate-300"></i>

                                    <p class="mt-3 text-sm font-medium text-slate-700">
                                        No announcements found
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-slate-200 md:hidden">

            @forelse($announcements as $announcement)

                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-slate-900">
                                {{ $announcement->title }}
                            </h3>

                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                {{ strip_tags($announcement->content) }}
                            </p>

                            <p class="mt-2 text-[11px] text-slate-400">
                                {{ $announcement->published_at
                                    ? $announcement->published_at->format('d M Y, h:i A')
                                    : 'Not published' }}
                            </p>
                        </div>

                        @if($announcement->status)
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

                        <a href="{{ route('admin.announcements.show', $announcement) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            View
                        </a>

                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            Edit
                        </a>

                    </div>

                </div>

            @empty

                <div class="px-5 py-14 text-center">
                    <p class="text-sm text-slate-600">No announcements found.</p>
                </div>

            @endforelse

        </div>

        @if($announcements->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>

</div>
@endsection