@extends('layouts.public')

@section('title', 'GATTC Alumni')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Our Community
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            GATTC Alumni
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Celebrating our graduates and their contribution to industry,
            business and society.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="mb-12 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <h2 class="section-heading text-4xl font-black brand-blue">
                    Our Successful Graduates
                </h2>
            </div>

            <a
                href="{{ route('public.alumni.register') }}"
                class="w-fit rounded-full bg-emerald-600 px-6 py-3 font-bold text-white hover:bg-emerald-700"
            >
                Register Yourself →
            </a>
        </div>

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($alumni as $person)
                <article class="soft-card overflow-hidden text-center">

                    @if($person->photo)
                        <img
                            src="{{ asset('storage/' . $person->photo) }}"
                            alt="{{ $person->name }}"
                            class="h-64 w-full object-cover"
                        >
                    @else
                        <div class="brand-gradient flex h-64 items-center justify-center">
                            <span class="text-8xl text-white">👤</span>
                        </div>
                    @endif

                    <div class="p-7">
                        <h2 class="text-2xl font-black brand-blue">
                            {{ $person->name }}
                        </h2>

                        <p class="mt-2 font-semibold text-emerald-600">
                            {{ $person->course }}
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            Graduated: {{ $person->graduation_year }}
                        </p>

                        @if($person->designation)
                            <p class="mt-4 font-semibold text-slate-700">
                                {{ $person->designation }}
                            </p>
                        @endif

                        @if($person->organization)
                            <p class="mt-1 text-slate-500">
                                {{ $person->organization }}
                            </p>
                        @endif

                        @if($person->bio)
                            <p class="mt-4 leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit($person->bio, 150) }}
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                    Alumni profiles will be published here soon.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $alumni->links() }}
        </div>
    </div>
</section>

@endsection