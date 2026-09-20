@extends('layouts.public')

@section('title', 'Apply Online - GATTC')

@section('content')

<div class="min-h-screen bg-slate-50 py-10">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8 text-center">

            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                GATTC Online Admission
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                Apply Online
            </h1>

            <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                Complete your application details and generate your official
                GATTC fee voucher(s).
            </p>

        </div>


        {{-- CLOSED --}}
        @if (!$activeSession)

            <div class="mx-auto max-w-3xl rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">

                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
                    <svg class="h-7 w-7 text-amber-700"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 2.57h16.94A2 2 0 0022.18 18L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-slate-900">
                    Online Admissions Currently Closed
                </h2>

                <p class="mt-2 text-sm text-slate-600">
                    Online admission will be available when the admission
                    session is opened by the administration.
                </p>

            </div>

        @else

            {{-- SESSION --}}
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">
                            Current Admission Session
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-slate-900">
                            {{ $activeSession->title }}
                        </h2>
                    </div>

                    <div class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <div>
                            <span class="font-semibold">Opening:</span>
                            {{ \Carbon\Carbon::parse($activeSession->opening_date)->format('d M Y') }}
                        </div>

                        <div>
                            <span class="font-semibold">Closing:</span>
                            {{ \Carbon\Carbon::parse($activeSession->closing_date)->format('d M Y') }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- ERRORS --}}
            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <div class="font-semibold text-red-800">
                        Please correct the following:
                    </div>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('public.admission.store') }}">

                @csrf

                <div class="space-y-6">

                    

                   

                    {{-- PERSONAL INFORMATION --}}
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">
                                1. Applicant Information
                            </h2>
                        </div>


                        <div class="grid gap-5 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Student Name <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="student_name"
                                       value="{{ old('student_name') }}"
                                       required
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </div>


                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Father Name <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="father_name"
                                       value="{{ old('father_name') }}"
                                       required
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </div>


                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    CNIC <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="cnic"
                                       value="{{ old('cnic') }}"
                                       placeholder="xxxxx-xxxxxxx-x"
                                       required
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </div>


                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>

                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       required
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </div>


                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Gender <span class="text-red-500">*</span>
                                </label>

                                <select name="gender"
                                        required
                                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">

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
                                    Mobile / Contact No. <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       required
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </div>


                            <div class="md:col-span-2">

                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">

                            </div>


                            <div class="md:col-span-2">

                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Address <span class="text-red-500">*</span>
                                </label>

                                <textarea name="address"
                                          rows="4"
                                          required
                                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('address') }}</textarea>

                            </div>

                        </div>

                    </section>

{{-- ==============================================================
     PROGRAMME SELECTION
     ============================================================== --}}
