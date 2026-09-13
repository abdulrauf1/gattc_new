@extends('layouts.public')

@section('title', 'Facilities | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Learning Environment
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Our Facilities
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            A supportive and practical environment where students learn,
            experiment and develop professional confidence.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">

            @foreach([
                ['icon' => '💻', 'title' => 'Computer Laboratories', 'text' => 'Modern computer-based learning for digital literacy, programming and office applications.'],
                ['icon' => '🧰', 'title' => 'Technical Workshops', 'text' => 'Practical workshop activities that develop real technical and problem-solving skills.'],
                ['icon' => '⚡', 'title' => 'Electrical Training Labs', 'text' => 'Hands-on learning in electrical installation, wiring, testing and safety.'],
                ['icon' => '❄️', 'title' => 'HVACR Practice Areas', 'text' => 'Practical training in refrigeration, air conditioning and maintenance procedures.'],
                ['icon' => '🛠️', 'title' => 'Equipment and Tools', 'text' => 'Training with tools and equipment used in professional technical environments.'],
                ['icon' => '👨‍🏫', 'title' => 'Expert Instructors', 'text' => 'Guidance from experienced instructors focused on student progress and employability.']
            ] as $facility)
                <article class="soft-card p-8">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-4xl">
                        {{ $facility['icon'] }}
                    </div>

                    <h2 class="mt-6 text-2xl font-black brand-blue">
                        {{ $facility['title'] }}
                    </h2>

                    <p class="mt-4 leading-7 text-slate-600">
                        {{ $facility['text'] }}
                    </p>
                </article>
            @endforeach

        </div>
    </div>
</section>

@endsection