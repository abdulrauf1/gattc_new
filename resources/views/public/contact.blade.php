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

            <form method="POST"
                action="{{ route('public.contact.store') }}"
                class="space-y-4">

                @csrf

                @if(session('success'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <ul class="list-disc pl-5 text-xs text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Name *
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm"
                            placeholder="Your name">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Email *
                        </label>

                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm"
                            placeholder="you@example.com">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Phone
                        </label>

                        <input type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm"
                            placeholder="03XX-XXXXXXX">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Subject
                        </label>

                        <input type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm"
                            placeholder="How can we help?">
                    </div>

                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Message *
                    </label>

                    <textarea name="message"
                            rows="7"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-6"
                            placeholder="Write your message...">{{ old('message') }}</textarea>
                </div>

                <button type="submit"
                        class="inline-flex h-11 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white hover:bg-emerald-600">

                    <i data-lucide="send" class="h-4 w-4"></i>
                    Send Message

                </button>

            </form>
        </div>
    </div>
</section>

@endsection