@extends('layouts.public')

@section('title', $course->title)

@section('content')

<section class="bg-slate-950 py-16 text-white">

    <div class="container-site">

        <a
            href="{{ route('public.courses') }}"
            class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            All Courses
        </a>


        <div class="mt-8 max-w-4xl">

            <span class="rounded-full bg-emerald-400/10 px-3 py-1.5 text-xs font-semibold text-emerald-300">
                {{ $course->category?->name ?? 'Technical Training' }}
            </span>

            <h1 class="mt-4 text-4xl font-black sm:text-5xl">
                {{ $course->title }}
            </h1>

            <p class="mt-5 text-lg leading-8 text-slate-300">
                {{ $course->short_description }}
            </p>

        </div>

    </div>

</section>


<section class="section-padding">

    <div class="container-site grid gap-10 lg:grid-cols-[1.4fr_.6fr]">

        <div>

            <h2 class="text-2xl font-black text-slate-900">
                About this course
            </h2>

            <div class="prose prose-slate mt-5 max-w-none">

                {!! nl2br(e($course->description)) !!}

            </div>

        </div>


        <aside>

            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="text-sm font-semibold text-slate-500">
                    Course information
                </div>


                <dl class="mt-5 space-y-4 text-sm">

                    <div class="flex justify-between gap-5 border-b border-slate-100 pb-3">
                        <dt class="text-slate-500">Duration</dt>
                        <dd class="font-semibold text-slate-900">
                            {{ $course->duration ?? '—' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-5 border-b border-slate-100 pb-3">
                        <dt class="text-slate-500">Qualification</dt>
                        <dd class="text-right font-semibold text-slate-900">
                            {{ $course->qualification ?? '—' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-5">
                        <dt class="text-slate-500">Fee</dt>
                        <dd class="font-black text-emerald-700">
                            Rs. {{ number_format((float) $course->fee) }}
                        </dd>
                    </div>

                </dl>


                <a
                    href="{{ route('public.admission') }}"
                    class="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white hover:bg-emerald-700"
                >
                    Apply Online
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>

            </div>

        </aside>

    </div>

</section>


@if($relatedCourses->count())

<section class="bg-slate-50 py-16">

    <div class="container-site">

        <h2 class="text-2xl font-black text-slate-900">
            Related courses
        </h2>

        <div class="mt-7 grid gap-5 md:grid-cols-3">

            @foreach($relatedCourses as $related)

                <a
                    href="{{ route('public.course.show', $related->slug) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 hover:border-emerald-300"
                >

                    <div class="text-lg font-bold text-slate-900">
                        {{ $related->title }}
                    </div>

                    <div class="mt-2 text-sm text-slate-500">
                        {{ $related->duration }}
                    </div>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endif

@endsection