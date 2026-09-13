@extends('layouts.public')

@section('title', 'Admission Portal | GATTC')

@section('content')

<section class="page-hero px-4 py-28 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="font-bold uppercase tracking-[0.3em] text-emerald-300">
            GATTC Admissions
        </p>

        <h1 class="mt-5 text-5xl font-black sm:text-6xl">
            Admission Portal
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
            Apply online for technical and vocational training programs
            at Government Advance Technical Training Centre.
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">

        @if($activeSession)

            <!-- Open Admission Session -->
            <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-xl">

                <div class="brand-gradient px-7 py-10 text-white sm:px-12">
                    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">

                        <div>
                            <div class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-bold">
                                Admissions Open
                            </div>

                            <h2 class="mt-5 text-3xl font-black sm:text-4xl">
                                {{ $activeSession->name ?? 'Current Admission Session' }}
                            </h2>

                            <p class="mt-3 max-w-2xl text-slate-200">
                                Applications are currently being accepted.
                                Complete your application before the closing date.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-5 text-center">
                            <div class="text-sm font-semibold text-emerald-200">
                                Application Deadline
                            </div>

                            <div class="mt-2 text-2xl font-black">
                                {{ \Carbon\Carbon::parse($activeSession->closing_date)->format('d M Y') }}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-7 sm:p-12">

                    <div class="mt-10 rounded-2xl border border-blue-100 bg-blue-50 p-6 text-blue-900">
                        <h3 class="font-black">
                            Before You Apply
                        </h3>

                        <ul class="mt-3 list-disc space-y-2 pl-5 leading-7">
                            <li>Keep your personal information ready.</li>
                            <li>Enter your contact details carefully.</li>
                            <li>Review your application before submission.</li>
                            <li>Keep your application or voucher information safe.</li>
                        </ul>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('public.admission.store') }}"
                        class="space-y-6"
                    >
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">
                                Select Course
                            </label>

                            <select
                                name="course_id"
                                required
                                class="mt-2 w-full rounded-xl border-gray-300"
                            >
                                <option value="">Select a course</option>

                                @foreach ($courses as $course)
                                    <option
                                        value="{{ $course->id }}"
                                        @selected(old('course_id') == $course->id)
                                    >
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('course_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    Student Name
                                </label>

                                <input
                                    type="text"
                                    name="student_name"
                                    value="{{ old('student_name') }}"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >

                                @error('student_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    Father Name
                                </label>

                                <input
                                    type="text"
                                    name="father_name"
                                    value="{{ old('father_name') }}"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >

                                @error('father_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    CNIC
                                </label>

                                <input
                                    type="text"
                                    name="cnic"
                                    value="{{ old('cnic') }}"
                                    placeholder="12345-1234567-1"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >

                                @error('cnic')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth') }}"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >

                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >
                                    <option value="">Select gender</option>
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

                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300"
                                >

                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="mt-2 w-full rounded-xl border-gray-300"
                            >

                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                required
                                class="mt-2 w-full rounded-xl border-gray-300"
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-700 px-6 py-3 font-semibold text-white hover:bg-blue-800"
                        >
                            Generate Fee Voucher
                        </button>
                    </form>

                </div>
            </div>

        @else

            <!-- Closed Admission Session -->
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 text-center shadow-xl sm:p-14">

                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-amber-100 text-5xl">
                    🔒
                </div>

                <h2 class="mt-8 text-3xl font-black text-[#073b70] sm:text-4xl">
                    Admission Portal
                </h2>

                <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    Online admission will be available when the admission
                    session is opened by the administration.
                </p>

                <div class="mx-auto mt-8 max-w-2xl rounded-2xl border border-blue-100 bg-blue-50 p-6 text-left text-blue-900">
                    <h3 class="font-black">
                        What You Can Do Now
                    </h3>

                    <ul class="mt-3 list-disc space-y-2 pl-5 leading-7">
                        <li>Explore the available training courses.</li>
                        <li>Review our facilities and campus gallery.</li>
                        <li>Check announcements for admission updates.</li>
                        <li>Contact GATTC for further information.</li>
                    </ul>
                </div>

                <div class="mt-9 flex flex-wrap justify-center gap-4">
                    <a
                        href="{{ route('public.courses') }}"
                        class="rounded-full bg-[#073b70] px-7 py-3 font-bold text-white hover:bg-[#052b52]"
                    >
                        Explore Courses
                    </a>

                    <a
                        href="{{ route('public.announcements') }}"
                        class="rounded-full border-2 border-emerald-600 px-7 py-3 font-bold text-emerald-700 hover:bg-emerald-50"
                    >
                        View Announcements
                    </a>

                    <a
                        href="{{ route('public.contact') }}"
                        class="rounded-full border-2 border-slate-300 px-7 py-3 font-bold text-slate-700 hover:bg-slate-50"
                    >
                        Contact Us
                    </a>
                </div>
            </div>

        @endif

    </div>
</section>

@endsection