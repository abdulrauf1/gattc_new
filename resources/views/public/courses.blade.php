@extends('layouts.public')

@section('title', 'Courses')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Training programmes
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Courses at GATTC
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Explore our technical, vocational and digital skills programmes.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site">

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

            @forelse($courses as $course)

                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

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


                    <h2 class="mt-5 text-xl font-bold text-slate-900">
                        {{ $course->title }}
                    </h2>


                    @if($course->code)

                        <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            {{ $course->code }}
                        </div>

                    @endif


                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        {{ $course->short_description }}
                    </p>


                    <div class="mt-5 grid grid-cols-2 gap-3 text-xs">

                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-slate-400">Duration</div>
                            <div class="mt-1 font-semibold">
                                {{ $course->duration ?? '—' }}
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-slate-400">Qualification</div>
                            <div class="mt-1 font-semibold">
                                {{ $course->qualification ?? '—' }}
                            </div>
                        </div>

                    </div>


                    <div class="mt-auto flex items-center justify-between pt-6">

                        <div>

                            <div class="text-xs text-slate-400">
                                Course fee
                            </div>

                            <div class="text-xl font-black text-slate-900">
                                Rs. {{ number_format((float) $course->fee) }}
                            </div>

                        </div>

                        <a
                            href="{{ route('public.course.show', $course->slug) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                        >
                            Details
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                    No active courses are available.
                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection