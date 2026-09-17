<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>
        @yield(
            'title',
            config('app.name', 'GATTC Administration')
        )
    </title>


    {{-- Laravel Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>


    {{-- Alpine --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js">
    </script>


    @stack('styles')

</head>


<body
    class="bg-slate-100 text-slate-800 antialiased"
    x-data="{
        sidebarOpen: false,
        desktopSidebar: true
    }"
    @keydown.escape.window="sidebarOpen = false"
>


<div class="min-h-screen">


    {{-- ========================================================= --}}
    {{-- MOBILE SIDEBAR OVERLAY                                    --}}
    {{-- ========================================================= --}}

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        style="display:none;"
        @click="sidebarOpen = false">
    </div>



    {{-- ========================================================= --}}
    {{-- SIDEBAR                                                    --}}
    {{-- ========================================================= --}}

    <aside
        class="
            fixed
            inset-y-0
            left-0
            z-50
            w-[260px]
            bg-slate-950
            text-white
            border-r
            border-slate-800
            flex
            flex-col
            transform
            transition-transform
            duration-200
            ease-out
            -translate-x-full
            lg:translate-x-0
        "
        :class="{
            'translate-x-0': sidebarOpen
        }"
    >


        {{-- ===================================================== --}}
        {{-- BRAND                                                   --}}
        {{-- ===================================================== --}}

        <div
            class="
                h-16
                min-h-16
                px-4
                border-b
                border-slate-800
                flex
                items-center
                justify-between
                shrink-0
            "
        >

            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 min-w-0"
                @click="sidebarOpen = false"
            >

                {{-- Logo --}}
                <div
                    class="
                        w-9
                        h-9
                        rounded-lg
                        bg-white
                        flex
                        items-center
                        justify-center
                        shrink-0
                        overflow-hidden
                    "
                >

                    @if(
                        file_exists(
                            public_path(
                                'images/gattc-logo.png'
                            )
                        )
                    )

                        <img
                            src="{{ asset(
                                'images/gattc-logo.png'
                            ) }}"
                            alt="GATTC"
                            class="
                                w-full
                                h-full
                                object-contain
                                p-1
                            "
                        >

                    @else

                        <span
                            class="
                                text-slate-950
                                font-bold
                                text-lg
                            "
                        >
                            G
                        </span>

                    @endif

                </div>


                {{-- Brand text --}}
                <div class="min-w-0">

                    <div
                        class="
                            text-sm
                            font-bold
                            leading-5
                            truncate
                        "
                    >
                        GATTC
                    </div>

                    <div
                        class="
                            text-[10px]
                            text-slate-400
                            leading-4
                            truncate
                        "
                    >
                        Administration Portal
                    </div>

                </div>

            </a>


            {{-- Mobile close --}}
            <button
                type="button"
                class="
                    lg:hidden
                    w-8
                    h-8
                    rounded-lg
                    flex
                    items-center
                    justify-center
                    text-slate-400
                    hover:text-white
                    hover:bg-slate-800
                "
                @click="sidebarOpen = false"
            >

                <i
                    data-lucide="x"
                    class="w-4 h-4"
                ></i>

            </button>

        </div>



        {{-- ===================================================== --}}
        {{-- NAVIGATION                                              --}}
        {{-- ===================================================== --}}

        <nav
            class="
                flex-1
                overflow-y-auto
                px-2.5
                py-4
                scrollbar-thin
            "
        >


            {{-- ================================================= --}}
            {{-- MAIN                                              --}}
            {{-- ================================================= --}}

            <div class="mb-5">

                <div class="nav-section-title">
                    Main
                </div>


                <a
                    href="{{ route('admin.dashboard') }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.dashboard'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="layout-dashboard"
                        class="nav-icon"
                    ></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- ADMISSIONS                                         --}}
            {{-- ================================================= --}}

            <div class="mb-5">

                <div class="nav-section-title">
                    Admissions
                </div>


                {{-- Admission Sessions --}}
                <a
                    href="{{ route(
                        'admin.admission-sessions.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.admission-sessions.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="calendar-days"
                        class="nav-icon"
                    ></i>

                    <span>
                        Admission Sessions
                    </span>

                </a>


                {{-- Admissions --}}
                <a
                    href="{{ route(
                        'admin.admissions.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.admissions.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="graduation-cap"
                        class="nav-icon"
                    ></i>

                    <span>
                        Admissions
                    </span>

                </a>


                {{-- Vouchers --}}
                <a
                    href="{{ route(
                        'admin.vouchers.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.vouchers.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="receipt"
                        class="nav-icon"
                    ></i>

                    <span>
                        Vouchers
                    </span>

                </a>


                {{-- Payment Verification --}}
                <a
                    href="{{ route(
                        'admin.fee-payments.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.fee-payments.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="badge-check"
                        class="nav-icon"
                    ></i>

                    <span>
                        Payment Verification
                    </span>

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- ACADEMICS                                          --}}
            {{-- ================================================= --}}

            <div class="mb-5">

                <div class="nav-section-title">
                    Academics
                </div>


                {{-- Course Categories --}}
                <a
                    href="{{ route(
                        'admin.course-categories.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.course-categories.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="folders"
                        class="nav-icon"
                    ></i>

                    <span>
                        Course Categories
                    </span>

                </a>


                {{-- Courses --}}
                <a
                    href="{{ route(
                        'admin.courses.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.courses.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="book-open"
                        class="nav-icon"
                    ></i>

                    <span>
                        Courses
                    </span>

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- FINANCE                                            --}}
            {{-- ================================================= --}}

            <div class="mb-5">

                <div class="nav-section-title">
                    Finance
                </div>


                {{-- Bank Accounts --}}
                <a
                    href="{{ route(
                        'admin.bank-accounts.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.bank-accounts.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="landmark"
                        class="nav-icon"
                    ></i>

                    <span>
                        Bank Accounts
                    </span>

                </a>


                {{-- Generate Voucher --}}
                <a
                    href="{{ route(
                        'admin.vouchers.create'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.vouchers.create'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="file-plus-2"
                        class="nav-icon"
                    ></i>

                    <span>
                        Generate Voucher
                    </span>

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- WEBSITE MANAGEMENT                                 --}}
            {{-- ================================================= --}}

            <div class="mb-5">

                <div class="nav-section-title">
                    Website
                </div>


                {{-- Gallery --}}
                <a
                    href="{{ route(
                        'admin.gallery.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.gallery.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="images"
                        class="nav-icon"
                    ></i>

                    <span>
                        Gallery
                    </span>

                </a>


                {{-- Announcements --}}
                <a
                    href="{{ route(
                        'admin.announcements.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.announcements.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="megaphone"
                        class="nav-icon"
                    ></i>

                    <span>
                        Announcements
                    </span>

                </a>


                {{-- Events --}}
                <a
                    href="{{ route(
                        'admin.events.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.events.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="calendar-heart"
                        class="nav-icon"
                    ></i>

                    <span>
                        Events
                    </span>

                </a>


                {{-- Alumni --}}
                <a
                    href="{{ route(
                        'admin.alumni.index'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'admin.alumni.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="users-round"
                        class="nav-icon"
                    ></i>

                    <span>
                        Alumni
                    </span>

                </a>


                {{-- Contact Messages --}}
                @php
                    $unreadContactMessages = \App\Models\ContactMessage::whereNull(
                        'read_at'
                    )->count();
                @endphp

                <a
                    href="{{ route('admin.contact-messages.index') }}"
                    class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'nav-link-active' : '' }}"
                >

                    <span class="nav-icon">
                        <i data-lucide="messages-square" class="h-4 w-4"></i>
                    </span>

                    <span>Contact Messages</span>

                    @if($unreadContactMessages > 0)

                        <span class="ml-auto rounded-full bg-emerald-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">
                            {{ $unreadContactMessages > 99 ? '99+' : $unreadContactMessages }}
                        </span>

                    @endif

                </a>


                {{-- Website Settings --}}
                <a
                    href="{{ route('admin.website-settings.index') }}"
                    class="nav-link {{ request()->routeIs('admin.website-settings.*') ? 'nav-link-active' : '' }}"
                >

                    <span class="nav-icon">
                        <i data-lucide="settings-2" class="h-4 w-4"></i>
                    </span>

                    <span>Website Settings</span>

                </a>


            </div>



            {{-- ================================================= --}}
            {{-- ACCOUNT                                            --}}
            {{-- ================================================= --}}

            <div>

                <div class="nav-section-title">
                    Account
                </div>


                {{-- Profile --}}
                <a
                    href="{{ route(
                        'profile.edit'
                    ) }}"
                    @click="sidebarOpen = false"
                    class="nav-link
                        {{ request()->routeIs(
                            'profile.*'
                        )
                            ? 'nav-link-active'
                            : ''
                        }}"
                >

                    <i
                        data-lucide="user-cog"
                        class="nav-icon"
                    ></i>

                    <span>
                        Profile
                    </span>

                </a>


                {{-- Logout --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            nav-link
                            w-full
                            text-left
                            text-slate-400
                            hover:text-red-300
                        "
                    >

                        <i
                            data-lucide="log-out"
                            class="nav-icon"
                        ></i>

                        <span>
                            Logout
                        </span>

                    </button>

                </form>

            </div>


        </nav>



        {{-- ===================================================== --}}
        {{-- SIDEBAR FOOTER                                         --}}
        {{-- ===================================================== --}}

        <div
            class="
                shrink-0
                px-4
                py-3
                border-t
                border-slate-800
            "
        >

            <div
                class="
                    text-center
                    text-[10px]
                    leading-4
                    text-slate-500
                "
            >

                Government Advance Technical<br>
                Training Centre, Hayatabad Peshawar

            </div>

        </div>

    </aside>



    {{-- ========================================================= --}}
    {{-- DESKTOP CONTENT                                           --}}
    {{-- ========================================================= --}}

    <div
        class="
            min-h-screen
            lg:ml-[260px]
        "
    >


        {{-- ===================================================== --}}
        {{-- TOP BAR                                                --}}
        {{-- ===================================================== --}}

        <header
            class="
                sticky
                top-0
                z-30
                h-16
                bg-white
                border-b
                border-slate-200
                shadow-sm
            "
        >

            <div
                class="
                    h-full
                    px-4
                    sm:px-5
                    lg:px-6
                    flex
                    items-center
                    justify-between
                "
            >


                {{-- LEFT --}}
                <div class="flex items-center gap-3 min-w-0">

                    {{-- Mobile menu --}}
                    <button
                        type="button"
                        class="
                            lg:hidden
                            w-9
                            h-9
                            rounded-lg
                            bg-slate-100
                            hover:bg-slate-200
                            flex
                            items-center
                            justify-center
                            shrink-0
                        "
                        @click="sidebarOpen = true"
                    >

                        <i
                            data-lucide="menu"
                            class="w-5 h-5"
                        ></i>

                    </button>


                    <div class="min-w-0">

                        <p
                            class="
                                hidden
                                sm:block
                                text-[9px]
                                uppercase
                                tracking-[0.12em]
                                font-bold
                                text-slate-400
                                leading-4
                            "
                        >
                            GATTC ADMINISTRATION
                        </p>


                        <h1
                            class="
                                text-sm
                                sm:text-base
                                font-semibold
                                text-slate-900
                                truncate
                            "
                        >

                            @yield(
                                'page-heading',
                                'Administration'
                            )

                        </h1>

                    </div>

                </div>



                {{-- RIGHT --}}
                <div class="flex items-center gap-2">


                    {{-- View Website --}}
                    <a
                        href="{{ route('home') }}"
                        target="_blank"
                        class="
                            hidden
                            sm:inline-flex
                            h-9
                            px-3
                            rounded-lg
                            bg-slate-100
                            hover:bg-slate-200
                            text-slate-700
                            text-xs
                            font-semibold
                            items-center
                            gap-1.5
                        "
                    >

                        <i
                            data-lucide="external-link"
                            class="w-4 h-4"
                        ></i>

                        Website

                    </a>



                    {{-- User --}}
                    <div
                        class="
                            flex
                            items-center
                            gap-2
                            pl-1
                            sm:pl-2
                        "
                    >

                        <div
                            class="
                                w-9
                                h-9
                                rounded-full
                                bg-slate-900
                                text-white
                                flex
                                items-center
                                justify-center
                                font-bold
                                text-xs
                                shrink-0
                            "
                        >

                            {{ strtoupper(
                                substr(
                                    auth()->user()->name ?? 'A',
                                    0,
                                    1
                                )
                            ) }}

                        </div>


                        <div class="hidden md:block">

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    text-slate-800
                                    leading-4
                                "
                            >

                                {{ auth()->user()->name ?? 'Administrator' }}

                            </p>

                            <p
                                class="
                                    text-[10px]
                                    text-slate-400
                                    leading-4
                                "
                            >

                                Administrator

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </header>



        {{-- ===================================================== --}}
        {{-- MAIN CONTENT                                           --}}
        {{-- ===================================================== --}}

        <main
            class="
                p-3
                sm:p-4
                lg:p-5
                xl:p-6
            "
        >


            {{-- Success --}}
            @if(session('success'))

                <div
                    class="
                        mb-4
                        rounded-xl
                        border
                        border-emerald-200
                        bg-emerald-50
                        px-4
                        py-3
                    "
                >

                    <div
                        class="
                            flex
                            items-start
                            gap-2.5
                        "
                    >

                        <div
                            class="
                                w-7
                                h-7
                                rounded-lg
                                bg-emerald-100
                                flex
                                items-center
                                justify-center
                                shrink-0
                            "
                        >

                            <i
                                data-lucide="check-circle-2"
                                class="
                                    w-4
                                    h-4
                                    text-emerald-600
                                "
                            ></i>

                        </div>


                        <div>

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    text-emerald-800
                                "
                            >
                                Success
                            </p>

                            <p
                                class="
                                    text-xs
                                    text-emerald-700
                                    mt-0.5
                                "
                            >
                                {{ session('success') }}
                            </p>

                        </div>

                    </div>

                </div>

            @endif



            {{-- Error --}}
            @if(session('error'))

                <div
                    class="
                        mb-4
                        rounded-xl
                        border
                        border-red-200
                        bg-red-50
                        px-4
                        py-3
                    "
                >

                    <div
                        class="
                            flex
                            items-start
                            gap-2.5
                        "
                    >

                        <div
                            class="
                                w-7
                                h-7
                                rounded-lg
                                bg-red-100
                                flex
                                items-center
                                justify-center
                                shrink-0
                            "
                        >

                            <i
                                data-lucide="circle-alert"
                                class="
                                    w-4
                                    h-4
                                    text-red-600
                                "
                            ></i>

                        </div>


                        <div>

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    text-red-800
                                "
                            >
                                Error
                            </p>

                            <p
                                class="
                                    text-xs
                                    text-red-700
                                    mt-0.5
                                "
                            >
                                {{ session('error') }}
                            </p>

                        </div>

                    </div>

                </div>

            @endif



            {{-- Validation errors --}}
            @if($errors->any())

                <div
                    class="
                        mb-4
                        rounded-xl
                        border
                        border-red-200
                        bg-red-50
                        px-4
                        py-3
                    "
                >

                    <div class="flex items-start gap-2.5">

                        <i
                            data-lucide="triangle-alert"
                            class="
                                w-4
                                h-4
                                text-red-600
                                mt-0.5
                                shrink-0
                            "
                        ></i>


                        <div>

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    text-red-800
                                "
                            >
                                Please correct the following:
                            </p>


                            <ul
                                class="
                                    mt-1
                                    ml-4
                                    list-disc
                                    text-xs
                                    text-red-700
                                    space-y-0.5
                                "
                            >

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif



            {{-- PAGE CONTENT --}}
            @yield('content')

        </main>

    </div>

</div>



{{-- ============================================================= --}}
{{-- GLOBAL STYLES                                                 --}}
{{-- ============================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    .nav-section-title {
        padding-left: .75rem;
        padding-right: .75rem;
        margin-bottom: .45rem;

        font-size: .62rem;
        line-height: 1rem;

        text-transform: uppercase;
        letter-spacing: .12em;

        font-weight: 700;

        color: rgb(100 116 139);
    }


    .nav-link {
        min-height: 2.35rem;

        display: flex;
        align-items: center;

        gap: .7rem;

        width: 100%;

        margin-bottom: .15rem;

        padding:
            .52rem
            .75rem;

        border-radius: .6rem;

        color: rgb(203 213 225);

        font-size: .78rem;
        line-height: 1rem;

        font-weight: 500;

        transition:
            background-color .15s ease,
            color .15s ease;
    }


    .nav-link:hover {
        background: rgb(30 41 59);
        color: white;
    }


    .nav-link-active {
        background: rgb(15 118 110);
        color: white;
    }


    .nav-link-active:hover {
        background: rgb(13 148 136);
        color: white;
    }


    .nav-icon {
        width: 1rem;
        height: 1rem;

        flex-shrink: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Scrollbar
    |--------------------------------------------------------------------------
    */

    .scrollbar-thin {
        scrollbar-width: thin;
        scrollbar-color:
            rgb(51 65 85)
            transparent;
    }


    .scrollbar-thin::-webkit-scrollbar {
        width: 5px;
    }


    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }


    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: rgb(51 65 85);
        border-radius: 9999px;
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent horizontal overflow
    |--------------------------------------------------------------------------
    */

    html,
    body {
        overflow-x: hidden;
    }


    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1023px) {

        .nav-link {
            min-height: 2.5rem;
            font-size: .8rem;
        }

        .nav-icon {
            width: 1.05rem;
            height: 1.05rem;
        }

    }

</style>



{{-- ============================================================= --}}
{{-- GLOBAL SCRIPTS                                                --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        if (
            window.lucide &&
            typeof window.lucide.createIcons === 'function'
        ) {

            window.lucide.createIcons();

        }

    }
);

</script>


@stack('scripts')

</body>

</html>