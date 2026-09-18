@extends('layouts.public')

@section('title', 'Alumni')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site flex flex-col gap-6 md:flex-row md:items-end md:justify-between">

        <div>

            <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
                GATTC community
            </span>

            <h1 class="mt-3 text-4xl font-black sm:text-5xl">
                Our Alumni
            </h1>

            <p class="mt-5 max-w-3xl text-slate-300">
                Celebrating former trainees and their professional journeys.
            </p>

        </div>


        <a
            href="{{ route('public.alumni.register') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-slate-950"
        >
            <i data-lucide="user-plus" class="h-4 w-4"></i>
            Register as Alumni
        </a>

    </div>

</section>


<section class="section-padding">

    <div class="container-site">

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            @forelse($alumni as $person)

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="flex h-64 items-center justify-center bg-slate-100">

                        @if($person->photo)

                            <img
                                src="{{ asset('storage/' . $person->photo) }}"
                                alt="{{ $person->name }}"
                                class="h-full w-full object-cover"
                            >

                        @else

                            <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-100 text-3xl font-black text-emerald-700">

                                {{ strtoupper(
                                    collect(
                                        preg_split('/\s+/', trim($person->name))
                                    )
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($part) => substr($part, 0, 1))
                                    ->join('')
                                ) }}

                            </div>

                        @endif

                    </div>


                    <div class="p-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            {{ $person->name }}
                        </h2>

                        <div class="mt-1 text-sm font-semibold text-emerald-700">
                            {{ $person->course }}
                        </div>

                        <div class="mt-2 text-xs text-slate-400">
                            Graduate of {{ $person->graduation_year }}
                        </div>


                        @if($person->designation || $person->organization)

                            <div class="mt-4 text-sm text-slate-600">

                                @if($person->designation)
                                    {{ $person->designation }}
                                @endif

                                @if($person->designation && $person->organization)
                                    ·
                                @endif

                                @if($person->organization)
                                    {{ $person->organization }}
                                @endif

                            </div>

                        @endif


                        @if($person->bio)

                            <p class="mt-4 line-clamp-4 text-sm leading-6 text-slate-600">
                                {{ $person->bio }}
                            </p>

                        @endif

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 sm:col-span-2 lg:col-span-3">
                    No alumni profiles are currently published.
                </div>

            @endforelse

        </div>


        @if($alumni->hasPages())

            <div class="mt-8">
                {{ $alumni->links() }}
            </div>

        @endif

    </div>

</section>

@endsection