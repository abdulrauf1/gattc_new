@extends('layouts.public')

@section('title', 'Contact')

@php
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

    $facebook = $settings->get(
        'facebook',
        'GATTC Peshawar'
    );
@endphp

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Get in touch
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Contact GATTC
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Send us a message or use the institute contact details below.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site grid gap-8 lg:grid-cols-[.7fr_1.3fr]">

        <div class="space-y-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex gap-4">

                    <div class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                        <i data-lucide="map-pin" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <div class="font-semibold text-slate-900">
                            Address
                        </div>

                        <div class="mt-1 text-sm leading-6 text-slate-600">
                            {{ $address }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex gap-4">

                    <div class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                        <i data-lucide="phone" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <div class="font-semibold text-slate-900">
                            Phone
                        </div>

                        <a
                            href="tel:{{ $phone }}"
                            class="mt-1 block text-sm text-slate-600"
                        >
                            {{ $phone }}
                        </a>

                    </div>

                </div>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex gap-4">

                    <div class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                        <i data-lucide="mail" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <div class="font-semibold text-slate-900">
                            Email
                        </div>

                        <a
                            href="mailto:{{ $email }}"
                            class="mt-1 block text-sm text-slate-600"
                        >
                            {{ $email }}
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <div>

            <form
                method="POST"
                action="{{ route('public.contact.store') }}"
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8"
            >

                @csrf

                <h2 class="text-2xl font-black text-slate-900">
                    Send us a message
                </h2>

                <div class="mt-6 grid gap-5 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Subject
                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Message
                        </label>

                        <textarea
                            name="message"
                            rows="7"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >{{ old('message') }}</textarea>

                    </div>

                </div>


                <button
                    type="submit"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                >
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Send Message
                </button>

            </form>

        </div>

    </div>

</section>

@endsection