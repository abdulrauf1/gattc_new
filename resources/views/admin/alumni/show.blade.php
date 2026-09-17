@extends('layouts.admin')

@section('title', 'Alumni Details')
@section('page-heading', 'Alumni Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                {{ $alumni->name }}
            </h1>

            <p class="text-xs text-slate-500">
                Alumni profile
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.alumni.edit', $alumni) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-900 px-4 text-sm font-medium text-white">
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Edit
            </a>

            <a href="{{ route('admin.alumni.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>

    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-4">

        {{-- Profile --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="p-5 text-center">

                <div class="mx-auto h-32 w-32 overflow-hidden rounded-full bg-slate-100">

                    @if($alumni->photo)

                        <img src="{{ asset('storage/' . $alumni->photo) }}"
                             alt="{{ $alumni->name }}"
                             class="h-full w-full object-cover">

                    @else

                        <div class="flex h-full w-full items-center justify-center">
                            <i data-lucide="user-round" class="h-10 w-10 text-slate-300"></i>
                        </div>

                    @endif

                </div>

                <h2 class="mt-4 text-base font-semibold text-slate-900">
                    {{ $alumni->name }}
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $alumni->course }}
                </p>

                <div class="mt-3">

                    @if($alumni->status)
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                            Approved
                        </span>
                    @else
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                            Not Approved
                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- Details --}}
        <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Alumni Information
                </h2>
            </div>

            <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

                <div class="divide-y divide-slate-100">

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Name
                        </p>
                        <p class="mt-1 text-sm font-medium text-slate-800">
                            {{ $alumni->name }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Email
                        </p>
                        <p class="mt-1 break-all text-sm text-slate-700">
                            {{ $alumni->email }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Phone
                        </p>
                        <p class="mt-1 text-sm text-slate-700">
                            {{ $alumni->phone }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Course
                        </p>
                        <p class="mt-1 text-sm text-slate-700">
                            {{ $alumni->course }}
                        </p>
                    </div>

                </div>

                <div class="divide-y divide-slate-100">

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Graduation Year
                        </p>
                        <p class="mt-1 text-sm font-medium text-slate-800">
                            {{ $alumni->graduation_year }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Organization
                        </p>
                        <p class="mt-1 text-sm text-slate-700">
                            {{ $alumni->organization ?: '—' }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Designation
                        </p>
                        <p class="mt-1 text-sm text-slate-700">
                            {{ $alumni->designation ?: '—' }}
                        </p>
                    </div>

                    <div class="px-5 py-4">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Added
                        </p>
                        <p class="mt-1 text-sm text-slate-700">
                            {{ $alumni->created_at?->format('d M Y, h:i A') }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Bio --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                Biography
            </h2>
        </div>

        <div class="p-5">

            @if($alumni->bio)

                <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                    {{ $alumni->bio }}
                </p>

            @else

                <p class="text-sm text-slate-400">
                    No biography has been added.
                </p>

            @endif

        </div>

    </div>

</div>
@endsection