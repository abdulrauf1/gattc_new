<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'GATTC | Government Advance Technical Training Centre')
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'Government Advance Technical Training Centre, Hayatabad, Peshawar')"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
            font-family: "Inter", "Segoe UI", sans-serif;
        }

        .brand-blue {
            color: #073b70;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #073b70, #075985, #059669);
        }

        .page-hero {
            background:
                linear-gradient(90deg, rgba(3, 37, 65, .96), rgba(3, 37, 65, .72)),
                url('/images/campus-1.jpg') center/cover;
        }

        .section-heading::after {
            content: "";
            display: block;
            width: 70px;
            height: 4px;
            margin-top: 15px;
            border-radius: 999px;
            background: linear-gradient(90deg, #059669, #2563eb);
        }

        .nav-link {
            position: relative;
            color: #334155;
            font-weight: 700;
            transition: .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #059669;
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 999px;
            background: #059669;
        }

        .soft-card {
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            background: white;
            transition: .3s ease;
        }

        .soft-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .10);
        }
    </style>

    @stack('styles')
</head>

<body>

<header
    x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 20"
    :class="scrolled ? 'shadow-lg' : ''"
    class="fixed inset-x-0 top-0 z-50 bg-white/95 backdrop-blur"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-24 items-center justify-between gap-5">

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/gattc-logo.png') }}"
                    alt="GATTC Logo"
                    class="h-16 w-16 object-contain"
                >

                <div class="hidden border-l border-slate-200 pl-3 sm:block">
                    <div class="text-2xl font-black tracking-wide brand-blue">
                        GATTC
                    </div>

                    <div class="text-xs font-semibold leading-4 text-slate-600">
                        Government Advance<br>
                        Technical Training Centre
                    </div>

                    <div class="text-xs font-bold text-emerald-600">
                        Hayatabad, Peshawar
                    </div>
                </div>
            </a>

            <nav class="hidden items-center gap-6 lg:flex">
                <a
                    href="{{ route('home') }}"
                    class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                >
                    Home
                </a>

                <a
                    href="{{ route('public.courses') }}"
                    class="nav-link {{ request()->routeIs('public.courses*') ? 'active' : '' }}"
                >
                    Courses
                </a>

                <a
                    href="{{ route('public.facilities') }}"
                    class="nav-link {{ request()->routeIs('public.facilities') ? 'active' : '' }}"
                >
                    Facilities
                </a>

                <a
                    href="{{ route('public.gallery') }}"
                    class="nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}"
                >
                    Gallery
                </a>

                <a
                    href="{{ route('public.events') }}"
                    class="nav-link {{ request()->routeIs('public.events') ? 'active' : '' }}"
                >
                    Events
                </a>

                <a
                    href="{{ route('public.announcements') }}"
                    class="nav-link {{ request()->routeIs('public.announcements') ? 'active' : '' }}"
                >
                    News
                </a>

                <a
                    href="{{ route('public.alumni') }}"
                    class="nav-link {{ request()->routeIs('public.alumni*') ? 'active' : '' }}"
                >
                    Alumni
                </a>

                <a
                    href="{{ route('public.contact') }}"
                    class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}"
                >
                    Contact
                </a>

                <a
                    href="{{ route('public.admission') }}"
                    class="rounded-full bg-emerald-600 px-6 py-3 font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700"
                >
                    Apply Online
                </a>
            </nav>

            <button
                @click="open = !open"
                class="rounded-xl p-2 text-slate-700 hover:bg-slate-100 lg:hidden"
                aria-label="Toggle navigation"
            >
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>

                <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak class="border-t border-slate-100 py-5 lg:hidden">
            <div class="flex flex-col gap-5">
                <a href="{{ route('home') }}" class="font-bold">Home</a>
                <a href="{{ route('public.courses') }}" class="font-bold">Courses</a>
                <a href="{{ route('public.facilities') }}" class="font-bold">Facilities</a>
                <a href="{{ route('public.gallery') }}" class="font-bold">Gallery</a>
                <a href="{{ route('public.events') }}" class="font-bold">Events</a>
                <a href="{{ route('public.announcements') }}" class="font-bold">Announcements</a>
                <a href="{{ route('public.alumni') }}" class="font-bold">Alumni</a>
                <a href="{{ route('public.contact') }}" class="font-bold">Contact</a>

                <a
                    href="{{ route('public.admission') }}"
                    class="w-fit rounded-full bg-emerald-600 px-6 py-3 font-bold text-white"
                >
                    Apply Online
                </a>
            </div>
        </div>
    </div>
</header>

<main class="pt-24">

    @if(session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-[#032541] text-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">

        <div>
            <img
                src="{{ asset('images/gattc-logo.png') }}"
                alt="GATTC Logo"
                class="mb-5 h-24 w-24 object-contain"
            >

            <h3 class="text-2xl font-black">GATTC</h3>

            <p class="mt-4 max-w-sm leading-7 text-slate-300">
                Government Advance Technical Training Centre provides
                practical and career-oriented technical education.
            </p>
        </div>

        <div>
            <h3 class="text-xl font-black">Explore</h3>

            <div class="mt-5 grid grid-cols-2 gap-3 text-slate-300">
                <a href="{{ route('public.courses') }}" class="hover:text-emerald-300">Courses</a>
                <a href="{{ route('public.facilities') }}" class="hover:text-emerald-300">Facilities</a>
                <a href="{{ route('public.gallery') }}" class="hover:text-emerald-300">Gallery</a>
                <a href="{{ route('public.events') }}" class="hover:text-emerald-300">Events</a>
                <a href="{{ route('public.alumni') }}" class="hover:text-emerald-300">Alumni</a>
                <a href="{{ route('public.contact') }}" class="hover:text-emerald-300">Contact</a>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-black">Contact</h3>

            <div class="mt-5 space-y-4 text-slate-300">
                <p>📍 Hayatabad, Peshawar, Khyber Pakhtunkhwa</p>
                <p>☎️ 091-5881389</p>
                <p>🌐 gattc.edu.pk</p>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col justify-between gap-3 px-4 py-6 text-center text-sm text-slate-400 sm:px-6 md:flex-row md:text-left lg:px-8">
            <p>© {{ date('Y') }} GATTC. All rights reserved.</p>

            <p class="font-bold tracking-[0.2em] text-emerald-400">
                SKILLS TODAY · SUCCESS TOMORROW
            </p>
        </div>
    </div>
</footer>

@stack('scripts')

</body>
</html>