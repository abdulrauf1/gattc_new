@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-heading', 'Edit Event')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">Edit Event</h1>
            <p class="text-xs text-slate-500">{{ $event->title }}</p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.events.show', $event) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                <i data-lucide="eye" class="h-4 w-4"></i>
                View
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <ul class="list-disc pl-5 text-xs text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.events.update', $event) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Event Information
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <input type="text"
                           name="title"
                           value="{{ old('title', $event->title) }}"
                           required
                           class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    <textarea name="short_description"
                              rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">{{ old('short_description', $event->short_description) }}</textarea>

                    <textarea name="description"
                              rows="10"
                              required
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-7">{{ old('description', $event->description) }}</textarea>

                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Event Details
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Event Date *
                        </label>

                        <input type="date"
                               name="event_date"
                               value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Location
                        </label>

                        <input type="text"
                               name="location"
                               value="{{ old('location', $event->location) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Status
                        </label>

                        <select name="status"
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm">
                            <option value="1" @selected(old('status', $event->status) == '1')>Published</option>
                            <option value="0" @selected(old('status', $event->status) == '0')>Hidden</option>
                        </select>
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">

            <a href="{{ route('admin.events.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white">
                <i data-lucide="save" class="h-4 w-4"></i>
                Update Event
            </button>

        </div>

    </form>

</div>
@endsection