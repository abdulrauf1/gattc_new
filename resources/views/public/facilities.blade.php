@extends('layouts.public')

@section('title', 'Facilities')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Campus facilities
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Facilities designed for practical learning
        </h1>

        <p class="mt-5 max-w-3xl leading-7 text-slate-300">
            A learning environment built around practical training, workshops, laboratories and student support.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @php

            $facilities = [
                [
                    'icon' => 'wrench',
                    'title' => 'Technical Workshops',
                    'text' => 'Practical workshop spaces for hands-on technical training and skill development.',
                ],
                [
                    'icon' => 'monitor',
                    'title' => 'Computer Labs',
                    'text' => 'Computer-based learning facilities for IT, office automation and digital skills.',
                ],
                [
                    'icon' => 'sun',
                    'title' => 'Electrical & Solar Training',
                    'text' => 'Practical learning environments for electrical and renewable-energy skills.',
                ],
                [
                    'icon' => 'building-2',
                    'title' => 'Hostel',
                    'text' => 'Residential support for eligible trainees where accommodation is available.',
                ],
                [
                    'icon' => 'mosque',
                    'title' => 'Mosque',
                    'text' => 'On-campus prayer facility for trainees and staff.',
                ],
                [
                    'icon' => 'presentation',
                    'title' => 'Conference & Seminar Space',
                    'text' => 'Dedicated space for seminars, orientations, meetings and awareness activities.',
                ],
                [
                    'icon' => 'car',
                    'title' => 'Parking',
                    'text' => 'Dedicated parking arrangements for visitors, trainees and staff.',
                ],
                [
                    'icon' => 'users',
                    'title' => 'Student Areas',
                    'text' => 'Student-focused spaces supporting interaction and campus activities.',
                ],
                [
                    'icon' => 'briefcase-business',
                    'title' => 'Career Support',
                    'text' => 'Support for employability, workplace readiness and career development.',
                ],
            ];

        @endphp


        @foreach($facilities as $facility)

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                    <i
                        data-lucide="{{ $facility['icon'] }}"
                        class="h-6 w-6"
                    ></i>

                </div>

                <h2 class="mt-5 text-lg font-bold text-slate-900">
                    {{ $facility['title'] }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $facility['text'] }}
                </p>

            </article>

        @endforeach

    </div>

</section>

@endsection