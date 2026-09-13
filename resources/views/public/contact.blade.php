@extends('layouts.public')

@section('title', 'Contact Us | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Get in Touch
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Contact GATTC
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Have a question about admissions, courses or training programs?
            Send us a message.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-5">

        <div class="lg:col-span-2">
            <h2 class="section-heading text-4xl font-black brand-blue">
                Visit Our Campus
            </h2>

            <p class="mt-7 leading-8 text-slate-600">
                Our team is available to guide you about technical training,
                admission procedures and available courses.
            </p>

            <div class="mt-8 space-y-5">
                <div class="flex gap-4">
                    <div class="text-2xl">📍</div>
                    <div>
                        <h3 class="font-black brand-blue">Address</h3>
                        <p class="mt-1 text-slate-600">
                            Hayatabad, Peshawar, Khyber Pakhtunkhwa
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="text-2xl">☎️</div>
                    <div>
                        <h3 class="font-black brand-blue">Phone</h3>
                        <p class="mt-1 text-slate-600">091-5881389</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="text-2xl">🌐</div>
                    <div>
                        <h3 class="font-black brand-blue">Website</h3>
                        <p class="mt-1 text-slate-600">gattc.edu.pk</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="soft-card p-7 sm:p-10 lg:col-span-3">
            <h2 class="text-3xl font-black brand-blue">
                Send Us a Message
            </h2>

            <form
                action="{{ route('public.contact.submit') }}"
                method="POST"
                class="mt-8 space-y-5"
            >
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block font-bold text-slate-700">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="Your name"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block font-bold text-slate-700">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="you@example.com"
                        >
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block font-bold text-slate-700">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="03XX-XXXXXXX"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block font-bold text-slate-700">
                            Subject
                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="Message subject"
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Message
                    </label>

                    <textarea
                        name="message"
                        rows="6"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Write your message..."
                    >{{ old('message') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-emerald-600 px-8 py-4 font-black text-white shadow-lg transition hover:bg-emerald-700"
                >
                    Send Message →
                </button>
            </form>
        </div>
    </div>
</section>

@endsection