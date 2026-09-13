@extends('layouts.public')

@section('title', 'Events | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Campus Activities
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Events and Activities
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Stay updated with training activities, exhibitions, seminars,
            competitions and institutional events.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @forelse($events as $event)
                <article class="soft-card overflow-hidden">
                    <div class="brand-gradient flex h-40 items-center justify-center">
                        <span class="text-7xl">📅</span>
                    </div>

                    <div class="p-7">
                        <p class="text-sm font-bold uppercase tracking-wide text-emerald-600">
                            GATTC Event
                        </p>

                        <h2 class="mt-3 text-2xl font-black brand-blue">
                            {{ $event->title ?? $event->name }}
                        </h2>

                        @if($event->description ?? false)
                            <p class="mt-4 leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 180) }}
                            </p>
                        @endif

                        @if($event->event_date ?? false)
                            <p class="mt-5 text-sm font-bold text-slate-500">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                    Upcoming events will appear here.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $events->links() }}
        </div>
    </div>
</section>

@endsection