@extends('layouts.admin')

@section('page-heading', 'Gallery')

@section('content')

<div class="space-y-4">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold">
                Gallery
            </h1>

            <p class="text-xs text-gray-500">
                Manage GATTC photos and gallery collections.
            </p>
        </div>

        <a
            href="{{ route('admin.gallery.create') }}"
            class="btn-primary">

            <i data-lucide="plus"
               class="w-4 h-4"></i>

            Add Gallery

        </a>

    </div>


    <div class="bg-white border rounded-xl p-3">

        <form method="GET"
              class="flex gap-2">

            <input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search galleries..."
                class="form-input flex-1">

            <select
                name="status"
                class="form-input w-32">

                <option value="">
                    All
                </option>

                <option value="1"
                    @selected(request('status') === '1')}>
                    Active
                </option>

                <option value="0"
                    @selected(request('status') === '0')}>
                    Inactive
                </option>

            </select>

            <button class="btn-primary">
                Filter
            </button>

            <a
                href="{{ route('admin.gallery.index') }}"
                class="btn-secondary">

                Reset

            </a>

        </form>

    </div>


    <div class="grid grid-cols-1
                md:grid-cols-2
                xl:grid-cols-3 gap-4">

        @forelse($galleries as $gallery)

            <div class="bg-white border
                        rounded-xl overflow-hidden">

                @php
                    $firstImage =
                        $gallery->images()->first();
                @endphp

                <div class="aspect-video bg-gray-100">

                    @if($firstImage)

                        <img
                            src="{{ asset(
                                'storage/' .
                                $firstImage->image
                            ) }}"
                            class="w-full h-full
                                   object-cover"
                            alt="">

                    @else

                        <div class="h-full
                                    flex items-center
                                    justify-center
                                    text-gray-300">

                            <i data-lucide="images"
                               class="w-10 h-10"></i>

                        </div>

                    @endif

                </div>


                <div class="p-4">

                    <div class="flex
                                items-center
                                justify-between">

                        <h2 class="font-semibold">
                            {{ $gallery->title }}
                        </h2>

                        @if($gallery->status)
                            <span class="badge-green">
                                Active
                            </span>
                        @else
                            <span class="badge-gray">
                                Inactive
                            </span>
                        @endif

                    </div>

                    <p class="text-xs
                              text-gray-500 mt-1">
                        {{ \Illuminate\Support\Str::limit(
                            $gallery->description,
                            90
                        ) }}
                    </p>

                    <div class="flex
                                items-center
                                justify-between mt-4">

                        <span class="text-xs text-gray-500">
                            {{ $gallery->images_count }}
                            images
                        </span>

                        <div class="flex gap-1">

                            <a
                                href="{{ route(
                                    'admin.gallery.show',
                                    $gallery
                                ) }}"
                                class="icon-btn">
                                <i data-lucide="eye"
                                   class="w-4 h-4"></i>
                            </a>

                            <a
                                href="{{ route(
                                    'admin.gallery.edit',
                                    $gallery
                                ) }}"
                                class="icon-btn">
                                <i data-lucide="pencil"
                                   class="w-4 h-4"></i>
                            </a>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.gallery.destroy',
                                    $gallery
                                ) }}"
                                onsubmit="return confirm(
                                    'Delete this gallery and its images?'
                                )">

                                @csrf
                                @method('DELETE')

                                <button class="icon-btn danger">
                                    <i data-lucide="trash-2"
                                       class="w-4 h-4"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-span-full
                        bg-white border rounded-xl
                        px-6 py-12 text-center
                        text-gray-400">

                No galleries found.

            </div>

        @endforelse

    </div>


    @if($galleries->hasPages())

        {{ $galleries
            ->withQueryString()
            ->links() }}

    @endif

</div>

@endsection