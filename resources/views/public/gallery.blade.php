@extends('layouts.public')

@section('title', 'Gallery | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Campus Memories
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Our Gallery
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Explore campus activities, practical training, workshops and events.
        </p>
    </div>
</section>

<section
    x-data="{ selected: null }"
    class="px-4 py-20 sm:px-6 lg:px-8"
>
    <div class="mx-auto max-w-7xl">

        <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">

            @forelse($images as $image)
                @php
                    $imagePath = $image->image_path
                        ?? $image->image
                        ?? $image->path
                        ?? null;
                @endphp

                @if($imagePath)
                    <button
                        @click="selected = '{{ asset('storage/' . ltrim($imagePath, '/')) }}'"
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-md"
                    >
                        <img
                            src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                            alt="GATTC Gallery"
                            class="h-64 w-full object-cover transition duration-500 group-hover:scale-110"
                        >

                        <div class="absolute inset-0 flex items-center justify-center bg-[#073b70]/0 transition group-hover:bg-[#073b70]/60">
                            <span class="scale-0 text-5xl text-white transition group-hover:scale-100">
                                +
                            </span>
                        </div>
                    </button>
                @endif
            @empty
                @foreach([
                    'gallery-1.jpg',
                    'gallery-2.jpg',
                    'gallery-3.jpg',
                    'gallery-4.jpg',
                    'gallery-5.jpg',
                    'gallery-6.jpg'
                ] as $image)
                    <button
                        @click="selected = '{{ asset('images/' . $image) }}'"
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-md"
                    >
                        <img
                            src="{{ asset('images/' . $image) }}"
                            alt="GATTC Campus"
                            class="h-64 w-full object-cover transition duration-500 group-hover:scale-110"
                        >
                    </button>
                @endforeach
            @endforelse

        </div>

        <div class="mt-12">
            {{ method_exists($images, 'links') ? $images->links() : '' }}
        </div>
    </div>

    <div
        x-show="selected"
        x-cloak
        x-transition
        @keydown.escape.window="selected = null"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-5"
    >
        <button
            @click="selected = null"
            class="absolute right-6 top-5 text-5xl text-white"
        >
            ×
        </button>

        <img
            :src="selected"
            alt="Gallery preview"
            class="max-h-[85vh] max-w-full rounded-2xl object-contain"
        >
    </div>
</section>

@endsection