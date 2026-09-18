@extends('layouts.public')

@section('title', 'Home')

@section(
    'meta_description',
    'Government Advance Technical Training Centre, Hayatabad Peshawar — practical technical and vocational training.'
)

@section('content')

{{-- ==============================================================
| HERO
================================================================ --}}
<section class="relative overflow-hidden bg-slate-950 text-white">

    <div class="hero-grid absolute inset-0 opacity-70"></div>

    <div class="container-site relative grid min-h-[620px] items-center gap-12 py-16 lg:grid-cols-2 lg:py-20">

        <div>

            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1.5 text-xs font-semibold text-emerald-300">

                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                {{ $activeSession ? 'Admissions are currently open' : 'Admissions are currently closed' }}

            </div>


            <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">

                Build practical skills.
                <span class="text-emerald-400">
                    Build your future.
                </span>

            </h1>


            <p class="mt-6 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">

                Government Advance Technical Training Centre provides practical, market-oriented technical and vocational training in Hayatabad, Peshawar.

            </p>


            <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                <a
                    href="{{ route('public.admission') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-6 py-3.5 text-sm font-bold text-slate-950 hover:bg-emerald-400"
                >
                    <i data-lucide="file-pen-line" class="h-5 w-5"></i>

                    {{ $activeSession ? 'Apply Online' : 'Admission Information' }}

                </a>


                <a
                    href="{{ route('public.courses') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/10"
                >
                    Explore Courses

                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>

            </div>


            @if($activeSession)

                <div class="mt-5 text-sm text-slate-400">

                    <span class="font-medium text-white">
                        {{ $activeSession->title }}
                    </span>

                    ·
                    Closing
                    {{ $activeSession->closing_date?->format('d M Y') }}

                </div>

            @endif

        </div>


        {{-- Hero information card --}}
        <div class="lg:justify-self-end">

            <div class="glass rounded-3xl border border-white/10 p-6 shadow-2xl sm:p-8">

                <div class="grid grid-cols-2 gap-4">

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                        <div class="text-3xl font-black text-emerald-400">
                            {{ $courseCount }}
                        </div>

                        <div class="mt-1 text-sm text-slate-300">
                            Active Courses
                        </div>

                    </div>


                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                        <div class="text-3xl font-black text-emerald-400">
                            {{ $studentCount }}
                        </div>

                        <div class="mt-1 text-sm text-slate-300">
                            Approved Trainees
                        </div>

                    </div>


                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                        <div class="text-3xl font-black text-emerald-400">
                            {{ $alumniCount }}
                        </div>

                        <div class="mt-1 text-sm text-slate-300">
                            Alumni
                        </div>

                    </div>


                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                        <div class="text-3xl font-black text-emerald-400">
                            100%
                        </div>

                        <div class="mt-1 text-sm text-slate-300">
                            Practical Focus
                        </div>

                    </div>

                </div>


                <div class="mt-5 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-5">

                    <div class="flex items-start gap-3">

                        <div class="rounded-xl bg-emerald-400/20 p-2">
                            <i data-lucide="graduation-cap" class="h-5 w-5 text-emerald-300"></i>
                        </div>

                        <div>

                            <div class="font-semibold text-white">
                                Learn. Practice. Progress.
                            </div>

                            <div class="mt-1 text-sm leading-6 text-slate-300">
                                Develop technical abilities through structured training, workshops and practical learning.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ==============================================================
| VISION / MISSION
================================================================ --}}
<section class="section-padding bg-white">

    <div class="container-site">

        <div class="mx-auto max-w-3xl text-center">

            <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                Our direction
            </span>

            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                Vision and Mission
            </h2>

            <p class="mt-4 text-slate-600">
                A clear focus on market-relevant skills, employability and practical technical education.
            </p>

        </div>


        <div class="mt-12 grid gap-6 lg:grid-cols-2">

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-7">

                <div class="flex items-center gap-3">

                    <div class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                        <i data-lucide="eye" class="h-6 w-6"></i>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900">
                        Our Vision
                    </h3>

                </div>

                <p class="mt-5 text-base leading-8 text-slate-600">

                    {{ $settings->get(
                        'vision',
                        'Prosperous and economically stable Khyber Pakhtunkhwa for meeting domestic and overseas market demand.'
                    ) }}

                </p>

            </div>


            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-7">

                <div class="flex items-center gap-3">

                    <div class="rounded-xl bg-blue-100 p-3 text-blue-700">
                        <i data-lucide="target" class="h-6 w-6"></i>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900">
                        Our Mission
                    </h3>

                </div>

                <p class="mt-5 text-base leading-8 text-slate-600">

                    {{ $settings->get(
                        'mission',
                        'Youth engagement in demand-driven skilled training in sync with market-based technology for improved employability of Khyber Pakhtunkhwa.'
                    ) }}

                </p>

            </div>

        </div>

    </div>

</section>