<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-6">

        <h2 class="text-lg font-bold text-slate-900">
            1. Select Training Programme(s)
        </h2>

        <p class="mt-1 text-sm leading-6 text-slate-500">
            Select one programme, or select one Morning programme and
            one Evening programme. Only one Evening programme can be selected.
        </p>

    </div>


    {{-- ==============================================================
         MORNING
         ============================================================== --}}
    @if ($regularCourses->count())

        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <div class="mb-4">

                <span class="inline-flex rounded-lg bg-emerald-600 px-3 py-1 text-xs font-bold text-white">
                    MORNING
                </span>

                <h3 class="mt-2 font-bold text-slate-900">
                    Regular Courses
                </h3>

            </div>


            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Regular / Morning Course
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>


            <select
                name="regular_course_id"
                id="regular_course_id"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
            >

                <option value="">
                    Do not apply for Morning course
                </option>

                @foreach ($regularCourses as $course)

                    <option
                        value="{{ $course->id }}"
                        @selected(old('regular_course_id') == $course->id)
                    >
                        {{ $course->title }}

                        @if ($course->duration)
                            — {{ $course->duration }}
                        @endif

                        — Rs. {{ number_format($course->fee_amount, 2) }}
                    </option>

                @endforeach

            </select>

        </div>

    @endif


    {{-- ==============================================================
         EVENING
         ============================================================== --}}
    @if (
        $ditCourses->count() ||
        $privateCourses->count()
    )

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">

            <div class="mb-4">

                <span class="inline-flex rounded-lg bg-blue-600 px-3 py-1 text-xs font-bold text-white">
                    EVENING
                </span>

                <h3 class="mt-2 font-bold text-slate-900">
                    Evening Courses
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    Select only one Evening course.
                </p>

            </div>


            <div class="space-y-3">

                {{-- NONE --}}
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">

                    <input
                        type="radio"
                        name="evening_programme"
                        value=""
                        @checked(
                            !old('dit_course_id') &&
                            !old('private_course_id')
                        )
                        class="mt-1 h-4 w-4"
                    >

                    <span>

                        <span class="block text-sm font-semibold text-slate-800">
                            No Evening Course
                        </span>

                        <span class="block text-xs text-slate-500">
                            Apply only for Morning / Regular course
                        </span>

                    </span>

                </label>


                {{-- DIT --}}
                @foreach ($ditCourses as $course)

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-blue-200 bg-white p-4">

                        <input
                            type="radio"
                            name="evening_programme"
                            value="dit:{{ $course->id }}"
                            @checked(
                                old('dit_course_id') == $course->id
                            )
                            class="mt-1 h-4 w-4 text-blue-600"
                        >

                        <span>

                            <span class="block text-sm font-bold text-slate-900">
                                {{ $course->title }}
                            </span>

                            <span class="mt-1 block text-xs text-slate-500">
                                DIT / Second Shift

                                @if ($course->duration)
                                    — {{ $course->duration }}
                                @endif

                                — Rs.
                                {{ number_format($course->fee_amount, 2) }}
                            </span>

                        </span>

                    </label>

                @endforeach


                {{-- PRIVATE / IMC --}}
                @foreach ($privateCourses as $course)

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-purple-200 bg-white p-4">

                        <input
                            type="radio"
                            name="evening_programme"
                            value="private:{{ $course->id }}"
                            @checked(
                                old('private_course_id') == $course->id
                            )
                            class="mt-1 h-4 w-4 text-purple-600"
                        >

                        <span>

                            <span class="block text-sm font-bold text-slate-900">
                                {{ $course->title }}
                            </span>

                            <span class="mt-1 block text-xs text-slate-500">
                                Private / IMC

                                @if ($course->duration)
                                    — {{ $course->duration }}
                                @endif

                                — Rs.
                                {{ number_format($course->fee_amount, 2) }}
                            </span>

                        </span>

                    </label>

                @endforeach

            </div>


            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-100 p-3 text-xs leading-5 text-blue-800">

                <strong>Important:</strong>
                DIT / Second Shift and Private / IMC are both Evening
                programmes. Only one of them can be selected.

            </div>

        </div>

    @endif

</section>


{{-- ==============================================================
     HOSTEL
     ============================================================== --}}
<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-4">

        <h2 class="text-lg font-bold text-slate-900">
            2. Hostel
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Hostel is optional and generates a separate voucher.
        </p>

    </div>


    @if ($hostelFee !== null)

        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-5">

            <input
                type="checkbox"
                name="apply_hostel"
                value="1"
                @checked(old('apply_hostel'))
                class="mt-1 h-5 w-5 rounded border-slate-300 text-amber-600"
            >

            <span>

                <span class="block font-bold text-slate-900">
                    Apply for Hostel
                </span>

                <span class="mt-1 block text-sm text-slate-600">
                    Hostel Fee:
                    <strong>
                        Rs. {{ number_format($hostelFee, 2) }}
                    </strong>
                </span>

            </span>

        </label>

    @else

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            Hostel fee has not been configured.
        </div>

    @endif

</section>


<input
    type="hidden"
    name="dit_course_id"
    id="dit_course_id"
    value="{{ old('dit_course_id') }}"
>

<input
    type="hidden"
    name="private_course_id"
    id="private_course_id"
    value="{{ old('private_course_id') }}"
