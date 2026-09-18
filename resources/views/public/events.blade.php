@extends('layouts.public')

@section('title', 'Events')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Campus activities
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Events
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Seminars, exhibitions, orientations and other institute activities.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site">

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

            @forelse($events as $event)

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="bg-emerald-600 p-6 text-white">

                        <div class="text-3xl font-black">
                            {{ $event->event_date?->format('d') }}
                        </div>

                        <div class="text-xs font-bold uppercase tracking-wider text-emerald-100">
                            {{ $event->event_date?->format('F Y') }}
                        </div>

                    </div>


                    <div class="p-6">

                        <h2 class="text-xl font-bold text-slate-900">
                            {{ $event->title }}
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            {{ $event->description }}
                        </p>

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 md:col-span-2 lg:col-span-3">
                    No public events are currently available.
                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection