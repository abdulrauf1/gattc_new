@extends('layouts.public')

@section('title', 'Alumni Registration')

@section('content')

<section class="bg-slate-950 py-20 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Stay connected
        </span>

        <h1 class="mt-3 text-4xl font-black sm:text-5xl">
            Alumni Registration
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Register your profile with GATTC and stay connected with the institute.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site max-w-4xl">

        <form
            method="POST"
            action="{{ route('public.alumni.register.store') }}"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >

            @csrf

            <div class="grid gap-5 p-6 md:grid-cols-2 md:p-8">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Full Name
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
                        required
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Course / Trade
                    </label>

                    <input
                        type="text"
                        name="course"
                        value="{{ old('course') }}"
                        required
                        placeholder="e.g. Computer Operator"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Graduation Year
                    </label>

                    <input
                        type="number"
                        name="graduation_year"
                        value="{{ old('graduation_year') }}"
                        min="1950"
                        max="{{ now()->year + 2 }}"
                        required
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Organization
                    </label>

                    <input
                        type="text"
                        name="organization"
                        value="{{ old('organization') }}"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Designation
                    </label>

                    <input
                        type="text"
                        name="designation"
                        value="{{ old('designation') }}"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Profile Photo
                    </label>

                    <input
                        type="file"
                        name="photo"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                    >
                </div>


                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Short Bio
                    </label>

                    <textarea
                        name="bio"
                        rows="5"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >{{ old('bio') }}</textarea>

                </div>

            </div>


            <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 md:px-8">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                >
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Submit Registration
                </button>

            </div>

        </form>

    </div>

</section>

@endsection