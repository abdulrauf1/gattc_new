@extends('layouts.admin')

@section('page-heading', 'Alumni')

@section('content')

<div class="space-y-4">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold">
                Alumni
            </h1>

            <p class="text-xs text-gray-500 mt-1">
                Add, edit, approve and manage GATTC alumni profiles.
            </p>
        </div>

        <a
            href="{{ route('admin.alumni.create') }}"
            class="btn-primary">

            <i data-lucide="plus"
               class="w-4 h-4"></i>

            Add Alumni

        </a>

    </div>


    <div class="bg-white border rounded-xl p-3">

        <form method="GET">

            <div class="flex items-center gap-2">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name, email or course..."
                    class="form-input flex-1">

                <select
                    name="status"
                    class="form-input w-36">

                    <option value="">
                        All
                    </option>

                    <option value="1"
                        @selected(request('status') === '1')}>
                        Approved
                    </option>

                    <option value="0"
                        @selected(request('status') === '0')}>
                        Pending
                    </option>

                </select>

                <button class="btn-primary">
                    Filter
                </button>

                <a
                    href="{{ route('admin.alumni.index') }}"
                    class="btn-secondary">
                    Reset
                </a>

            </div>

        </form>

    </div>


    <div class="bg-white border rounded-xl
                overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px]
                          text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Alumni
                        </th>

                        <th class="px-4 py-3 text-left">
                            Course
                        </th>

                        <th class="px-4 py-3 text-left">
                            Year
                        </th>

                        <th class="px-4 py-3 text-left">
                            Organization
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                        <th class="px-4 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse($alumni as $alumnus)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">

                            <div class="flex
                                        items-center gap-3">

                                <div class="w-9 h-9
                                            rounded-full
                                            overflow-hidden
                                            bg-gray-100
                                            flex items-center
                                            justify-center">

                                    @if($alumnus->photo)

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $alumnus->photo
                                            ) }}"
                                            class="w-full h-full
                                                   object-cover">

                                    @else

                                        <i data-lucide="user"
                                           class="w-4 h-4
                                                  text-gray-400"></i>

                                    @endif

                                </div>

                                <div>

                                    <p class="font-semibold">
                                        {{ $alumnus->name }}
                                    </p>

                                    <p class="text-[11px]
                                              text-gray-500">
                                        {{ $alumnus->email }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        <td class="px-4 py-3">
                            {{ $alumnus->course }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $alumnus->graduation_year }}
                        </td>


                        <td class="px-4 py-3">

                            <p>
                                {{ $alumnus->organization ?: '—' }}
                            </p>

                            @if($alumnus->designation)

                                <p class="text-[11px]
                                          text-gray-500">

                                    {{ $alumnus->designation }}

                                </p>

                            @endif

                        </td>


                        <td class="px-4 py-3">

                            @if($alumnus->status)
                                <span class="badge-green">
                                    Approved
                                </span>
                            @else
                                <span class="badge-amber">
                                    Pending
                                </span>
                            @endif

                        </td>


                        <td class="px-4 py-3 text-right">

                            <div class="inline-flex gap-1">

                                <a
                                    href="{{ route(
                                        'admin.alumni.edit',
                                        $alumnus
                                    ) }}"
                                    class="icon-btn">

                                    <i data-lucide="pencil"
                                       class="w-4 h-4"></i>

                                </a>


                                @if(!$alumnus->status)

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.alumni.approve',
                                            $alumnus
                                        ) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="icon-btn
                                                   success"
                                            title="Approve">

                                            <i data-lucide="check"
                                               class="w-4 h-4"></i>

                                        </button>

                                    </form>

                                @else

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.alumni.reject',
                                            $alumnus
                                        ) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="icon-btn
                                                   warning"
                                            title="Unpublish">

                                            <i data-lucide="eye-off"
                                               class="w-4 h-4"></i>

                                        </button>

                                    </form>

                                @endif


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.alumni.destroy',
                                        $alumnus
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Delete this alumni record?'
                                    )">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="icon-btn danger">

                                        <i data-lucide="trash-2"
                                           class="w-4 h-4"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="px-4 py-10
                                   text-center
                                   text-gray-400">

                            No alumni records found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($alumni->hasPages())

            <div class="border-t px-4 py-3">

                {{ $alumni
                    ->withQueryString()
                    ->links() }}

            </div>

        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endpush