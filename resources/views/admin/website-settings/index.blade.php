@extends('layouts.admin')

@section('title', 'Website Settings')
@section('page-heading', 'Website Settings')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div>
        <div class="flex items-center gap-2">

            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100">
                <i data-lucide="settings-2" class="h-5 w-5 text-slate-700"></i>
            </div>

            <div>
                <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                    Website Settings
                </h1>

                <p class="text-xs text-slate-500">
                    Manage basic public website information.
                </p>
            </div>

        </div>
    </div>

    @if(session('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif

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
          action="{{ route('admin.website-settings.update') }}">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Institution Information
                    </h2>
                </div>

                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">

                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Site Name
                        </label>

                        <input type="text"
                               name="site_name"
                               value="{{ old('site_name', $settings['site_name']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                    <div>

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Short Name
                        </label>

                        <input type="text"
                               name="short_name"
                               value="{{ old('short_name', $settings['short_name']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                    <div>

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $settings['phone']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                    <div class="sm:col-span-2">

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">{{ old('address', $settings['address']) }}</textarea>

                    </div>

                </div>

            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Online Information
                    </h2>
                </div>

                <div class="space-y-4 p-5">

                    <div>

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $settings['email']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                    <div>

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Website
                        </label>

                        <input type="text"
                               name="website"
                               value="{{ old('website', $settings['website']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                    <div>

                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Facebook
                        </label>

                        <input type="text"
                               name="facebook"
                               value="{{ old('facebook', $settings['facebook']) }}"
                               class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm">

                    </div>

                </div>

            </div>

        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-900">
                    Footer
                </h2>

            </div>

            <div class="p-5">

                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                    Footer Text
                </label>

                <textarea name="footer_text"
                          rows="4"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">{{ old('footer_text', $settings['footer_text']) }}</textarea>

            </div>

        </div>

        <div class="mt-4 flex justify-end">

            <button type="submit"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-emerald-500 px-6 text-sm font-medium text-white hover:bg-emerald-600">

                <i data-lucide="save" class="h-4 w-4"></i>
                Save Settings

            </button>

        </div>

    </form>

</div>
@endsection