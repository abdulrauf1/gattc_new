@extends('layouts.public')

@section('title', 'Courses | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            GATTC Academic Programs
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Explore Our Courses
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Discover practical and industry-focused technical training programs
            designed to prepare you for employment and entrepreneurship.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="mb-12 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="font-bold uppercase tracking-widest text-emerald-600">
                    Learning Opportunities
                </p>

                <h2 class="section-heading mt-3 text-4xl font-black brand-blue">
                    Find Your Future
                </h2>
            </div>

            <a
                href="{{ route('public.admission') }}"
                class="w-fit rounded-full bg-emerald-600 px-6 py-3 font-bold text-white hover:bg-emerald-700"
            >
                Apply Online →
            </a>
        </div>

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($courses as $course)
                <article class="soft-card overflow-hidden">
                    <div class="brand-gradient flex h-44 items-center justify-center">
                        <span class="text-7xl">⚙️</span>
                    </div>

                    <div class="p-7">
                        <p class="text-sm font-bold uppercase tracking-wide text-emerald-600">
                            Technical Training
                        </p>

                        <h3 class="mt-3 text-2xl font-black brand-blue">
                            {{ $course->name ?? $course->title }}
                        </h3>

                        @if($course->description ?? false)
                            <p class="mt-4 leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($course->description), 160) }}
                            </p>
                        @else
                            <p class="mt-4 leading-7 text-slate-600">
                                Build practical knowledge and professional skills
                                through hands-on technical training.
                            </p>
                        @endif

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-500">
                                Career-oriented
                            </span>

                            <a
                                href="{{ route('public.admission') }}"
                                class="font-bold text-emerald-600 hover:text-emerald-700"
                            >
                                Apply →
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                    Courses will be published here soon.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $courses->links() }}
        </div>
    </div>
</section>

@endsection