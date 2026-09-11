@extends('layouts.admin')

@section('title', 'Admission Sessions')

@section('page-title', 'Admission Sessions')

@section('content')

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Admission Sessions
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Control when online admissions are available.
        </p>
    </div>

    <a
        href="{{ route('admin.admission-sessions.create') }}"
        class="inline-flex items-center justify-center gap-2
               rounded-xl bg-emerald-600 px-5 py-3
               text-sm font-semibold text-white
               hover:bg-emerald-700"
    >
        <i data-lucide="plus"></i>
        New Session
    </a>

</div>


<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="px-6 py-4 text-left font-semibold">
                        Session
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Opening
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Closing
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right font-semibold">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($sessions as $session)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-slate-900">
                                {{ $session->name }}
                            </div>

                            <div class="text-xs text-slate-500">
                                {{ $session->session_code }}
                            </div>

                        </td>


                        <td class="px-6 py-4">
                            {{ $session->opening_date->format('d M Y h:i A') }}
                        </td>


                        <td class="px-6 py-4">
                            {{ $session->closing_date->format('d M Y h:i A') }}
                        </td>


                        <td class="px-6 py-4">

                            @if($session->is_open && $session->isCurrentlyOpen())

                                <span
                                    class="inline-flex rounded-full
                                           bg-emerald-100 px-3 py-1
                                           text-xs font-semibold
                                           text-emerald-700"
                                >
                                    OPEN
                                </span>

                            @elseif($session->is_open)

                                <span
                                    class="inline-flex rounded-full
                                           bg-amber-100 px-3 py-1
                                           text-xs font-semibold
                                           text-amber-700"
                                >
                                    SCHEDULED
                                </span>

                            @else

                                <span
                                    class="inline-flex rounded-full
                                           bg-slate-100 px-3 py-1
                                           text-xs font-semibold
                                           text-slate-600"
                                >
                                    CLOSED
                                </span>

                            @endif

                        </td>


                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route(
                                        'admin.admission-sessions.edit',
                                        $session
                                    ) }}"
                                    class="rounded-lg p-2 text-slate-500
                                           hover:bg-slate-100"
                                    title="Edit"
                                >
                                    <i data-lucide="pencil"></i>
                                </a>


                                @if(!$session->is_open)

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.admission-sessions.open',
                                            $session
                                        ) }}"
                                    >

                                        @csrf

                                        <button
                                            class="rounded-lg p-2 text-emerald-600
                                                   hover:bg-emerald-50"
                                            title="Open Admissions"
                                        >
                                            <i data-lucide="lock-open"></i>
                                        </button>

                                    </form>

                                @else

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.admission-sessions.close',
                                            $session
                                        ) }}"
                                    >

                                        @csrf

                                        <button
                                            class="rounded-lg p-2 text-red-600
                                                   hover:bg-red-50"
                                            title="Close Admissions"
                                        >
                                            <i data-lucide="lock"></i>
                                        </button>

                                    </form>

                                @endif


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.admission-sessions.destroy',
                                        $session
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Delete this admission session?'
                                    )"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="rounded-lg p-2 text-red-500
                                               hover:bg-red-50"
                                        title="Delete"
                                    >
                                        <i data-lucide="trash-2"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate-500"
                        >
                            No admission sessions found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="border-t border-slate-200 p-4">

        {{ $sessions->links() }}

    </div>

</div>

@endsection