@php
    use App\Models\WebsiteSetting;

    $settings = $settings ?? WebsiteSetting::query()
        ->pluck('value', 'key');

    $siteName = $settings->get(
        'site_name',
        'Government Advance Technical Training Centre'
    );

    $shortName = $settings->get(
        'short_name',
        'GATTC'
    );

    $address = $settings->get(
        'address',
        '16-A Industrial Estate, Opposite BRT TEVTA Stop, Hayatabad, Peshawar'
    );

    $phone = $settings->get(
        'phone',
        '091-5881389'
    );

    $email = $settings->get(
        'email',
        'info@gattc.edu.pk'
    );

    $website = $settings->get(
        'website',
        'https://gattc.edu.pk'
    );
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', $shortName) | {{ $shortName }}
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'Government Advance Technical Training Centre, Hayatabad Peshawar')"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glass {
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .section-padding {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        .container-site {
            width: min(1180px, calc(100% - 2rem));
            margin-inline: auto;
        }
    </style>

    @stack('styles')

</head>

<body class="bg-slate-50 text-slate-800">

    {{-- Top contact strip --}}
    <div class="hidden bg-slate-950 text-white md:block">

        <div class="container-site flex h-9 items-center justify-between text-xs">

            <div class="flex items-center gap-5">

                <span class="inline-flex items-center gap-1.5">
                    <i data-lucide="phone" class="h-3.5 w-3.5"></i>
                    {{ $phone }}
                </span>

                <span class="inline-flex items-center gap-1.5">
                    <i data-lucide="mail" class="h-3.5 w-3.5"></i>
                    {{ $email }}
                </span>

            </div>

            <span>
                Technical & Vocational Training
            </span>

        </div>

    </div>


    {{-- Header --}}
    <header
        x-data="{ open: false }"
        class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur"
    >

        <div class="container-site">

            <div class="flex h-16 items-center justify-between gap-4">

                {{-- Brand --}}
                <a
                    href="{{ route('home') }}"
                    class="flex min-w-0 items-center gap-3"
                >

                    <img
                        src="{{ asset('images/gattc-logo.png') }}"
                        alt="GATTC Logo"
                        class="h-11 w-11 object-contain"
                    >

                    
                    <div class="min-w-0">

                        <div class="truncate text-sm font-bold text-slate-900 md:text-base">
                            {{ $shortName }}
                        </div>

                        <div class="hidden truncate text-[11px] text-slate-500 sm:block">
                            Government Advance Technical Training Centre
                        </div>

                    </div>

                </a>


                {{-- Desktop nav --}}
                <nav class="hidden items-center gap-1 lg:flex">

                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Home
                    </a>

                    <a
                        href="{{ route('public.courses') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.courses') || request()->routeIs('public.course.show') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Courses
                    </a>

                    <a
                        href="{{ route('public.facilities') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.facilities') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Facilities
                    </a>

                    <a
                        href="{{ route('public.gallery') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.gallery') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Gallery
                    </a>

                    <a
                        href="{{ route('public.events') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.events') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Events
                    </a>

                    <a
                        href="{{ route('public.announcements') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.announcements') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        News
                    </a>

                    <a
                        href="{{ route('public.alumni') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.alumni*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Alumni
                    </a>

                    <a
                        href="{{ route('public.contact') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('public.contact*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        Contact
                    </a>

                </nav>


                {{-- Desktop CTA --}}
                <div class="hidden lg:block">

                    <a
                        href="{{ route('public.admission') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700"
                    >
                        <i data-lucide="file-pen-line" class="h-4 w-4"></i>
                        Apply Online
                    </a>

                </div>


                {{-- Mobile button --}}
                <button
                    type="button"
                    @click="open = !open"
                    class="rounded-lg border border-slate-200 p-2 text-slate-700 lg:hidden"
                >
                    <i
                        x-show="!open"
                        data-lucide="menu"
                        class="h-5 w-5"
                    ></i>

                    <i
                        x-show="open"
                        data-lucide="x"
                        class="h-5 w-5"
                    ></i>
                </button>

            </div>


            {{-- Mobile navigation --}}
            <div
                x-show="open"
                x-cloak
                class="border-t border-slate-200 py-3 lg:hidden"
            >

                <div class="grid gap-1">

                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Home
                    </a>

                    <a href="{{ route('public.courses') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Courses
                    </a>

                    <a href="{{ route('public.facilities') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Facilities
                    </a>

                    <a href="{{ route('public.gallery') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Gallery
                    </a>

                    <a href="{{ route('public.events') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Events
                    </a>

                    <a href="{{ route('public.announcements') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        News
                    </a>

                    <a href="{{ route('public.alumni') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Alumni
                    </a>

                    <a href="{{ route('public.contact') }}" class="rounded-lg px-3 py-2 text-sm hover:bg-slate-50">
                        Contact
                    </a>

                    <a
                        href="{{ route('public.admission') }}"
                        class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white"
                    >
                        Apply Online
                    </a>

                </div>

            </div>

        </div>

    </header>


    {{-- Flash messages --}}
    <div class="container-site pt-4">

        @if(session('success'))

            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>

        @endif

        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">

                <div class="font-semibold">
                    Please correct the following:
                </div>

                <ul class="mt-1 list-disc pl-5">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

    </div>


    @yield('content')


    {{-- Footer --}}
    <footer class="mt-20 bg-slate-950 text-slate-300">

        <div class="container-site grid gap-10 py-12 md:grid-cols-2 lg:grid-cols-4">

            <div>

                <div class="flex items-center gap-3">


                    <img
                        src="{{ asset('images/gattc-logo.png') }}"
                        alt="GATTC Logo"
                        class="h-11 w-11 object-contain"
                    >

                    
                    <div>
                        <div class="font-semibold text-white">
                            {{ $shortName }}
                        </div>

                        <div class="text-xs text-slate-400">
                            Technical Training
                        </div>
                    </div>

                </div>

                <p class="mt-4 text-sm leading-6 text-slate-400">
                    Empowering learners through practical technical and vocational training for changing workforce needs.
                </p>

            </div>


            <div>

                <h3 class="font-semibold text-white">
                    Quick Links
                </h3>

                <div class="mt-4 grid gap-2 text-sm">

                    <a href="{{ route('public.courses') }}" class="hover:text-white">
                        Courses
                    </a>

                    <a href="{{ route('public.facilities') }}" class="hover:text-white">
                        Facilities
                    </a>

                    <a href="{{ route('public.gallery') }}" class="hover:text-white">
                        Gallery
                    </a>

                    <a href="{{ route('public.alumni') }}" class="hover:text-white">
                        Alumni
                    </a>

                </div>

            </div>


            <div>

                <h3 class="font-semibold text-white">
                    Admissions
                </h3>

                <div class="mt-4 grid gap-2 text-sm">

                    <a href="{{ route('public.admission') }}" class="hover:text-white">
                        Apply Online
                    </a>

                    <a href="{{ route('public.announcements') }}" class="hover:text-white">
                        Announcements
                    </a>

                    <a href="{{ route('public.events') }}" class="hover:text-white">
                        Events
                    </a>

                    <a href="{{ route('public.contact') }}" class="hover:text-white">
                        Contact
                    </a>

                </div>

            </div>


            <div>

                <h3 class="font-semibold text-white">
                    Contact
                </h3>

                <div class="mt-4 space-y-3 text-sm text-slate-400">

                    <div class="flex gap-2">
                        <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0"></i>
                        <span>{{ $address }}</span>
                    </div>

                    <div class="flex gap-2">
                        <i data-lucide="phone" class="h-4 w-4 shrink-0"></i>
                        <span>{{ $phone }}</span>
                    </div>

                    <div class="flex gap-2">
                        <i data-lucide="mail" class="h-4 w-4 shrink-0"></i>
                        <span>{{ $email }}</span>
                    </div>

                </div>

            </div>

        </div>


        <div class="border-t border-white/10">

            <div class="container-site flex flex-col gap-2 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">

                <span>
                    {{ $settings->get(
                        'footer_text',
                        'Government Advance Technical Training Centre, Hayatabad Peshawar'
                    ) }}
                </span>

                <span>
                    © {{ now()->year }} {{ $shortName }}. All rights reserved.
                </span>

            </div>

        </div>

    </footer>


    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')

</body>
</html>