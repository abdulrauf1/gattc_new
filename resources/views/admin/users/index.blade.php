@extends('layouts.admin')

@section('title', 'User Accounts')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">
                User Accounts
            </h1>

            <p class="text-sm text-gray-500">
                Manage administrator accounts and assigned roles.
            </p>
        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
        >
            <i data-lucide="user-plus" class="h-4 w-4"></i>
            Create User
        </a>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Total Users
            </div>
            <div class="mt-1 text-2xl font-semibold text-gray-900">
                {{ $totalUsers }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Active
            </div>
            <div class="mt-1 text-2xl font-semibold text-emerald-600">
                {{ $activeUsers }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Inactive
            </div>
            <div class="mt-1 text-2xl font-semibold text-gray-500">
                {{ $inactiveUsers }}
            </div>
        </div>

    </div>


    {{-- Main panel --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Tabs --}}
        <div class="border-b border-gray-200">
            <div class="flex overflow-x-auto">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="whitespace-nowrap border-b-2 border-emerald-600 px-4 py-3 text-sm font-medium text-emerald-700"
                >
                    All
                    <span class="ml-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs">
                        {{ $totalUsers }}
                    </span>
                </a>

            </div>
        </div>


        {{-- Filters --}}
        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="border-b border-gray-200 p-4"
        >

            <div class="grid grid-cols-1 gap-3 md:grid-cols-4">

                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-medium text-gray-600">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name or email..."
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                        <option value="">All Roles</option>

                        @foreach($roles as $role)
                            <option
                                value="{{ $role->name }}"
                                @selected(request('role') === $role->name)
                            >
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                        <option value="">All Status</option>
                        <option value="active" @selected(request('status') === 'active')>
                            Active
                        </option>
                        <option value="inactive" @selected(request('status') === 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>


            <div class="mt-3 flex gap-2">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                >
                    <i data-lucide="filter" class="h-4 w-4"></i>
                    Filter
                </button>

                <a
                    href="{{ route('admin.users.index') }}"
                    title="Reset"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-3 text-gray-600 hover:bg-gray-50"
                >
                    <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                </a>

            </div>

        </form>


        {{-- Desktop table --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>

                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            User
                        </th>

                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Email
                        </th>

                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Roles
                        </th>

                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            ID: {{ $user->id }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-3">

                                <div class="flex flex-wrap gap-1">

                                    @forelse($user->roles as $role)

                                        <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                            {{ $role->name }}
                                        </span>

                                    @empty

                                        <span class="text-xs text-gray-400">
                                            No role assigned
                                        </span>

                                    @endforelse

                                </div>

                            </td>

                            <td class="px-4 py-3">

                                @if($user->is_active)

                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Active
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="px-4 py-3">

                                <div class="flex items-center justify-end gap-1">

                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        title="View"
                                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800"
                                    >
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>

                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        title="Edit"
                                        class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600"
                                    >
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>

                                    @if($user->is_active)

                                        @if(auth()->id() !== $user->id)
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.deactivate', $user) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    title="Deactivate"
                                                    type="submit"
                                                    class="rounded-lg p-2 text-gray-500 hover:bg-amber-50 hover:text-amber-600"
                                                >
                                                    <i data-lucide="user-x" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.activate', $user) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                title="Activate"
                                                type="submit"
                                                class="rounded-lg p-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600"
                                            >
                                                <i data-lucide="user-check" class="h-4 w-4"></i>
                                            </button>
                                        </form>

                                    @endif

                                    @if(auth()->id() !== $user->id)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Delete this user account permanently?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                title="Delete"
                                                type="submit"
                                                class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"
                                            >
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="text-sm font-medium text-gray-600">
                                    No users found.
                                </div>
                                <div class="mt-1 text-xs text-gray-400">
                                    Create the first administrator account.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Mobile cards --}}
        <div class="divide-y divide-gray-100 md:hidden">

            @forelse($users as $user)

                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-semibold text-emerald-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $user->name }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $user->email }}
                                </div>
                            </div>

                        </div>

                        @if($user->is_active)

                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700">
                                Active
                            </span>

                        @else

                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                Inactive
                            </span>

                        @endif

                    </div>


                    <div class="mt-3 flex flex-wrap gap-1">

                        @foreach($user->roles as $role)

                            <span class="rounded-full bg-blue-50 px-2 py-1 text-xs text-blue-700">
                                {{ $role->name }}
                            </span>

                        @endforeach

                    </div>


                    <div class="mt-3 flex gap-1 border-t border-gray-100 pt-3">

                        <a
                            href="{{ route('admin.users.show', $user) }}"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                        >
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </a>

                        <a
                            href="{{ route('admin.users.edit', $user) }}"
                            class="rounded-lg p-2 text-gray-500 hover:bg-blue-50"
                        >
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                        </a>

                        @if(auth()->id() !== $user->id)

                            @if($user->is_active)

                                <form
                                    method="POST"
                                    action="{{ route('admin.users.deactivate', $user) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button class="rounded-lg p-2 text-gray-500 hover:bg-amber-50">
                                        <i data-lucide="user-x" class="h-4 w-4"></i>
                                    </button>
                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('admin.users.activate', $user) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button class="rounded-lg p-2 text-gray-500 hover:bg-emerald-50">
                                        <i data-lucide="user-check" class="h-4 w-4"></i>
                                    </button>
                                </form>

                            @endif

                            <form
                                method="POST"
                                action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Delete this user account permanently?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

            @empty

                <div class="p-10 text-center text-sm text-gray-500">
                    No users found.
                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="border-t border-gray-200 p-4">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>

@endsection