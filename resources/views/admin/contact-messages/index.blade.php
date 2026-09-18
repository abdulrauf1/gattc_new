@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-heading', 'Contact Messages')

@section('content')
<div class="space-y-4">

    <div>
        <div class="flex items-center gap-2">

            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100">
                <i data-lucide="messages-square" class="h-5 w-5 text-slate-700"></i>
            </div>

            <div>
                <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                    Contact Messages
                </h1>

                <p class="text-xs text-slate-500">
                    Messages received from the public website.
                </p>
            </div>

        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Tabs --}}
        <div class="overflow-x-auto border-b border-slate-200">

            <div class="flex min-w-max px-2">

                <a href="{{ request()->fullUrlWithQuery([
                    'read' => null,
                    'page' => null
                ]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ !request()->filled('read')
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    All

                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $messageTotal }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery([
                    'read' => 'unread',
                    'page' => null
                ]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('read') === 'unread'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Unread

                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $messageUnread }})
                    </span>
                </a>

                <a href="{{ request()->fullUrlWithQuery([
                    'read' => 'read',
                    'page' => null
                ]) }}"
                   class="relative px-5 py-3 text-sm font-medium
                   {{ request('read') === 'read'
                        ? 'text-emerald-600 after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-emerald-500'
                        : 'text-slate-600 hover:text-slate-900' }}">
                    Read

                    <span class="ml-1 text-xs text-slate-500">
                        ({{ $messageRead }})
                    </span>
                </a>

            </div>
        </div>

        {{-- Filter --}}        
        <div class="p-3 border-b border-gray-200">

            <form method="GET" action="{{ route('admin.contact-messages.index') }}">

                <div class="flex flex-col lg:flex-row gap-2">

                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">

                            <i data-lucide="search"
                               class="absolute left-3 top-1/2
                                      -translate-y-1/2
                                      w-4 h-4 text-gray-400">
                            </i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search name, email, subject or message..."
                                class="w-full h-9 rounded-lg border border-gray-300
                                       pl-9 pr-3 text-sm
                                       focus:border-blue-500
                                       focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>


                    {{-- Status --}}
                    <div class="w-full lg:w-36">

                        <select
                            name="status"
                            class="w-full h-9 rounded-lg border border-gray-300
                                   px-3 text-sm">

                            <option value="">All Messages</option>
                            <option value="unread" @selected(request('read') === 'unread')>
                                Unread
                            </option>
                            <option value="read" @selected(request('read') === 'read')>
                                Read
                            </option>

                        </select>

                    </div>



                    {{-- Filter --}}
                    <button
                        type="submit"
                        class="h-9 px-3 rounded-lg
                               bg-emerald-500 hover:bg-emerald-600
                               text-white text-sm font-medium
                               inline-flex items-center justify-center gap-1.5">

                        <i data-lucide="filter"
                           class="w-4 h-4"></i>

                        Filter

                    </button>


                    {{-- Reset --}}
                    <a
                        href="{{ route('admin.contact-messages.index') }}"
                        title="Reset filters"
                        class="h-9 w-9 rounded-lg
                               bg-gray-100 hover:bg-gray-200
                               flex items-center justify-center">

                        <i data-lucide="rotate-ccw"
                           class="w-4 h-4 text-gray-600"></i>

                    </a>

                </div>

            </form>

        </div>

        {{-- Desktop --}}
        <div class="hidden md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[950px] text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-xs font-semibold text-slate-700">

                            <th class="px-5 py-3">
                                Sender
                            </th>

                            <th class="px-4 py-3">
                                Subject
                            </th>

                            <th class="px-4 py-3">
                                Date
                            </th>

                            <th class="px-4 py-3">
                                Status
                            </th>

                            <th class="px-5 py-3 text-right">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($messages as $message)

                            <tr class="{{ !$message->read_at ? 'bg-emerald-50/30' : '' }} hover:bg-slate-50">

                                <td class="px-5 py-3.5">

                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $message->name }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $message->email }}
                                        </p>

                                        @if($message->phone)
                                            <p class="mt-0.5 text-[11px] text-slate-400">
                                                {{ $message->phone }}
                                            </p>
                                        @endif
                                    </div>

                                </td>

                                <td class="px-4 py-3.5">

                                    <p class="max-w-[350px] truncate text-sm text-slate-700">
                                        {{ $message->subject ?: 'No subject' }}
                                    </p>

                                    <p class="mt-1 max-w-[350px] truncate text-xs text-slate-400">
                                        {{ $message->message }}
                                    </p>

                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-slate-500">
                                    {{ $message->created_at?->format('d M Y, h:i A') }}
                                </td>

                                <td class="px-4 py-3.5">

                                    @if(!$message->read_at)

                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            Unread
                                        </span>

                                    @else

                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            Read
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-3.5">

                                    <div class="flex justify-end gap-1.5">

                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
                                           title="View">

                                            <i data-lucide="eye" class="h-4 w-4"></i>

                                        </a>

                                        @if($message->read_at)

                                            <form method="POST"
                                                  action="{{ route('admin.contact-messages.unread', $message) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100"
                                                        title="Mark unread">

                                                    <i data-lucide="mail" class="h-4 w-4"></i>

                                                </button>

                                            </form>

                                        @else

                                            <form method="POST"
                                                  action="{{ route('admin.contact-messages.read', $message) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100"
                                                        title="Mark read">

                                                    <i data-lucide="mail-open" class="h-4 w-4"></i>

                                                </button>

                                            </form>

                                        @endif

                                        <form method="POST"
                                              action="{{ route('admin.contact-messages.destroy', $message) }}"
                                              onsubmit="return confirm('Delete this message?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
                                                    title="Delete">

                                                <i data-lucide="trash-2" class="h-4 w-4"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-5 py-16 text-center">

                                    <i data-lucide="inbox" class="mx-auto h-9 w-9 text-slate-300"></i>

                                    <p class="mt-3 text-sm font-medium text-slate-700">
                                        No contact messages found
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-slate-200 md:hidden">

            @forelse($messages as $message)

                <div class="p-4 {{ !$message->read_at ? 'bg-emerald-50/30' : '' }}">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ $message->name }}
                            </p>

                            <p class="truncate text-xs text-slate-500">
                                {{ $message->email }}
                            </p>

                            <p class="mt-2 font-medium text-sm text-slate-700">
                                {{ $message->subject ?: 'No subject' }}
                            </p>

                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                {{ $message->message }}
                            </p>

                            <p class="mt-2 text-[11px] text-slate-400">
                                {{ $message->created_at?->format('d M Y, h:i A') }}
                            </p>

                        </div>

                        @if(!$message->read_at)
                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700">
                                Unread
                            </span>
                        @else
                            <span class="shrink-0 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-600">
                                Read
                            </span>
                        @endif

                    </div>

                    <div class="mt-3 flex gap-2 border-t border-slate-100 pt-3">

                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                           class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-xs font-medium text-slate-700">
                            View
                        </a>

                        <form method="POST"
                              action="{{ route(
                                  'admin.contact-messages.' . ($message->read_at ? 'unread' : 'read'),
                                  $message
                              ) }}"
                              class="flex-1">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="w-full rounded-lg bg-emerald-50 py-2 text-xs font-medium text-emerald-700">
                                {{ $message->read_at ? 'Mark Unread' : 'Mark Read' }}
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="px-5 py-14 text-center">
                    <p class="text-sm text-slate-600">
                        No contact messages found.
                    </p>
                </div>

            @endforelse

        </div>

        @if($messages->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $messages->links() }}
            </div>
        @endif

    </div>

</div>
@endsection