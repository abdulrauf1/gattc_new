@extends('layouts.public')

@section('title', 'GATTC | Learn Today, Build Tomorrow')

@section('meta_description')
Government Advance Technical Training Centre, Hayatabad Peshawar, offering practical technical and vocational education.
@endsection

@section('content')

<!-- Hero -->
<section
    x-data="{
        active: 0,
        slides: [
            {
                image: '{{ asset('images/campus-1.jpg') }}',
                title: 'Learn Today.',
                highlight: 'Build Tomorrow',
                text: 'Develop practical technical skills through quality education, professional training and hands-on learning.'
            },
            {
                image: '{{ asset('images/campus-2.jpg') }}',
                title: 'Transform Your',
                highlight: 'Potential Into Skills',
                text: 'Join industry-focused training programs designed to prepare you for employment and entrepreneurship.'
            },
            {
                image: '{{ asset('images/campus-3.jpg') }}',
                title: 'Learn Practical.',
                highlight: 'Grow Professionally',
                text: 'Experience modern workshops, expert instructors and career-oriented technical education.'
            }
        ]
    }"
    x-init="setInterval(() => active = (active + 1) % slides.length, 7000)"
    class="relative overflow-hidden bg-[#032541]"
>
    <div class="relative min-h-[650px]">

        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="active === index"
                x-transition.opacity.duration.700ms
                class="absolute inset-0"
            >
                <img
                    :src="slide.image"
                    alt="GATTC Campus"
                    class="h-full min-h-[650px] w-full object-cover"
                >

                <div class="absolute inset-0 bg-gradient-to-r from-[#032541]/95 via-[#032541]/75 to-transparent"></div>
            </div>
        </template>

        <div class="relative z-10 mx-auto flex min-h-[650px] max-w-7xl items-center px-4 py-24 sm:px-6 lg:px-8">
            <div class="max-w-3xl text-white">

                <div class="mb-6 inline-flex rounded-full bg-emerald-500/20 px-5 py-2 text-sm font-bold tracking-wider text-emerald-300">
                    SKILLS
                    <span class="mx-3">|</span>
                    TRAINING
                    <span class="mx-3">|</span>
                    BETTER FUTURE
                </div>

                <h1 class="text-5xl font-black leading-tight sm:text-6xl lg:text-7xl">
                    <span x-text="slides[active].title"></span>
                    <br>
                    <span class="text-emerald-400" x-text="slides[active].highlight"></span>
                </h1>

                <p
                    class="mt-7 max-w-2xl text-lg leading-8 text-slate-200 sm:text-xl"
                    x-text="slides[active].text"
                ></p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a
                        href="{{ route('public.courses') }}"
                        class="rounded-full bg-emerald-500 px-7 py-4 font-bold text-white transition hover:bg-emerald-600"
                    >
                        Explore Courses →
                    </a>

                    <a
                        href="{{ route('public.admission') }}"
                        class="rounded-full border-2 border-white px-7 py-4 font-bold text-white transition hover:bg-white hover:text-[#073b70]"
                    >
                        Apply for Admission →
                    </a>
                </div>

                <div class="mt-12 flex gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button
                            @click="active = index"
                            :class="active === index ? 'w-10 bg-emerald-400' : 'w-3 bg-white/50'"
                            class="h-3 rounded-full transition-all"
                            aria-label="Change hero slide"
                        ></button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="relative z-20 mx-auto -mt-12 max-w-6xl px-4">
    <div class="grid overflow-hidden rounded-3xl bg-[#063b70] shadow-2xl sm:grid-cols-2 lg:grid-cols-4">

        @foreach([
            ['number' => '15+', 'label' => 'Training Courses', 'icon' => '🎓'],
            ['number' => '5+', 'label' => 'Skill Categories', 'icon' => '⚙️'],
            ['number' => '1000+', 'label' => 'Successful Graduates', 'icon' => '👥'],
            ['number' => '20+', 'label' => 'Years of Excellence', 'icon' => '🏆']
        ] as $stat)
            <div class="flex items-center gap-4 border-b border-white/20 p-6 text-white last:border-b-0 lg:border-b-0 lg:border-r lg:last:border-r-0">
                <div class="text-4xl">{{ $stat['icon'] }}</div>

                <div>
                    <div class="text-3xl font-black">{{ $stat['number'] }}</div>
                    <div class="text-sm text-slate-200">{{ $stat['label'] }}</div>
                </div>
            </div>
        @endforeach

    </div>
