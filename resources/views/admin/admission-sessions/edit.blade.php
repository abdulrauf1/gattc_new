@extends('layouts.admin')

@section('title', 'Edit Admission Session')

@section('page-title', 'Edit Admission Session')

@section('content')

<div class="mx-auto max-w-3xl">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Edit Admission Session
        </h1>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.admission-sessions.update',
            $admissionSession
        ) }}"
        class="space-y-6 rounded-2xl border border-slate-200
               bg-white p-6 shadow-sm"
    >

        @csrf
        @method('PUT')


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Session Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old(
                    'name',
                    $admissionSession->name
                ) }}"
                required
                class="w-full rounded-xl border-slate-300"
            >

        </div>


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Session Code
            </label>

            <input
                type="text"
                name="session_code"
                value="{{ old(
                    'session_code',
                    $admissionSession->session_code
                ) }}"
                required
                class="w-full rounded-xl border-slate-300"
            >

        </div>


        <div class="grid gap-5 md:grid-cols-2">

            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Opening Date & Time
                </label>

                <input
                    type="datetime-local"
                    name="opening_date"
                    value="{{ old(
                        'opening_date',
                        $admissionSession->opening_date
                            ->format('Y-m-d\TH:i')
                    ) }}"
                    required
                    class="w-full rounded-xl border-slate-300"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Closing Date & Time
                </label>

                <input
                    type="datetime-local"
                    name="closing_date"
                    value="{{ old(
                        'closing_date',
                        $admissionSession->closing_date
                            ->format('Y-m-d\TH:i')
                    ) }}"
                    required
                    class="w-full rounded-xl border-slate-300"
                >

            </div>

        </div>


        <div>

            <label class="mb-2 block text-sm font-semibold">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full rounded-xl border-slate-300"
            >{{ old(
                'description',
                $admissionSession->description
            ) }}</textarea>

        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.admission-sessions.index') }}"
                class="rounded-xl border border-slate-300 px-5 py-3"
            >
                Cancel
            </a>

            <button
                class="rounded-xl bg-emerald-600
                       px-5 py-3 font-semibold text-white"
            >
                Update Session
            </button>

        </div>

    </form>

</div>

@endsection