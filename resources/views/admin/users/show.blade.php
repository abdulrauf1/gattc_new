@extends('layouts.admin')

@section('title', 'User Details')

@section('content')

<div class="mx-auto max-w-4xl space-y-4">

    <div class="flex items-center justify-between gap-3">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('admin.users.index') }}"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
            >
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>

            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    User Details
                </h1>

                <p class="text-sm text-gray-500">
                    Account information and assigned permissions.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.users.edit', $user) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
        >
            <i data-lucide="pencil" class="h-4 w-4"></i>
            Edit
        </a>

    </div>


    {{-- Basic details --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="flex items-center gap-4 border-b border-gray-200 p-5">

            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-xl font-semibold text-emerald-700">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="min-w-0">

                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $user->name }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $user->email }}
                </p>

            </div>

            <div class="ml-auto">

                @if($user->is_active)

                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                        Active
                    </span>

                @else

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                        Inactive
                    </span>

                @endif

            </div>

        </div>


        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">

            <div>
                <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    User ID
                </div>

                <div class="mt-1 text-sm text-gray-800">
                    #{{ $user->id }}
                </div>
            </div>

            <div>
                <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Email
                </div>

                <div class="mt-1 text-sm text-gray-800">
                    {{ $user->email }}
                </div>
            </div>

            <div>
                <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Created
                </div>

                <div class="mt-1 text-sm text-gray-800">
                    {{ $user->created_at?->format('d M Y, h:i A') }}
                </div>
            </div>

            <div>
                <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Last Updated
                </div>

                <div class="mt-1 text-sm text-gray-800">
                    {{ $user->updated_at?->format('d M Y, h:i A') }}
                </div>
            </div>

        </div>

    </div>


    {{-- Roles --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="border-b border-gray-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-gray-900">
                Assigned Roles
            </h2>

        </div>

        <div class="p-5">

            <div class="flex flex-wrap gap-2">

                @forelse($user->roles as $role)

                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700">
                        {{ $role->name }}
                    </span>

                @empty

                    <span class="text-sm text-gray-400">
                        No role assigned.
                    </span>

                @endforelse

            </div>

        </div>

    </div>


    {{-- Permissions --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="border-b border-gray-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-gray-900">
                Effective Permissions
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Permissions provided through the assigned roles.
            </p>

        </div>

        <div class="p-5">

            @php
                $permissions = $user->getAllPermissions()->sortBy('name');
            @endphp

            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">

                @forelse($permissions as $permission)

                    <div class="rounded-lg border border-gray-200 px-3 py-2">

                        <div class="text-sm font-medium text-gray-700">
                            {{ $permission->name }}
                        </div>

                    </div>

                @empty

                    <div class="text-sm text-gray-400">
                        No permissions assigned.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection