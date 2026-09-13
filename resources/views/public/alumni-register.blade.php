@extends('layouts.public')

@section('title', 'Alumni Registration | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            Stay Connected
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Alumni Registration
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Register yourself as a GATTC graduate and become part of our
            growing alumni community.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">

        <div class="mb-10 text-center">
            <h2 class="text-4xl font-black brand-blue">
                Graduate Registration Form
            </h2>

            <p class="mt-4 text-slate-600">
                Your profile will be reviewed by the administration before publication.
            </p>
        </div>

        <form
            action="{{ route('public.alumni.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="soft-card p-7 sm:p-10"
        >
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Full Name *
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Your full name"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Email Address *
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

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Phone Number *
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="03XX-XXXXXXX"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Course / Trade *
                    </label>

                    <input
                        type="text"
                        name="course"
                        value="{{ old('course') }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="e.g. Computer Operator"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Graduation Year *
                    </label>

                    <input
                        type="number"
                        name="graduation_year"
                        value="{{ old('graduation_year') }}"
                        min="1950"
                        max="{{ now()->year }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="{{ now()->year }}"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Current Organization
                    </label>

                    <input
                        type="text"
                        name="organization"
                        value="{{ old('organization') }}"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Company / organization"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Current Designation
                    </label>

                    <input
                        type="text"
                        name="designation"
                        value="{{ old('designation') }}"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Your job title"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-bold text-slate-700">
                        Profile Photo
                    </label>

                    <input
                        type="file"
                        name="photo"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Maximum size: 2 MB.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-2 block font-bold text-slate-700">
                        Short Biography
                    </label>

                    <textarea
                        name="bio"
                        rows="5"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Tell us briefly about your professional journey..."
                    >{{ old('bio') }}</textarea>
                </div>
            </div>

            <div class="mt-8 rounded-xl border border-blue-100 bg-blue-50 p-5 text-sm leading-6 text-blue-900">
                Your registration will remain pending until it is reviewed
                and approved by the GATTC administration.
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                <button
                    type="submit"
                    class="rounded-full bg-emerald-600 px-8 py-4 font-black text-white hover:bg-emerald-700"
                >
                    Submit Registration →
                </button>

                <a
                    href="{{ route('public.alumni') }}"
                    class="rounded-full border-2 border-slate-300 px-8 py-4 font-bold text-slate-700 hover:bg-slate-50"
                >
                    View Alumni
                </a>
            </div>
        </form>
    </div>
</section>

@endsection