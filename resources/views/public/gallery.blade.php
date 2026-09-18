@extends('layouts.public')

@section('title', 'Gallery')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Campus life
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            GATTC Gallery
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Highlights from training, campus activities and student life.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site space-y-12">

        @forelse($galleries as $gallery)

            <section>

                <div class="mb-6">

                    <h2 class="text-2xl font-black text-slate-900">
                        {{ $gallery->title }}
                    </h2>

                    @if($gallery->description)

                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                            {{ $gallery->description }}
                        </p>

                    @endif

                </div>


                @if($gallery->images->count())

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">

                        @foreach($gallery->images as $image)

                            <figure class="group overflow-hidden rounded-2xl bg-slate-100">

                                <div class="aspect-[4/3] overflow-hidden">

                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        alt="{{ $image->caption ?? $gallery->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    >

                                </div>

                                @if($image->caption)

                                    <figcaption class="bg-white p-3 text-xs text-slate-600">
                                        {{ $image->caption }}
                                    </figcaption>

                                @endif

                            </figure>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-sm text-slate-500">
                        No images have been added to this gallery yet.
                    </div>

                @endif

            </section>

        @empty

            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500">
                No public gallery items are available.
            </div>

        @endforelse

    </div>

</section>

@endsection