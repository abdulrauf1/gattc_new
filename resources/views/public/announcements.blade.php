@extends('layouts.public')

@section('title', 'Announcements | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Latest Information
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Announcements
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Read the latest admissions, academic and institutional announcements.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl space-y-6">

        @forelse($announcements as $announcement)
            <article class="soft-card p-7 sm:p-9">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                    <div class="flex h-16 w-16 shrink-0 flex-col items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                        <span class="text-xl font-black">
                            {{ optional($announcement->published_at)->format('d') ?? '—' }}
                        </span>

                        <span class="text-xs font-bold uppercase">
                            {{ optional($announcement->published_at)->format('M') ?? 'News' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide text-emerald-600">
                            Official Announcement
                        </p>

                        <h2 class="mt-2 text-2xl font-black brand-blue">
                            {{ $announcement->title }}
                        </h2>

                        @if($announcement->content ?? false)
                            <p class="mt-4 leading-8 text-slate-600">
                                {{ $announcement->content }}
                            </p>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                No announcements are available at the moment.
            </div>
        @endforelse

        <div class="pt-5">
            {{ $announcements->links() }}
        </div>
    </div>
</section>

@endsection