</section>

<!-- About -->
<section class="bg-white px-4 py-24 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl items-center gap-14 lg:grid-cols-2">

        <div>
            <p class="font-bold uppercase tracking-widest text-emerald-600">
                About GATTC
            </p>

            <h2 class="section-heading mt-4 text-4xl font-black brand-blue sm:text-5xl">
                Building Skills.
                <br>
                Empowering Futures.
            </h2>

            <p class="mt-8 text-lg leading-8 text-slate-600">
                Government Advance Technical Training Centre, Hayatabad,
                Peshawar provides practical and career-oriented technical
                and vocational education.
            </p>

            <p class="mt-5 leading-7 text-slate-600">
                Our focus is to develop employable skills through hands-on
                training, modern laboratories, experienced instructors and
                industry-relevant programs.
            </p>

            <a
                href="{{ route('public.facilities') }}"
                class="mt-8 inline-flex rounded-full bg-[#073b70] px-7 py-3 font-bold text-white hover:bg-[#052b52]"
            >
                Explore Our Facilities →
            </a>
        </div>

        <div>
            <img
                src="{{ asset('images/campus-2.jpg') }}"
                alt="GATTC Campus"
                class="h-[420px] w-full rounded-[2rem] object-cover shadow-2xl"
            >
        </div>
    </div>
</section>

