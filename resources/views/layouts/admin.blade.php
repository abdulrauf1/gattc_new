<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard') - GATTC Admin
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')

</head>


<body class="bg-slate-100 text-slate-800">

<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen"
>


    {{-- =========================================================
         MOBILE OVERLAY
    ========================================================== --}}

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden"
        style="display: none;"
    ></div>


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside
        class="fixed inset-y-0 left-0 z-50 w-72
               bg-slate-950 text-white
               transform transition-transform duration-300
               lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >


        {{-- =====================================================
             LOGO / BRANDING
        ====================================================== --}}

        <div
            class="flex h-20 items-center gap-3
                   border-b border-white/10 px-6"
        >

            <div
                class="flex h-11 w-11 items-center justify-center
                       rounded-xl bg-emerald-500 shadow-lg"
            >
                <i
                    data-lucide="graduation-cap"
                    class="h-6 w-6"
                ></i>
            </div>


            <div>

                <h1 class="text-lg font-bold tracking-wide">
                    GATTC
                </h1>

                <p class="text-xs text-slate-400">
                    Admin Portal
                </p>

            </div>


            {{-- Mobile close button --}}

            <button
                @click="sidebarOpen = false"
                type="button"
                class="ml-auto rounded-lg p-2
                       text-slate-400
                       hover:bg-white/10
                       lg:hidden"
            >
                <i
                    data-lucide="x"
                    class="h-5 w-5"
                ></i>
            </button>

        </div>



        {{-- =====================================================
             NAVIGATION
        ====================================================== --}}

        <nav
            class="h-[calc(100vh-5rem)]
                   overflow-y-auto px-4 py-6"
        >


            {{-- =================================================
                 MAIN
            ================================================== --}}

            <p
                class="mb-3 px-3 text-[11px] font-semibold
                       uppercase tracking-widest text-slate-500"
            >
                Main
            </p>


            <div class="space-y-1">

                {{-- Dashboard --}}

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.dashboard')
                                ? 'bg-emerald-500 text-white shadow-lg'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="layout-dashboard"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>



            {{-- =================================================
                 ADMISSIONS
            ================================================== --}}

            <p
                class="mb-3 mt-8 px-3 text-[11px]
                       font-semibold uppercase tracking-widest
                       text-slate-500"
            >
                Admissions
            </p>


            <div class="space-y-1">


                {{-- Admission Sessions --}}

                <a
                    href="{{ route('admin.admission-sessions.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.admission-sessions.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="calendar-days"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Admission Sessions
                    </span>

                </a>


                {{-- Admissions --}}

                <a
                    href="{{ route('admin.admissions.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.admissions.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="graduation-cap"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Admissions
                    </span>

                </a>


                {{-- Vouchers --}}

                <a
                    href="{{ route('admin.vouchers.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.vouchers.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="file-text"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Vouchers
                    </span>

                </a>


                {{-- Payment Verification --}}

                <a
                    href="{{ route('admin.fee-payments.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.fee-payments.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="badge-check"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Payment Verification
                    </span>

                </a>

            </div>



            {{-- =================================================
                 ACADEMICS
            ================================================== --}}

            <p
                class="mb-3 mt-8 px-3 text-[11px]
                       font-semibold uppercase tracking-widest
                       text-slate-500"
            >
                Academics
            </p>


            <div class="space-y-1">


                {{-- Course Categories --}}

                <a
                    href="{{ route('admin.course-categories.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.course-categories.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="layers"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Course Categories
                    </span>

                </a>


                {{-- Courses --}}

                <a
                    href="{{ route('admin.courses.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.courses.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="book-open"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Courses
                    </span>

                </a>

            </div>



            {{-- =================================================
                 FINANCE
            ================================================== --}}

            <p
                class="mb-3 mt-8 px-3 text-[11px]
                       font-semibold uppercase tracking-widest
                       text-slate-500"
            >
                Finance
            </p>


            <div class="space-y-1">


                {{-- Bank Accounts --}}

                <a
                    href="{{ route('admin.bank-accounts.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.bank-accounts.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="landmark"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Bank Accounts
                    </span>

                </a>


                {{-- Voucher shortcut --}}

                <a
                    href="{{ route('admin.vouchers.create') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.vouchers.create')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="file-plus-2"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Generate Voucher
                    </span>

                </a>


                {{-- Payment Verification shortcut --}}

                <a
                    href="{{ route('admin.fee-payments.index') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('admin.fee-payments.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="credit-card"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Payments
                    </span>

                </a>

            </div>



            {{-- =================================================
                 WEBSITE
            ================================================== --}}

            <p
                class="mb-3 mt-8 px-3 text-[11px]
                       font-semibold uppercase tracking-widest
                       text-slate-500"
            >
                Website
            </p>


            <div class="space-y-1">


                {{-- Public Website --}}

                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           text-slate-300 transition
                           hover:bg-white/10 hover:text-white"
                >

                    <i
                        data-lucide="external-link"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        View Website
                    </span>

                </a>


                {{-- Courses Website --}}

                <a
                    href="{{ route('public.courses') }}"
                    target="_blank"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           text-slate-300 transition
                           hover:bg-white/10 hover:text-white"
                >

                    <i
                        data-lucide="book-copy"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Public Courses
                    </span>

                </a>


                {{-- Announcements --}}

                <a
                    href="{{ route('public.announcements') }}"
                    target="_blank"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           text-slate-300 transition
                           hover:bg-white/10 hover:text-white"
                >

                    <i
                        data-lucide="megaphone"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Announcements
                    </span>

                </a>


                {{-- Events --}}

                <a
                    href="{{ route('public.events') }}"
                    target="_blank"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           text-slate-300 transition
                           hover:bg-white/10 hover:text-white"
                >

                    <i
                        data-lucide="calendar"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Events
                    </span>

                </a>


                {{-- Alumni --}}

                <a
                    href="{{ route('public.alumni') }}"
                    target="_blank"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           text-slate-300 transition
                           hover:bg-white/10 hover:text-white"
                >

                    <i
                        data-lucide="users-round"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        Alumni
                    </span>

                </a>

            </div>



            {{-- =================================================
                 ACCOUNT
            ================================================== --}}

            <p
                class="mb-3 mt-8 px-3 text-[11px]
                       font-semibold uppercase tracking-widest
                       text-slate-500"
            >
                Account
            </p>


            <div class="space-y-1 pb-6">


                {{-- Profile --}}

                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 rounded-xl
                           px-3 py-2.5 text-sm font-medium
                           transition
                           {{ request()->routeIs('profile.*')
                                ? 'bg-emerald-500 text-white'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >

                    <i
                        data-lucide="user-circle"
                        class="h-5 w-5"
                    ></i>

                    <span>
                        My Profile
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
                        class="flex w-full items-center gap-3
                               rounded-xl px-3 py-2.5
                               text-sm font-medium
                               text-slate-300
                               hover:bg-red-500/10
                               hover:text-red-400"
                    >

                        <i
                            data-lucide="log-out"
                            class="h-5 w-5"
                        ></i>

                        <span>
                            Logout
                        </span>

                    </button>

                </form>

            </div>

        </nav>

    </aside>



    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="lg:pl-72">


        {{-- =====================================================
             TOP HEADER
        ====================================================== --}}

        <header
            class="sticky top-0 z-30 border-b border-slate-200
                   bg-white/95 backdrop-blur"
        >

            <div
                class="flex h-20 items-center justify-between
                       px-4 sm:px-6 lg:px-8"
            >


                {{-- Mobile menu --}}

                <button
                    @click="sidebarOpen = true"
                    type="button"
                    class="rounded-xl p-2.5 text-slate-600
                           hover:bg-slate-100 lg:hidden"
                >

                    <i
                        data-lucide="menu"
                        class="h-6 w-6"
                    ></i>

                </button>


                {{-- Page heading --}}

                <div class="hidden sm:block">

                    <p
                        class="text-xs font-medium uppercase
                               tracking-wider text-emerald-600"
                    >
                        Government Advance Technical Training Centre
                    </p>

                    <h2 class="text-lg font-bold text-slate-900">
                        @yield('page-heading', 'Administration')
                    </h2>

                </div>


                {{-- Current User --}}

                <div class="flex items-center gap-3">

                    <div class="hidden text-right sm:block">

                        <p class="text-sm font-semibold text-slate-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Administrator
                        </p>

                    </div>


                    <div
                        class="flex h-10 w-10 items-center
                               justify-center rounded-full
                               bg-emerald-100
                               font-semibold text-emerald-700"
                    >
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                </div>

            </div>

        </header>



        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <main class="p-4 sm:p-6 lg:p-8">


            {{-- =================================================
                 SUCCESS MESSAGE
            ================================================== --}}

            @if(session('success'))

                <div
                    class="mb-6 flex items-center gap-3
                           rounded-xl border border-emerald-200
                           bg-emerald-50 px-4 py-3
                           text-sm text-emerald-700"
                >

                    <i
                        data-lucide="check-circle"
                        class="h-5 w-5 shrink-0"
                    ></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif



            {{-- =================================================
                 ERROR MESSAGE
            ================================================== --}}

            @if(session('error'))

                <div
                    class="mb-6 flex items-center gap-3
                           rounded-xl border border-red-200
                           bg-red-50 px-4 py-3
                           text-sm text-red-700"
                >

                    <i
                        data-lucide="alert-circle"
                        class="h-5 w-5 shrink-0"
                    ></i>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

            @endif



            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}

            @if($errors->any())

                <div
                    class="mb-6 rounded-xl
                           border border-red-200
                           bg-red-50 p-4
                           text-sm text-red-700"
                >

                    <div class="flex items-start gap-3">

                        <i
                            data-lucide="triangle-alert"
                            class="mt-0.5 h-5 w-5 shrink-0"
                        ></i>

                        <div>

                            <p class="mb-2 font-semibold">
                                Please correct the following:
                            </p>

                            <ul
                                class="list-disc space-y-1 pl-5"
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



            {{-- =================================================
                 PAGE CONTENT FROM CHILD VIEW
            ================================================== --}}

            @yield('content')

        </main>

    </div>

</div>



{{-- =============================================================
     LUCIDE INITIALIZATION
============================================================== --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    });

</script>


@stack('scripts')

</body>

</html>