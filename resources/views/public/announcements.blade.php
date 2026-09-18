@extends('layouts.public')

@section('title', 'Announcements')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Latest updates
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Announcements
        </h1>

    </div>

</section>


<section class="section-padding">

    <div class="container-site">

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

            @forelse($announcements as $announcement)

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                        {{ $announcement->published_at?->format('d M Y') ?? 'GATTC Update' }}
                    </div>


                    <h2 class="mt-3 text-xl font-bold text-slate-900">
                        {{ $announcement->title }}
                    </h2>


                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        {{ strip_tags($announcement->content) }}
                    </p>

                </article>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 md:col-span-2 lg:col-span-3">
                    No announcements are currently available.
                </div>

            @endforelse

        </div>


        @if($announcements->hasPages())

            <div class="mt-8">
                {{ $announcements->links() }}
            </div>

        @endif

    </div>

</section>

@endsection