<!-- Courses -->
<section class="bg-slate-50 px-4 py-24 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div>
                <p class="font-bold uppercase tracking-widest text-emerald-600">
                    Our Courses
                </p>

                <h2 class="section-heading mt-4 text-4xl font-black brand-blue sm:text-5xl">
                    Discover Your
                    <span class="text-emerald-600">Perfect Course</span>
                </h2>
            </div>

            <a
                href="{{ route('public.courses') }}"
                class="w-fit rounded-full bg-emerald-600 px-6 py-3 font-bold text-white hover:bg-emerald-700"
            >
                View More Courses →
            </a>
        </div>

        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($courses as $course)
                <article class="soft-card overflow-hidden">
                    <div class="brand-gradient flex h-40 items-center justify-center">
                        <span class="text-7xl">⚙️</span>
                    </div>

                    <div class="p-7">
                        <p class="text-sm font-bold uppercase tracking-wide text-emerald-600">
                            Technical Training
                        </p>

                        <h3 class="mt-3 text-2xl font-black brand-blue">
                            {{ $course->name ?? $course->title }}
                        </h3>

                        <p class="mt-4 leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(strip_tags($course->description ?? 'Practical career-oriented technical training.'), 130) }}
                        </p>

                        <a
                            href="{{ route('public.admission') }}"
                            class="mt-6 inline-block font-bold text-emerald-600 hover:text-emerald-700"
                        >
                            Apply for This Course →
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                    Courses will be published here soon.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features -->
<section class="bg-white px-4 py-24 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="text-center">
            <p class="font-bold uppercase tracking-widest text-emerald-600">
                Why Choose GATTC
            </p>

            <h2 class="mt-4 text-4xl font-black brand-blue sm:text-5xl">
                More Than Just Training
            </h2>
        </div>

        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['icon' => '👨‍🏫', 'title' => 'Expert Instructors', 'text' => 'Learn from experienced and dedicated trainers.'],
                ['icon' => '🏢', 'title' => 'Modern Facilities', 'text' => 'Practice in well-equipped labs and workshops.'],
                ['icon' => '🧰', 'title' => 'Hands-on Training', 'text' => 'Gain practical skills through real activities.'],
                ['icon' => '🤝', 'title' => 'Career Guidance', 'text' => 'Receive guidance for employment and entrepreneurship.']
            ] as $feature)
                <div class="soft-card p-8 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-4xl">
                        {{ $feature['icon'] }}
                    </div>

                    <h3 class="mt-6 text-xl font-black brand-blue">
                        {{ $feature['title'] }}
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        {{ $feature['text'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a
                href="{{ route('public.facilities') }}"
                class="inline-flex rounded-full border-2 border-emerald-600 px-7 py-3 font-bold text-emerald-700 hover:bg-emerald-50"
            >
                View More Facilities →
            </a>
        </div>
    </div>
</section>

<!-- Gallery preview -->
<section class="bg-slate-50 px-4 py-24 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div>
                <p class="font-bold uppercase tracking-widest text-emerald-600">
                    Campus Life
                </p>

                <h2 class="section-heading mt-4 text-4xl font-black brand-blue sm:text-5xl">
                    Explore Our Gallery
                </h2>
            </div>

            <a
                href="{{ route('public.gallery') }}"
                class="w-fit rounded-full bg-emerald-600 px-6 py-3 font-bold text-white hover:bg-emerald-700"
            >
                View More Images →
            </a>
        </div>

        <div class="mt-14 grid grid-cols-2 gap-5 md:grid-cols-3">
            @foreach([
                'gallery-1.jpg',
                'gallery-2.jpg',
                'gallery-3.jpg',
                'gallery-4.jpg',
                'gallery-5.jpg',
                'gallery-6.jpg'
            ] as $image)
                <a
                    href="{{ route('public.gallery') }}"
                    class="group overflow-hidden rounded-3xl shadow-md"
                >
                    <img
                        src="{{ asset('images/' . $image) }}"
                        alt="GATTC Gallery"
                        class="h-64 w-full object-cover transition duration-500 group-hover:scale-110"
                    >
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Announcements and Events -->
<section class="bg-white px-4 py-24 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-14 lg:grid-cols-2">

        <div>
            <div class="flex items-end justify-between gap-4">
                <h2 class="section-heading text-3xl font-black brand-blue">
                    Latest Announcements
                </h2>

                <a
                    href="{{ route('public.announcements') }}"
                    class="font-bold text-emerald-600"
                >
                    View More →
                </a>
            </div>

            <div class="mt-10 space-y-5">
                @forelse($announcements as $announcement)
                    <article class="soft-card p-6">
                        <p class="text-sm font-bold text-emerald-600">
                            {{ optional($announcement->published_at)->format('d M Y') ?? 'Latest Update' }}
                        </p>

                        <h3 class="mt-2 text-xl font-black brand-blue">
                            {{ $announcement->title }}
                        </h3>

                        <p class="mt-3 leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content ?? ''), 150) }}
                        </p>
                    </article>
                @empty
                    <p class="text-slate-500">
                        No announcements are available.
                    </p>
                @endforelse
            </div>
        </div>

        <div>
            <div class="flex items-end justify-between gap-4">
                <h2 class="section-heading text-3xl font-black brand-blue">
                    Upcoming Events
                </h2>

                <a
                    href="{{ route('public.events') }}"
                    class="font-bold text-emerald-600"
                >
                    View More →
                </a>
            </div>

            <div class="mt-10 space-y-5">
                @forelse($events as $event)
                    <article class="soft-card p-6">
                        <p class="text-sm font-bold text-emerald-600">
                            GATTC Event
                        </p>

                        <h3 class="mt-2 text-xl font-black brand-blue">
                            {{ $event->title ?? $event->name }}
                        </h3>

                        <p class="mt-3 leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 150) }}
                        </p>
                    </article>
                @empty
                    <p class="text-slate-500">
                        No upcoming events are available.
                    </p>
                @endforelse
            </div>
        </div>

    </div>
</section>

<!-- Alumni CTA -->
<section class="brand-gradient px-4 py-24 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl text-center">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Stay Connected
        </p>

        <h2 class="mt-5 text-4xl font-black sm:text-5xl">
            Are You a GATTC Graduate?
        </h2>

        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Register yourself in our alumni community and stay connected
            with GATTC activities and opportunities.
        </p>

        <div class="mt-9 flex flex-wrap justify-center gap-4">
            <a
                href="{{ route('public.alumni.register') }}"
                class="rounded-full bg-emerald-500 px-8 py-4 font-black text-white hover:bg-emerald-600"
            >
                Register as Alumni →
            </a>

            <a
                href="{{ route('public.alumni') }}"
                class="rounded-full border-2 border-white px-8 py-4 font-black text-white hover:bg-white hover:text-[#073b70]"
            >
                View Alumni
            </a>
        </div>
    </div>
</section>

@endsection