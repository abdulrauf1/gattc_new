@extends('layouts.admin')

@section('title', 'Alumni')
@section('page-heading', 'Alumni')

@section('content')
<div class="space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100">
                    <i data-lucide="graduation-cap" class="h-5 w-5 text-slate-700"></i>
                </div>

                <div>
                    <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Alumni
                    </h1>
                    <p class="text-xs text-slate-500">
                        Manage alumni profiles and approvals.
                    </p>
                </div>

            </div>
        </div>

        <a href="{{ route('admin.alumni.create') }}"
           class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white hover:bg-slate-800">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add Alumni
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
                        ({{ $alumniTotal }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '1', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '1'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Approved
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $alumniApproved }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => '0', 'page' => null]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('status') === '0'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Not Approved
                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $alumniNotApproved }})
                    </span>
                </a>

            </div>
        </div>

        {{-- Filter --}}

        <div class="p-3 border-b border-gray-200">

            <form method="GET" action="{{ route('admin.alumni.index') }}">

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
                                placeholder="Search name, email, phone, course or organization..."
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
                            <option value="1" @selected(request('status') === '1')>
                                Approved
                            </option>
                            <option value="0" @selected(request('status') === '0')>
                                Not Approved
                            </option>


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
                        href="{{ route('admin.alumni.index') }}"
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


        {{-- Desktop --}}
        <div class="hidden md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1050px] text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-xs font-semibold text-slate-700">
                            <th class="px-5 py-3">Alumni</th>
                            <th class="px-4 py-3">Course</th>
                            <th class="px-4 py-3">Graduation</th>
                            <th class="px-4 py-3">Organization</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($alumnis as $alumni)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-3.5">

                                    <div class="flex items-center gap-3">

                                        <div class="h-11 w-11 shrink-0 overflow-hidden rounded-full bg-slate-100">

                                            @if($alumni->photo)

                                                <img src="{{ asset('storage/' . $alumni->photo) }}"
                                                     alt="{{ $alumni->name }}"
                                                     class="h-full w-full object-cover">

                                            @else

                                                <div class="flex h-full w-full items-center justify-center">
                                                    <i data-lucide="user" class="h-5 w-5 text-slate-400"></i>
                                                </div>

                                            @endif

                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate text-sm font-semibold text-slate-900">
                                                {{ $alumni->name }}
                                            </p>

                                            <p class="truncate text-xs text-slate-500">
                                                {{ $alumni->email }}
                                            </p>

                                            <p class="truncate text-[11px] text-slate-400">
                                                {{ $alumni->phone }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-4 py-3.5 text-sm text-slate-600">
                                    {{ $alumni->course }}
                                </td>

                                <td class="px-4 py-3.5 text-sm text-slate-600">
                                    {{ $alumni->graduation_year }}
                                </td>

                                <td class="px-4 py-3.5">

                                    <p class="text-sm text-slate-700">
                                        {{ $alumni->organization ?: '—' }}
                                    </p>

                                    @if($alumni->designation)
                                        <p class="mt-0.5 text-xs text-slate-400">
                                            {{ $alumni->designation }}
                                        </p>
                                    @endif

                                </td>

                                <td class="px-4 py-3.5">

                                    @if($alumni->status)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            Approved
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                            Not Approved
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-3.5">

                                    <div class="flex justify-end gap-1.5">

                                        <a href="{{ route('admin.alumni.show', $alumni) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
                                           title="View">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a href="{{ route('admin.alumni.edit', $alumni) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
                                           title="Edit">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        @if(!$alumni->status)

                                            <form method="POST"
                                                  action="{{ route('admin.alumni.approve', $alumni) }}">
                                                @csrf

                                                <button type="submit"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100"
                                                        title="Approve">
                                                    <i data-lucide="check" class="h-4 w-4"></i>
                                                </button>
                                            </form>

                                        @endif

                                        <form method="POST"
                                              action="{{ route('admin.alumni.destroy', $alumni) }}"
                                              onsubmit="return confirm('Delete this alumni record?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
                                                    title="Delete">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">

                                    <i data-lucide="graduation-cap" class="mx-auto h-9 w-9 text-slate-300"></i>

                                    <p class="mt-3 text-sm font-medium text-slate-700">
                                        No alumni found
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

            @forelse($alumnis as $alumni)

                <div class="p-4">

                    <div class="flex gap-3">

                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-slate-100">

                            @if($alumni->photo)

                                <img src="{{ asset('storage/' . $alumni->photo) }}"
                                     alt="{{ $alumni->name }}"
                                     class="h-full w-full object-cover">

                            @else

                                <div class="flex h-full w-full items-center justify-center">
                                    <i data-lucide="user" class="h-5 w-5 text-slate-400"></i>
                                </div>

                            @endif

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-2">

                                <div class="min-w-0">

                                    <h3 class="truncate text-sm font-semibold text-slate-900">
                                        {{ $alumni->name }}
                                    </h3>

                                    <p class="truncate text-xs text-slate-500">
                                        {{ $alumni->email }}
                                    </p>

                                </div>

                                @if($alumni->status)
                                    <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700">
                                        Approved
                                    </span>
                                @else
                                    <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-700">
                                        Not Approved
                                    </span>
                                @endif

                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-[11px]">

                                <div>
                                    <span class="text-slate-400">Course</span>
                                    <p class="font-medium text-slate-700">
                                        {{ $alumni->course }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-slate-400">Graduation</span>
                                    <p class="font-medium text-slate-700">
                                        {{ $alumni->graduation_year }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-slate-400">Organization</span>
                                    <p class="truncate font-medium text-slate-700">
                                        {{ $alumni->organization ?: '—' }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-slate-400">Phone</span>
                                    <p class="font-medium text-slate-700">
                                        {{ $alumni->phone }}
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="mt-3 flex gap-2 border-t border-slate-100 pt-3">

                        <a href="{{ route('admin.alumni.show', $alumni) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            View
                        </a>

                        <a href="{{ route('admin.alumni.edit', $alumni) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            Edit
                        </a>

                        @if(!$alumni->status)

                            <form method="POST"
                                  action="{{ route('admin.alumni.approve', $alumni) }}"
                                  class="flex-1">

                                @csrf

                                <button type="submit"
                                        class="w-full rounded-lg bg-emerald-50 py-2 text-xs font-medium text-emerald-700">
                                    Approve
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @empty

                <div class="px-5 py-14 text-center">
                    <p class="text-sm text-slate-600">
                        No alumni found.
                    </p>
                </div>

            @endforelse

        </div>

        @if($alumnis->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $alumnis->links() }}
            </div>
        @endif

    </div>

</div>
@endsection