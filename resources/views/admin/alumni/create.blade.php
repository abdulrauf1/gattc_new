@extends('layouts.admin')

@section('title', 'Add Alumni')
@section('page-heading', 'Add Alumni')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Add Alumni
            </h1>
            <p class="text-xs text-slate-500">
                Create an alumni profile.
            </p>
        </div>

        <a href="{{ route('admin.alumni.index') }}"
           class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back
        </a>

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
          action="{{ route('admin.alumni.store') }}"
          enctype="multipart/form-data">

        @csrf

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
                               value="{{ old('name') }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Email *
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Phone *
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Course *
                        </label>

                        <input type="text"
                               name="course"
                               value="{{ old('course') }}"
                               required
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Graduation Year *
                        </label>

                        <input type="number"
                               name="graduation_year"
                               value="{{ old('graduation_year') }}"
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
                               value="{{ old('organization') }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Designation
                        </label>

                        <input type="text"
                               name="designation"
                               value="{{ old('designation') }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">
                    </div>

                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Bio
                        </label>

                        <textarea name="bio"
                                  rows="8"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-7">{{ old('bio') }}</textarea>

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

                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center">

                            <input type="file"
                                   name="photo"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="hidden"
                                   onchange="document.getElementById('photo-name').textContent = this.files[0]?.name || 'No photo selected';">

                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white">
                                <i data-lucide="user-round" class="h-7 w-7 text-slate-400"></i>
                            </div>

                            <p class="mt-3 text-sm font-medium text-slate-700">
                                Select Photo
                            </p>

                            <p id="photo-name"
                               class="mt-1 text-xs text-slate-400">
                                JPG, PNG or WEBP
                            </p>

                        </label>

                    </div>
                </div>

                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-xs leading-5 text-emerald-700">
                    Alumni records created by the administrator are saved as approved records according to the current controller workflow.
                </div>

            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">

            <a href="{{ route('admin.alumni.index') }}"
               class="inline-flex h-10 items-center rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white">
                <i data-lucide="save" class="h-4 w-4"></i>
                Save Alumni
            </button>

        </div>

    </form>

</div>
@endsection