>


                    


                    {{-- PAYMENT INSTRUCTION --}}
                    <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                        <div class="flex gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white">

                                <svg class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V4m0 16v-2m7-6a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>

                            </div>


                            <div>

                                <h3 class="font-bold text-slate-900">
                                    Payment Procedure
                                </h3>

                                <ol class="mt-2 space-y-1 text-sm leading-6 text-slate-700">
                                    <li>1. Submit this online application.</li>
                                    <li>2. Print the generated voucher(s).</li>
                                    <li>3. Deposit the required fee at the designated Bank of Khyber account.</li>
                                    <li>4. Present the original bank deposit slip to the GATTC admission office.</li>
                                    <li>5. The authorized clerk will record and verify the deposited payment in the Admin Portal.</li>
                                </ol>

                                <p class="mt-3 text-xs font-semibold text-emerald-800">
                                    Bank slip upload is NOT required from the student on this website.
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- SUBMIT --}}
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">

                            <svg class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                            Submit Application & Generate Voucher

                        </button>

                    </div>

                </div>

            </form>

        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const eveningRadios =
        document.querySelectorAll(
            'input[name="evening_programme"]'
        );

    const ditField =
        document.getElementById(
            'dit_course_id'
        );

    const privateField =
        document.getElementById(
            'private_course_id'
        );


    function updateEveningProgramme() {

        ditField.value = '';
        privateField.value = '';


        eveningRadios.forEach(function (radio) {

            if (
                radio.checked &&
                radio.value !== ''
            ) {

                const parts =
                    radio.value.split(':');


                const type =
                    parts[0];

                const id =
                    parts[1];


                if (type === 'dit') {
                    ditField.value = id;
                }


                if (type === 'private') {
                    privateField.value = id;
                }

            }

        });
    }


    eveningRadios.forEach(function (radio) {

        radio.addEventListener(
            'change',
            updateEveningProgramme
        );

    });


    updateEveningProgramme();

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const regularSelect = document.getElementById('regular_course_id');

    const eveningRadios = document.querySelectorAll(
        'input[name="evening_programme"]'
    );

    const ditHidden = document.getElementById('dit_course_id');

    const privateHidden = document.getElementById('private_course_id');

    const hostelCheckbox = document.querySelector(
        'input[name="apply_hostel"]'
    );

    const summary = document.getElementById(
        'programme-summary-text'
    );


    function updateSelection() {

        let regularSelected =
            regularSelect &&
            regularSelect.value !== '';

        let eveningSelected = null;

        eveningRadios.forEach(function (radio) {

            if (radio.checked && radio.value !== '') {
                eveningSelected = radio.value;
            }

        });


        /*
         * Reset hidden fields.
         */
        ditHidden.value = '';
        privateHidden.value = '';


        /*
         * Set selected Evening course.
         */
        if (eveningSelected) {

            const parts =
                eveningSelected.split(':');

            const type = parts[0];
            const id = parts[1];

            if (type === 'dit') {
                ditHidden.value = id;
            }

            if (type === 'private') {
                privateHidden.value = id;
            }
        }


        /*
         * Count programme vouchers.
         */
        let programmeCount = 0;

        if (regularSelected) {
            programmeCount++;
        }

        if (eveningSelected) {
            programmeCount++;
        }


        /*
         * Hostel adds one more voucher.
         */
        let voucherCount = programmeCount;

        if (
            hostelCheckbox &&
            hostelCheckbox.checked
        ) {
            voucherCount++;
        }


        /*
         * Display summary.
         */
        if (programmeCount === 0) {

            summary.textContent =
                'Please select at least one programme.';

            return;
        }


        let text =
            programmeCount +
            ' programme voucher' +
            (programmeCount > 1 ? 's' : '') +
            ' will be generated.';

        if (
            hostelCheckbox &&
            hostelCheckbox.checked
        ) {

            text +=
                ' Hostel selected: ' +
                voucherCount +
                ' total voucher' +
                (voucherCount > 1 ? 's' : '') +
                '.';
        }


        summary.textContent = text;
    }


    eveningRadios.forEach(function (radio) {

        radio.addEventListener(
            'change',
            updateSelection
        );

    });


    regularSelect.addEventListener(
        'change',
        updateSelection
    );


    if (hostelCheckbox) {

        hostelCheckbox.addEventListener(
            'change',
            updateSelection
        );

    }


    updateSelection();

});
</script>
@endpush