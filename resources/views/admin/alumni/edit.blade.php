@extends('layouts.admin')

@section('title', 'Edit Alumni')
@section('page-heading', 'Edit Alumni')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Edit Alumni
            </h1>
            <p class="text-xs text-slate-500">
                {{ $alumni->name }}
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.alumni.show', $alumni) }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                <i data-lucide="eye" class="h-4 w-4"></i>
                View
            </a>

            <a href="{{ route('admin.alumni.index') }}"
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
          action="{{ route('admin.alumni.update', $alumni) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Alumni Information
                    </h2>
                </div>

                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Name *
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $alumni->name) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Email *
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $alumni->email) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Phone *
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $alumni->phone) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Course *
                        </label>

                        <input type="text"
                               name="course"
                               value="{{ old('course', $alumni->course) }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Graduation Year *
                        </label>

                        <input type="number"
                               name="graduation_year"
                               value="{{ old('graduation_year', $alumni->graduation_year) }}"
                               min="1900"
                               max="2100"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Organization
                        </label>

                        <input type="text"
                               name="organization"
                               value="{{ old('organization', $alumni->organization) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Designation
                        </label>

                        <input type="text"
                               name="designation"
                               value="{{ old('designation', $alumni->designation) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Bio
                        </label>

                        <textarea name="bio"
                                  rows="8"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-7">{{ old('bio', $alumni->bio) }}</textarea>

                    </div>

                </div>

            </div>

            <div class="space-y-4">

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Profile Photo
                        </h2>
                    </div>

                    <div class="p-5">

                        <div class="mx-auto h-40 w-40 overflow-hidden rounded-xl bg-slate-100">

                            @if($alumni->photo)

                                <img src="{{ asset('storage/' . $alumni->photo) }}"
                                     alt="{{ $alumni->name }}"
                                     class="h-full w-full object-cover">

                            @else

                                <div class="flex h-full w-full items-center justify-center">
                                    <i data-lucide="user-round" class="h-10 w-10 text-slate-300"></i>
                                </div>

                            @endif

                        </div>

                        <label class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center">

                            <input type="file"
                                   name="photo"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="hidden"
                                   onchange="document.getElementById('new-photo').textContent = this.files[0]?.name || 'No new photo selected';">

                            <i data-lucide="upload" class="h-6 w-6 text-slate-400"></i>

                            <p class="mt-2 text-sm font-medium text-slate-700">
                                Replace Photo
                            </p>

                            <p id="new-photo"
                               class="mt-1 break-all text-xs text-slate-400">
                                No new photo selected
                            </p>

                        </label>

                    </div>

                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Approval Status
                        </h2>
                    </div>

                    <div class="p-5">

                        @if($alumni->status)

                            <div class="rounded-lg bg-emerald-50 p-3 text-xs text-emerald-700">
                                This alumni profile is approved.
                            </div>

                        @else

                            <div class="rounded-lg bg-amber-50 p-3 text-xs text-amber-700">
                                This alumni profile is not approved.
                            </div>

                        @endif

                        <p class="mt-2 text-[11px] leading-5 text-slate-400">
                            Approval is managed from the Alumni listing.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">

            <a href="{{ route('admin.alumni.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm text-slate-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white">
                <i data-lucide="save" class="h-4 w-4"></i>
                Update Alumni
            </button>

        </div>

    </form>

</div>
@endsection