
@extends('layouts.admin')

@section('title', 'Create Admission Session')
@section('page-heading', 'Create Admission Session')

@section('content')

@php
    $selectedCourseIds = old('courses', []);

    if (!is_array($selectedCourseIds)) {
        $selectedCourseIds = [];
    }

    $selectedCourseIds = array_map(
        'strval',
        $selectedCourseIds
    );
@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Create Admission Session
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Configure the admission period and select the courses
                available to applicants.
            </p>
        </div>

        <a
            href="{{ route('admin.admission-sessions.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   border border-slate-200 bg-white px-4 py-2.5 text-sm
                   font-semibold text-slate-700 shadow-sm transition
                   hover:bg-slate-50"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Sessions
        </a>
    </div>


    {{-- SERVER-SIDE VALIDATION SUMMARY --}}
    @if ($errors->any())
        <div
            id="server-error-summary"
            tabindex="-1"
            role="alert"
            class="rounded-2xl border border-red-200 bg-red-50 p-5"
        >
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle"
                   class="mt-0.5 h-5 w-5 shrink-0 text-red-600"></i>

                <div class="min-w-0">
                    <h2 class="font-bold text-red-900">
                        Please correct the following errors
                    </h2>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- CLIENT-SIDE VALIDATION SUMMARY --}}
    <div
        id="client-error-summary"
        tabindex="-1"
        role="alert"
        aria-live="assertive"
        class="hidden rounded-2xl border border-red-200 bg-red-50 p-5"
    >
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle"
               class="mt-0.5 h-5 w-5 shrink-0 text-red-600"></i>

            <div>
                <h2 class="font-bold text-red-900">
                    The form needs your attention
                </h2>

                <p id="client-error-summary-text"
                   class="mt-1 text-sm text-red-800">
                    Please correct the highlighted fields.
                </p>
            </div>
        </div>
    </div>


    {{-- CREATE SESSION FORM --}}
    <form
        id="session-create-form"
        method="POST"
        action="{{ route('admin.admission-sessions.store') }}"
        novalidate
    >
        @csrf

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- SESSION INFORMATION --}}
            <section class="space-y-6 lg:col-span-2">

                <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                    <div class="border-b border-slate-100 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50">
                                <i data-lucide="calendar-days"
                                   class="h-5 w-5 text-emerald-700"></i>
                            </div>

                            <div>
                                <h2 class="font-bold text-slate-900">
                                    Session Information
                                </h2>

                                <p class="mt-0.5 text-xs text-slate-500">
                                    Fields marked with * are required.
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="space-y-6 p-6">

                        {{-- SESSION TITLE --}}
                        <div>
                            <label
                                for="title"
                                class="mb-1.5 block text-sm font-semibold text-slate-700"
                            >
                                Session Title <span class="text-red-600">*</span>
                            </label>

                            <input
                                id="title"
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="e.g. Fall Admissions 2026"
                                required
                                minlength="3"
                                maxlength="120"
                                autocomplete="off"
                                aria-describedby="title-help title-error"
                                @class([
                                    'w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-2',
                                    'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('title'),
                                    'border-slate-300 focus:border-emerald-500 focus:ring-emerald-100' => !$errors->has('title'),
                                ])
                            >

                            <div class="mt-1.5 flex flex-wrap items-start justify-between gap-2">
                                <p id="title-help" class="text-xs text-slate-500">
                                    Use a descriptive title between 3 and 120 characters.
                                </p>

                                <p id="title-counter"
                                   class="shrink-0 text-xs text-slate-500">
                                    {{ mb_strlen(old('title', '')) }}/120
                                </p>
                            </div>

                            <p
                                id="title-error"
                                data-error-for="title"
                                class="mt-1 min-h-5 text-sm {{ $errors->has('title') ? 'text-red-600' : 'text-slate-500' }}"
                            >{{ $errors->first('title') }}</p>
                        </div>


                        {{-- OPENING / CLOSING DATE --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label
                                    for="opening_date"
                                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                                >
                                    Opening Date &amp; Time
                                    <span class="text-red-600">*</span>
                                </label>

                                <input
                                    id="opening_date"
                                    type="datetime-local"
                                    name="opening_date"
                                    value="{{ old('opening_date') }}"
                                    required
                                    aria-describedby="opening-help opening-error"
                                    @class([
                                        'w-full rounded-xl border bg-white px-3 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:ring-2',
                                        'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('opening_date'),
                                        'border-slate-300 focus:border-emerald-500 focus:ring-emerald-100' => !$errors->has('opening_date'),
                                    ])
                                >

                                <p id="opening-help" class="mt-1.5 text-xs text-slate-500">
                                    When applicants may start applying.
                                </p>

                                <p
                                    id="opening-error"
                                    data-error-for="opening_date"
                                    class="mt-1 min-h-5 text-sm {{ $errors->has('opening_date') ? 'text-red-600' : 'text-slate-500' }}"
                                >{{ $errors->first('opening_date') }}</p>
                            </div>


                            <div>
                                <label
                                    for="closing_date"
                                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                                >
                                    Closing Date &amp; Time
                                    <span class="text-red-600">*</span>
                                </label>

                                <input
                                    id="closing_date"
                                    type="datetime-local"
                                    name="closing_date"
                                    value="{{ old('closing_date') }}"
                                    required
                                    aria-describedby="closing-help closing-error"
                                    @class([
                                        'w-full rounded-xl border bg-white px-3 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:ring-2',
                                        'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('closing_date'),
                                        'border-slate-300 focus:border-emerald-500 focus:ring-emerald-100' => !$errors->has('closing_date'),
                                    ])
                                >

                                <p id="closing-help" class="mt-1.5 text-xs text-slate-500">
                                    Must be later than the opening date and time.
                                </p>

                                <p
                                    id="closing-error"
                                    data-error-for="closing_date"
                                    class="mt-1 min-h-5 text-sm {{ $errors->has('closing_date') ? 'text-red-600' : 'text-slate-500' }}"
                                >{{ $errors->first('closing_date') }}</p>
                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div>
                            <div class="mb-1.5 flex items-center justify-between gap-3">
                                <label
                                    for="description"
                                    class="block text-sm font-semibold text-slate-700"
                                >
                                    Description
                                    <span class="font-normal text-slate-400">(Optional)</span>
                                </label>
                            </div>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="2000"
                                placeholder="Describe the admission session, eligible programmes, or important instructions..."
                                aria-describedby="description-help description-error"
                                @class([
                                    'w-full resize-y rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-2',
                                    'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('description'),
                                    'border-slate-300 focus:border-emerald-500 focus:ring-emerald-100' => !$errors->has('description'),
                                ])
                            >{{ old('description') }}</textarea>

                            <div class="mt-1.5 flex items-start justify-between gap-3">
                                <p id="description-help" class="text-xs text-slate-500">
                                    Keep instructions clear and concise. Maximum 2,000 characters.
                                </p>

                                <p id="description-counter"
                                   class="shrink-0 text-xs text-slate-500">
                                    {{ mb_strlen(old('description', '')) }}/2000
                                </p>
                            </div>

                            <p
                                id="description-error"
                                data-error-for="description"
                                class="mt-1 min-h-5 text-sm {{ $errors->has('description') ? 'text-red-600' : 'text-slate-500' }}"
                            >{{ $errors->first('description') }}</p>
                        </div>


                        {{-- OPEN IMMEDIATELY --}}
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                            <label for="is_open" class="flex cursor-pointer items-start gap-3">

                                <input
                                    id="is_open"
                                    type="checkbox"
                                    name="is_open"
                                    value="1"
                                    @checked((bool) old('is_open'))
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                >

                                <span>
                                    <span class="block text-sm font-semibold text-emerald-950">
                                        Open this session immediately
                                    </span>

                                    <span class="mt-1 block text-xs leading-5 text-emerald-800">
                                        If enabled, the administration should close any other open
                                        admission session when this session is saved.
                                    </span>
                                </span>

                            </label>
                        </div>

                    </div>

                </div>

            </section>


            {{-- COURSE SELECTION --}}
            <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <div class="border-b border-slate-100 p-6">

                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-bold text-slate-900">
                                Available Courses
                                <span class="text-red-600">*</span>
                            </h2>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Select at least one active course for this session.
                            </p>
                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                            {{ $courses->count() }} available
                        </span>
                    </div>


                    @if ($courses->count())
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <p id="selected-course-count"
                               class="text-xs font-semibold text-emerald-700"
                               aria-live="polite">
                                {{ count($selectedCourseIds) }} selected
                            </p>

                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    id="select-all-courses"
                                    class="text-xs font-semibold text-emerald-700 hover:text-emerald-900"
                                >
                                    Select all
                                </button>

                                <button
                                    type="button"
                                    id="clear-courses"
                                    class="text-xs font-semibold text-slate-500 hover:text-slate-800"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>
                    @endif

                </div>


                <div
                    id="course-selection-group"
                    class="max-h-[520px] space-y-2 overflow-y-auto p-4"
                    aria-describedby="courses-error"
                >

                    @forelse ($courses as $course)

                        <label
                            @class([
                                'flex cursor-pointer items-start gap-3 rounded-xl border p-3 transition',
                                'border-emerald-300 bg-emerald-50/70' => in_array((string) $course->id, $selectedCourseIds, true),
                                'border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/40' => !in_array((string) $course->id, $selectedCourseIds, true),
                            ])
                        >

                            <input
                                type="checkbox"
                                name="courses[]"
                                value="{{ $course->id }}"
                                data-course-checkbox
                                @checked(in_array((string) $course->id, $selectedCourseIds, true))
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                            >

                            <span class="min-w-0 flex-1">

                                <span class="block break-words text-sm font-semibold text-slate-800">
                                    {{ $course->title }}
                                </span>

                                <span class="mt-1 flex flex-wrap items-center gap-1.5 text-xs text-slate-500">

                                    @switch($course->course_type)
                                        @case('dit')
                                            <span class="rounded-full bg-blue-100 px-2 py-0.5 font-semibold text-blue-800">
                                                DIT / Second Shift
                                            </span>
                                            @break

                                        @case('private')
                                            <span class="rounded-full bg-purple-100 px-2 py-0.5 font-semibold text-purple-800">
                                                Private / IMC
                                            </span>
                                            @break

                                        @default
                                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 font-semibold text-emerald-800">
                                                Regular
                                            </span>
                                    @endswitch

                                    <span>
                                        Rs. {{ number_format((float) $course->fee_amount, 2) }}
                                    </span>

                                </span>
                            </span>

                        </label>

                    @empty

                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center">
                            <i data-lucide="book-open"
                               class="mx-auto h-8 w-8 text-slate-400"></i>

                            <p class="mt-2 text-sm font-semibold text-slate-700">
                                No active courses available
                            </p>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Activate or create courses before creating this session.
                            </p>
                        </div>

                    @endforelse

                </div>


                <div class="border-t border-slate-100 px-5 pb-5">

                    <p
                        id="courses-error"
                        data-error-for="courses"
                        class="mt-3 min-h-5 text-sm {{ $errors->has('courses') ? 'text-red-600' : 'text-slate-500' }}"
                    >
                        {{ $errors->first('courses') }}
                    </p>

                    @if ($errors->has('courses.*'))
                        <p class="mt-1 text-sm text-red-600">
                            One or more selected courses are invalid.
                        </p>
                    @endif

                    <p class="mt-2 text-xs leading-5 text-slate-500">
                        Only selected courses will be associated with this admission session.
                    </p>

                </div>

            </section>

        </div>


        {{-- FORM ACTIONS --}}
        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs leading-5 text-slate-500">
                Check the dates and selected courses before creating the session.
            </p>

            <div class="flex flex-col-reverse gap-3 sm:flex-row">

                <a
                    href="{{ route('admin.admission-sessions.index') }}"
                    class="inline-flex items-center justify-center rounded-xl
                           border border-slate-200 bg-white px-5 py-3 text-sm
                           font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    id="submit-session-button"
                    type="submit"
                    @disabled($courses->isEmpty())
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-emerald-600 px-5 py-3 text-sm font-semibold
                           text-white shadow-sm transition hover:bg-emerald-700
                           focus:outline-none focus:ring-2 focus:ring-emerald-500
                           focus:ring-offset-2 disabled:cursor-not-allowed
                           disabled:opacity-50"
                >
                    <i data-lucide="save" class="h-4 w-4" data-submit-icon></i>

                    <span data-submit-label>Create Session</span>

                    <svg
                        data-submit-spinner
                        class="hidden h-4 w-4 animate-spin"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <circle class="opacity-25"
                                cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>

                        <path class="opacity-75"
                              fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                </button>

            </div>

        </div>

    </form>

</div>


{{-- FRONT-END VALIDATION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('session-create-form');

    if (!form) return;

    const titleInput = document.getElementById('title');
    const openingInput = document.getElementById('opening_date');
    const closingInput = document.getElementById('closing_date');
    const descriptionInput = document.getElementById('description');

    const courseCheckboxes = Array.from(
        document.querySelectorAll('[data-course-checkbox]')
    );

    const selectedCourseCount = document.getElementById('selected-course-count');
    const courseError = document.getElementById('courses-error');

    const clientSummary = document.getElementById('client-error-summary');
    const clientSummaryText = document.getElementById('client-error-summary-text');

    const submitButton = document.getElementById('submit-session-button');
    const submitLabel = submitButton?.querySelector('[data-submit-label]');
    const submitSpinner = submitButton?.querySelector('[data-submit-spinner]');
    const submitIcon = submitButton?.querySelector('[data-submit-icon]');

    let submitted = false;


    /*
     * --------------------------------------------------------------
     * Helpers
     * --------------------------------------------------------------
     */
    function errorElement(name) {
        return document.querySelector(
            '[data-error-for="' + name + '"]'
        );
    }


    function setFieldError(name, message) {
        const error = errorElement(name);

        if (error) {
            error.textContent = message || '';
            error.classList.toggle('text-red-600', Boolean(message));
            error.classList.toggle('text-slate-500', !message);
        }

        const input = form.elements.namedItem(name);

        if (input && input.classList) {
            input.setAttribute(
                'aria-invalid',
                message ? 'true' : 'false'
            );

            input.classList.toggle('border-red-400', Boolean(message));
            input.classList.toggle('focus:border-red-500', Boolean(message));
            input.classList.toggle('focus:ring-red-100', Boolean(message));

            input.classList.toggle('border-slate-300', !message);
            input.classList.toggle('focus:border-emerald-500', !message);
            input.classList.toggle('focus:ring-emerald-100', !message);
        }

        return !message;
    }


    function setCoursesError(message) {
        if (courseError) {
            courseError.textContent = message || '';
            courseError.classList.toggle('text-red-600', Boolean(message));
            courseError.classList.toggle('text-slate-500', !message);
        }

        const group = document.getElementById('course-selection-group');

        if (group) {
            group.setAttribute(
                'aria-invalid',
                message ? 'true' : 'false'
            );
        }

        return !message;
    }


    function updateCounter(input, counter, maximum) {
        if (!input || !counter) return;

        const length = input.value.length;

        counter.textContent = length + '/' + maximum;

        counter.classList.toggle(
            'text-red-600',
            length > maximum
        );

        counter.classList.toggle(
            'text-slate-500',
            length <= maximum
        );
    }


    function selectedCourses() {
        return courseCheckboxes.filter(
            checkbox => checkbox.checked
        );
    }


    function updateSelectedCount() {
        const count = selectedCourses().length;

        if (selectedCourseCount) {
            selectedCourseCount.textContent =
                count + (count === 1 ? ' selected' : ' selected');
        }

        courseCheckboxes.forEach(function (checkbox) {
            const label = checkbox.closest('label');

            if (!label) return;

            label.classList.toggle(
                'border-emerald-300',
                checkbox.checked
            );

            label.classList.toggle(
                'bg-emerald-50/70',
                checkbox.checked
            );

            label.classList.toggle(
                'border-slate-200',
                !checkbox.checked
            );

            label.classList.toggle(
                'hover:border-emerald-300',
                !checkbox.checked
            );
        });
    }


    /*
     * --------------------------------------------------------------
     * Individual field validation
     * --------------------------------------------------------------
     */
    function validateTitle() {
        const value = titleInput.value.trim();

        if (!value) {
            return setFieldError(
                'title',
                'Please enter a session title.'
            );
        }

        if (value.length < 3) {
            return setFieldError(
                'title',
                'The session title must contain at least 3 characters.'
            );
        }

        if (value.length > 120) {
            return setFieldError(
                'title',
                'The session title cannot exceed 120 characters.'
            );
        }

        return setFieldError('title', '');
    }


    function validateOpeningDate() {
        if (!openingInput.value) {
            return setFieldError(
                'opening_date',
                'Please select the opening date and time.'
            );
        }

        if (Number.isNaN(Date.parse(openingInput.value))) {
            return setFieldError(
                'opening_date',
                'Please enter a valid opening date and time.'
            );
        }

        return setFieldError('opening_date', '');
    }


    function validateClosingDate() {
        if (!closingInput.value) {
            return setFieldError(
                'closing_date',
                'Please select the closing date and time.'
            );
        }

        if (Number.isNaN(Date.parse(closingInput.value))) {
            return setFieldError(
                'closing_date',
                'Please enter a valid closing date and time.'
            );
        }

        if (
            openingInput.value &&
            Date.parse(closingInput.value) <= Date.parse(openingInput.value)
        ) {
            return setFieldError(
                'closing_date',
                'The closing date and time must be later than the opening date and time.'
            );
        }

        return setFieldError('closing_date', '');
    }


    function validateDescription() {
        if (descriptionInput.value.length > 2000) {
            return setFieldError(
                'description',
                'The description cannot exceed 2,000 characters.'
            );
        }

        return setFieldError('description', '');
    }


    function validateCourses() {
        const count = selectedCourses().length;

        if (count === 0) {
            return setCoursesError(
                'Select at least one active course for this session.'
            );
        }

        return setCoursesError('');
    }


    /*
     * --------------------------------------------------------------
     * Validate entire form
     * --------------------------------------------------------------
     */
    function validateForm() {
        const results = [
            validateTitle(),
            validateOpeningDate(),
            validateClosingDate(),
            validateDescription(),
            validateCourses()
        ];

        return results.every(Boolean);
    }


    function showClientSummary() {
        const hasInvalidFields = !validateForm();

        if (!hasInvalidFields) {
            clientSummary.classList.add('hidden');
            return false;
        }

        clientSummaryText.textContent =
            'Please correct the highlighted fields and select at least one course before continuing.';

        clientSummary.classList.remove('hidden');

        return true;
    }


    /*
     * --------------------------------------------------------------
     * Live validation
     * --------------------------------------------------------------
     */
    titleInput.addEventListener('input', function () {
        updateCounter(
            titleInput,
            document.getElementById('title-counter'),
            120
        );

        validateTitle();
    });


    titleInput.addEventListener('blur', validateTitle);


    openingInput.addEventListener('change', function () {
        /*
         * Set the browser's minimum allowable closing date.
         */
        closingInput.min = openingInput.value;

        validateOpeningDate();

        if (closingInput.value) {
            validateClosingDate();
        }
    });


    openingInput.addEventListener('blur', function () {
        validateOpeningDate();

        if (closingInput.value) {
            validateClosingDate();
        }
    });


    closingInput.addEventListener('change', function () {
        validateClosingDate();
    });


    closingInput.addEventListener('blur', function () {
        validateClosingDate();
    });


    descriptionInput.addEventListener('input', function () {
        updateCounter(
            descriptionInput,
            document.getElementById('description-counter'),
            2000
        );

        validateDescription();
    });


    courseCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateSelectedCount();
            validateCourses();
        });
    });


    /*
     * --------------------------------------------------------------
     * Select all / Clear courses
     * --------------------------------------------------------------
     */
    document.getElementById('select-all-courses')?.addEventListener(
        'click',
        function () {
            courseCheckboxes.forEach(
                checkbox => checkbox.checked = true
            );

            updateSelectedCount();
            validateCourses();
        }
    );


    document.getElementById('clear-courses')?.addEventListener(
        'click',
        function () {
            courseCheckboxes.forEach(
                checkbox => checkbox.checked = false
            );

            updateSelectedCount();
            validateCourses();
        }
    );


    /*
     * --------------------------------------------------------------
     * Initial display
     * --------------------------------------------------------------
     */
    if (openingInput.value) {
        closingInput.min = openingInput.value;
    }

    updateCounter(
        titleInput,
        document.getElementById('title-counter'),
        120
    );

    updateCounter(
        descriptionInput,
        document.getElementById('description-counter'),
        2000
    );

    updateSelectedCount();


    /*
     * --------------------------------------------------------------
     * Submission
     * --------------------------------------------------------------
     */
    form.addEventListener('submit', function (event) {
        if (submitted) {
            event.preventDefault();
            return;
        }

        /*
         * Trim whitespace-only title and normalize leading/trailing spaces.
         */
        titleInput.value = titleInput.value.trim();

        const valid = validateForm();

        if (!valid) {
            event.preventDefault();

            showClientSummary();
            clientSummary.focus();

            const firstInvalid = form.querySelector(
                '[aria-invalid="true"]'
            );

            if (firstInvalid) {
                firstInvalid.focus({ preventScroll: true });

                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            } else {
                document.getElementById(
                    'course-selection-group'
                )?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return;
        }

        submitted = true;

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75');

            if (submitLabel) {
                submitLabel.textContent = 'Creating session...';
            }

            if (submitIcon) {
                submitIcon.classList.add('hidden');
            }

            if (submitSpinner) {
                submitSpinner.classList.remove('hidden');
            }
        }
    });
});
</script>

@endsection