{{-- ==============================================================
| OBJECTIVES
================================================================ --}}
<section class="section-padding bg-slate-50">

    <div class="container-site grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-center">

        <div>

            <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                Our objectives
            </span>

            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                Skills that lead to opportunity
            </h2>

            <p class="mt-5 leading-7 text-slate-600">
                GATTC's training approach is built around practical learning, workplace readiness and relevant technical capabilities.
            </p>

        </div>


        @php

            $objectiveText = $settings->get('objectives');

            $objectives = $objectiveText
                ? preg_split('/\r\n|\r|\n/', $objectiveText)
                : [
                    'Provide demand-driven technical and vocational training.',
                    'Strengthen hands-on practical learning through workshops and laboratories.',
                    'Improve employability through relevant technical and digital skills.',
                    'Encourage entrepreneurship, freelancing and self-employment.',
                    'Strengthen links between training institutions and industry.',
                    'Promote safe, inclusive and professional learning environments.',
                ];

            $objectives = array_values(
                array_filter(
                    array_map('trim', $objectives)
                )
            );

        @endphp


        <div class="grid gap-3 sm:grid-cols-2">

            @foreach($objectives as $index => $objective)

                <div class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-5">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-sm font-black text-emerald-700">
                        {{ $index + 1 }}
                    </div>

                    <div class="text-sm leading-6 text-slate-600">
                        {{ $objective }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>


{{-- ==============================================================
| LEADERSHIP
================================================================ --}}
<section class="section-padding bg-white">

    <div class="container-site">

        <div class="mx-auto max-w-3xl text-center">

            <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                Leadership
            </span>

            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                Institute leadership
            </h2>

            <p class="mt-4 text-slate-600">
                Leadership profiles presented by the institute for students, parents and stakeholders.
            </p>

        </div>


        @php

            $leaders = [

                [
                    'name' => $settings->get(
                        'principal_name',
                        'Principal'
                    ),
                    'designation' => $settings->get(
                        'principal_designation',
                        'Principal, GATTC Hayatabad'
                    ),
                    'photo' => $settings->get(
                        'principal_photo'
                    ),
                    'message' => $settings->get(
                        'principal_message',
                        ''
                    ),
                ],

                [
                    'name' => $settings->get(
                        'imc_chairman_name',
                        'IMC Chairman'
                    ),
                    'designation' => $settings->get(
                        'imc_chairman_designation',
                        'Chairman, Institute Management Committee'
                    ),
                    'photo' => $settings->get(
                        'imc_chairman_photo'
                    ),
                    'message' => $settings->get(
                        'imc_chairman_message',
                        ''
                    ),
                ],

                [
                    'name' => $settings->get(
                        'md_name',
                        'Mian Abdul Qadir Shah'
                    ),
                    'designation' => $settings->get(
                        'md_designation',
                        'Managing Director, KP-TEVTA'
                    ),
                    'photo' => $settings->get(
                        'md_photo'
                    ),
                    'message' => $settings->get(
                        'md_message',
                        ''
                    ),
                ],

            ];

        @endphp


        <div class="mt-12 grid gap-6 lg:grid-cols-3">

            @foreach($leaders as $leader)

                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50">

                    <div class="flex min-h-[280px] items-center justify-center bg-slate-100">

                        @if($leader['photo'])

                            <img
                                src="{{ asset($leader['photo']) }}"
                                alt="{{ $leader['name'] }}"
                                class="h-64 w-full object-cover"
                            >

                        @else

                            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-emerald-100 text-4xl font-black text-emerald-700">

                                {{ strtoupper(
                                    collect(
                                        preg_split('/\s+/', trim($leader['name']))
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

                        <div class="text-lg font-bold text-slate-900">
                            {{ $leader['name'] }}
                        </div>

                        <div class="mt-1 text-sm font-medium text-emerald-700">
                            {{ $leader['designation'] }}
                        </div>

                        @if($leader['message'])

                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                {{ $leader['message'] }}
                            </p>

                        @endif

                    </div>

                </article>

            @endforeach

        </div>

    </div>

</section>


{{-- ==============================================================
| FEATURED COURSES
================================================================ --}}
<section class="section-padding bg-slate-50">

    <div class="container-site">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                    Training programmes
                </span>

                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900">
                    Featured courses
                </h2>

            </div>

            <a
                href="{{ route('public.courses') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
            >
                View all courses
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>

        </div>


        <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">

            @forelse($courses as $course)

                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    <div class="flex items-start justify-between gap-3">

                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            {{ $course->category?->name ?? 'Technical Training' }}
                        </span>

                        @if($course->featured)

                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                Featured
                            </span>

                        @endif

                    </div>


                    <h3 class="mt-4 text-lg font-bold text-slate-900">
                        {{ $course->title }}
                    </h3>


                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                        {{ $course->short_description }}
                    </p>


                    <div class="mt-5 grid grid-cols-2 gap-3 text-xs">

                        <div class="rounded-xl bg-slate-50 p-3">

                            <div class="text-slate-400">
                                Duration
                            </div>

                            <div class="mt-1 font-semibold text-slate-700">
                                {{ $course->duration ?? '—' }}
                            </div>

                        </div>


                        <div class="rounded-xl bg-slate-50 p-3">

                            <div class="text-slate-400">
                                Qualification
                            </div>

                            <div class="mt-1 font-semibold text-slate-700">
                                {{ $course->qualification ?? '—' }}
                            </div>

                        </div>

                    </div>


                    <div class="mt-auto flex items-center justify-between pt-5">

                        <div class="text-lg font-black text-slate-900">
                            Rs. {{ number_format((float) $course->fee) }}
                        </div>

                        <a
                            href="{{ route('public.course.show', $course->slug) }}"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Details
                            <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                        </a>

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    No courses are currently available.
                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- ==============================================================
| ANNOUNCEMENTS + EVENTS
================================================================ --}}
<section class="section-padding bg-white">

    <div class="container-site grid gap-10 lg:grid-cols-2">

        <div>

            <div class="flex items-end justify-between">

                <div>

                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                        Latest updates
                    </span>

                    <h2 class="mt-2 text-2xl font-black text-slate-900">
                        Announcements
                    </h2>

                </div>

                <a
                    href="{{ route('public.announcements') }}"
                    class="text-sm font-semibold text-emerald-700"
                >
                    More
                </a>

            </div>


            <div class="mt-6 space-y-3">

                @forelse($announcements as $announcement)

                    <a
                        href="{{ route('public.announcements') }}"
                        class="block rounded-2xl border border-slate-200 p-5 hover:border-emerald-300 hover:bg-emerald-50/40"
                    >

                        <div class="text-xs font-medium text-slate-400">
                            {{ $announcement->published_at?->format('d M Y') }}
                        </div>

                        <div class="mt-1 font-bold text-slate-900">
                            {{ $announcement->title }}
                        </div>

                        <div class="mt-1 line-clamp-2 text-sm text-slate-600">
                            {{ strip_tags($announcement->content) }}
                        </div>

                    </a>

                @empty

                    <div class="rounded-2xl border border-slate-200 p-6 text-sm text-slate-500">
                        No announcements available.
                    </div>

                @endforelse

            </div>

        </div>


        <div>

            <div class="flex items-end justify-between">

                <div>

                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                        What's happening
                    </span>

                    <h2 class="mt-2 text-2xl font-black text-slate-900">
                        Upcoming events
                    </h2>

                </div>

                <a
                    href="{{ route('public.events') }}"
                    class="text-sm font-semibold text-emerald-700"
                >
                    More
                </a>

            </div>


            <div class="mt-6 space-y-3">

                @forelse($events as $event)

                    <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                        <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                            <span class="text-lg font-black">
                                {{ $event->event_date?->format('d') }}
                            </span>

                            <span class="text-[10px] font-bold uppercase">
                                {{ $event->event_date?->format('M') }}
                            </span>

                        </div>


                        <div class="min-w-0">

                            <div class="font-bold text-slate-900">
                                {{ $event->title }}
                            </div>

                            <div class="mt-1 line-clamp-2 text-sm text-slate-600">
                                {{ $event->description }}
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="rounded-2xl border border-slate-200 p-6 text-sm text-slate-500">
                        No upcoming events available.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>


{{-- ==============================================================
| GALLERY
================================================================ --}}
@if($galleryImages->count())

<section class="section-padding bg-slate-50">

    <div class="container-site">

        <div class="flex items-end justify-between">

            <div>

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                    Campus life
                </span>

                <h2 class="mt-2 text-3xl font-black text-slate-900">
                    GATTC in pictures
                </h2>

            </div>

            <a
                href="{{ route('public.gallery') }}"
                class="text-sm font-semibold text-emerald-700"
            >
                View gallery
            </a>

        </div>


        <div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4">

            @foreach($galleryImages as $image)

                <div class="group aspect-[4/3] overflow-hidden rounded-2xl bg-slate-200">

                    <img
                        src="{{ asset('storage/' . $image->image) }}"
                        alt="{{ $image->caption ?? 'GATTC activity' }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    >

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif


{{-- ==============================================================
| CTA
================================================================ --}}
<section class="bg-emerald-700 py-16 text-white">

    <div class="container-site flex flex-col gap-8 md:flex-row md:items-center md:justify-between">

        <div>

            <div class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-200">
                Start your journey
            </div>

            <h2 class="mt-2 text-3xl font-black">
                Ready to learn a practical skill?
            </h2>

            <p class="mt-3 max-w-2xl text-emerald-50">
                Explore our current programmes and apply online when the admission session is open.
            </p>

        </div>


        <a
            href="{{ route('public.admission') }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-emerald-700 hover:bg-emerald-50"
        >
            Apply Online

            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>

    </div>

</section>

@endsection