@extends('layouts.admin')

@section('title', 'Create Announcement')
@section('page-heading', 'Create Announcement')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Create Announcement
            </h1>
            <p class="text-xs text-slate-500">
                Create a new public announcement.
            </p>
        </div>

        <a href="{{ route('admin.announcements.index') }}"
           class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <ul class="list-disc space-y-1 pl-5 text-xs text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.announcements.store') }}"
          class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Announcement Content
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Title *
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-slate-400 focus:ring-2 focus:ring-slate-100">

                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Content *
                        </label>

                        <textarea name="content"
                                  rows="14"
                                  required
                                  placeholder="Write the announcement..."
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-7 focus:border-slate-400 focus:ring-2 focus:ring-slate-100">{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Publishing
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Status
                        </label>

                        <select name="status"
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm">
                            <option value="1" @selected(old('status', '1') == '1')>
                                Published
                            </option>
                            <option value="0" @selected(old('status') === '0')>
                                Hidden
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Published At
                        </label>

                        <input type="datetime-local"
                               name="published_at"
                               value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                </div>
            </div>

        </div>

        <div class="flex justify-end gap-2">

            <a href="{{ route('admin.announcements.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white hover:bg-emerald-600">
                <i data-lucide="save" class="h-4 w-4"></i>
                Save Announcement
            </button>

        </div>

    </form>
</div>
@endsection