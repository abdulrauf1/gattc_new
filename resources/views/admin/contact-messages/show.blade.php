@extends('layouts.admin')

@section('title', 'Contact Message')
@section('page-heading', 'Contact Message')

@section('content')
<div class="mx-auto max-w-6xl space-y-4">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Contact Message
            </h1>

            <p class="text-xs text-slate-500">
                Message received from {{ $contactMessage->name }}
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.contact-messages.index') }}"
               class="inline-flex h-10 items-center gap-2 rounded-lg bg-slate-100 px-4 text-sm font-medium text-slate-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>

        </div>

    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-4">

        {{-- Message --}}
        <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <div class="flex flex-wrap items-center justify-between gap-2">

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            {{ $contactMessage->subject ?: 'No Subject' }}
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $contactMessage->created_at?->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    @if($contactMessage->read_at)
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                            Read
                        </span>
                    @else
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                            Unread
                        </span>
                    @endif

                </div>

            </div>

            <div class="p-5">

                <div class="whitespace-pre-line text-sm leading-7 text-slate-700">
                    {{ $contactMessage->message }}
                </div>

            </div>

        </div>

        {{-- Sender Information --}}
        <div class="space-y-4">

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Sender Information
                    </h2>
                </div>

                <div class="divide-y divide-slate-100">

                    <div class="px-5 py-3">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Name
                        </p>
                        <p class="mt-1 text-sm font-medium text-slate-800">
                            {{ $contactMessage->name }}
                        </p>
                    </div>

                    <div class="px-5 py-3">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">
                            Email
                        </p>

                        <a href="mailto:{{ $contactMessage->email }}"
                           class="mt-1 block break-all text-sm text-emerald-600 hover:underline">
                            {{ $contactMessage->email }}
                        </a>
                    </div>

                    @if($contactMessage->phone)

                        <div class="px-5 py-3">

                            <p class="text-[11px] uppercase tracking-wide text-slate-400">
                                Phone
                            </p>

                            <a href="tel:{{ $contactMessage->phone }}"
                               class="mt-1 block text-sm text-emerald-600 hover:underline">
                                {{ $contactMessage->phone }}
                            </a>

                        </div>

                    @endif

                </div>

            </div>

            {{-- Read Status --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

                @if($contactMessage->read_at)

                    <form method="POST"
                          action="{{ route('admin.contact-messages.unread', $contactMessage) }}">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="w-full rounded-lg bg-amber-50 py-2.5 text-xs font-medium text-amber-700 hover:bg-amber-100">
                            Mark as Unread
                        </button>

                    </form>

                @else

                    <form method="POST"
                          action="{{ route('admin.contact-messages.read', $contactMessage) }}">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="w-full rounded-lg bg-emerald-50 py-2.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100">
                            Mark as Read
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

    {{-- Admin Notes --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Administrator Notes
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Internal notes are not displayed on the public website.
            </p>

        </div>

        <form method="POST"
              action="{{ route('admin.contact-messages.notes', $contactMessage) }}"
              class="p-5">

            @csrf
            @method('PATCH')

            <textarea name="admin_notes"
                      rows="5"
                      placeholder="Add internal notes..."
                      class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm leading-6 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>

            <div class="mt-3 flex justify-end">

                <button type="submit"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-medium text-white hover:bg-emerald-600">

                    <i data-lucide="save" class="h-4 w-4"></i>
                    Save Notes

                </button>

            </div>

        </form>

    </div>

</div>
@endsection