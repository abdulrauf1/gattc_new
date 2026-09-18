@extends('layouts.public')

@section('title', 'Apply Online')

@section('content')

@if(!$activeSession)

    <section class="section-padding">

        <div class="container-site max-w-3xl">

            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-8 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                    <i data-lucide="calendar-x-2" class="h-8 w-8"></i>
                </div>

                <h1 class="mt-5 text-2xl font-black text-slate-900">
                    Online admission is currently closed
                </h1>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">
                    Online admission will be available when the admission session is opened by the administration.
                </p>

                <a
                    href="{{ route('public.courses') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white"
                >
                    Explore Courses
                </a>

            </div>

        </div>

    </section>

@else

<section class="bg-slate-950 py-16 text-white">

    <div class="container-site">

        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">
            Online admission
        </span>

        <h1 class="mt-3 text-4xl font-black">
            {{ $activeSession->title }}
        </h1>

        <p class="mt-4 text-slate-300">
            Complete the form below to generate your admission fee voucher.
        </p>

    </div>

</section>


<section class="section-padding">

    <div class="container-site max-w-5xl">

        <form
            method="POST"
            action="{{ route('public.admission.store') }}"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >

            @csrf

            <div class="p-6 md:p-8">


                {{-- Course --}}
                <div>

                    <div class="mb-4 text-lg font-bold text-slate-900">
                        1. Select programme
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Course
                            </label>

                            <select
                                name="course_id"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >

                                <option value="">
                                    Select Course
                                </option>

                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        @selected(old('course_id') == $course->id)
                                    >
                                        {{ $course->title }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Batch
                            </label>

                            <select
                                name="course_batch_id"
                                class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >

                                <option value="">
                                    Select batch (optional)
                                </option>

                                {{-- Batch loading can be added through AJAX later. --}}

                            </select>

                        </div>

                    </div>

                </div>


                <div class="my-8 border-t border-slate-200"></div>


                {{-- Personal information --}}
                <div>

                    <div class="mb-4 text-lg font-bold text-slate-900">
                        2. Applicant information
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Student Name
                            </label>

                            <input
                                type="text"
                                name="student_name"
                                value="{{ old('student_name') }}"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Father Name
                            </label>

                            <input
                                type="text"
                                name="father_name"
                                value="{{ old('father_name') }}"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                CNIC
                            </label>

                            <input
                                type="text"
                                name="cnic"
                                value="{{ old('cnic') }}"
                                required
                                placeholder="xxxxx-xxxxxxx-x"
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth') }}"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Gender
                            </label>

                            <select
                                name="gender"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                                <option value="">
                                    Select gender
                                </option>

                                <option value="Male" @selected(old('gender') === 'Male')>
                                    Male
                                </option>

                                <option value="Female" @selected(old('gender') === 'Female')>
                                    Female
                                </option>

                                <option value="Other" @selected(old('gender') === 'Other')>
                                    Other
                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
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
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >

                        </div>


                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                required
                                class="w-full rounded-xl border-slate-300 text-sm"
                            >{{ old('address') }}</textarea>

                        </div>

                    </div>

                </div>


                <div class="my-8 border-t border-slate-200"></div>


                {{-- Additional vouchers --}}
                <div>

                    <div class="mb-1 text-lg font-bold text-slate-900">
                        3. Additional fee vouchers
                    </div>

                    <p class="mb-5 text-sm text-slate-500">
                        Regular course admission fee is generated automatically. Select additional vouchers where applicable.
                    </p>


                    <div class="grid gap-3 md:grid-cols-3">

                        <label class="cursor-pointer rounded-2xl border border-slate-200 p-4 hover:border-emerald-300">

                            <div class="flex gap-3">

                                <input
                                    type="checkbox"
                                    name="include_dit"
                                    value="1"
                                    @checked(old('include_dit'))
                                    class="mt-1 rounded border-slate-300 text-emerald-600"
                                >

                                <div>

                                    <div class="font-semibold text-slate-900">
                                        DIT / Second Shift
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        Generate a separate DIT/second-shift voucher if applicable.
                                    </div>

                                </div>

                            </div>

                        </label>


                        <label class="cursor-pointer rounded-2xl border border-slate-200 p-4 hover:border-emerald-300">

                            <div class="flex gap-3">

                                <input
                                    type="checkbox"
                                    name="include_hostel"
                                    value="1"
                                    @checked(old('include_hostel'))
                                    class="mt-1 rounded border-slate-300 text-emerald-600"
                                >

                                <div>

                                    <div class="font-semibold text-slate-900">
                                        Hostel
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        Generate hostel fee voucher where hostel fee configuration exists.
                                    </div>

                                </div>

                            </div>

                        </label>


                        <label class="cursor-pointer rounded-2xl border border-slate-200 p-4 hover:border-emerald-300">

                            <div class="flex gap-3">

                                <input
                                    type="checkbox"
                                    name="include_private"
                                    value="1"
                                    @checked(old('include_private'))
                                    class="mt-1 rounded border-slate-300 text-emerald-600"
                                >

                                <div>

                                    <div class="font-semibold text-slate-900">
                                        Private / IMC
                                    </div>

                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        Generate a private course voucher where configured.
                                    </div>

                                </div>

                            </div>

                        </label>

                    </div>

                </div>


                <div class="mt-8 rounded-2xl border border-blue-200 bg-blue-50 p-5 text-sm leading-6 text-blue-800">

                    <div class="flex gap-3">

                        <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0"></i>

                        <div>
                            After submitting this form, your voucher will be generated. Deposit the fee according to the bank information shown on the voucher, then submit your payment slip for verification.
                        </div>

                    </div>

                </div>

            </div>


            <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 md:px-8">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white hover:bg-emerald-700"
                >
                    Generate Voucher
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>

            </div>

        </form>

    </div>

</section>

@endif

@endsection