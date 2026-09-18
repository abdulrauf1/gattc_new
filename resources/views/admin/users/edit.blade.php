@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="mx-auto max-w-4xl space-y-4">

    <div class="flex items-center gap-3">

        <a
            href="{{ route('admin.users.show', $user) }}"
            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
        >
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>

        <div>
            <h1 class="text-xl font-semibold text-gray-900">
                Edit User
            </h1>

            <p class="text-sm text-gray-500">
                Update account information and assigned roles.
            </p>
        </div>

    </div>


    <form
        method="POST"
        action="{{ route('admin.users.update', $user) }}"
        class="overflow-hidden rounded-xl border border-gray-200 bg-white"
    >

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >

                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >

                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Role(s)
                </label>

                @php
                    $selectedRoles = old(
                        'roles',
                        $user->roles->pluck('name')->toArray()
                    );
                @endphp

                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach($roles as $role)

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">

                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                @checked(in_array($role->name, $selectedRoles))
                                class="mt-0.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                            >

                            <div>
                                <div class="text-sm font-medium text-gray-800">
                                    {{ $role->name }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $role->permissions->count() }} permissions
                                </div>
                            </div>

                        </label>

                    @endforeach

                </div>

                @error('roles')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

            </div>


            <div class="md:col-span-2">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $user->is_active))
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <div class="text-sm font-medium text-gray-700">
                            Active Account
                        </div>

                        <div class="text-xs text-gray-500">
                            Allow this user to access the system.
                        </div>
                    </div>

                </label>

            </div>

        </div>


        <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-5 py-4">

            <a
                href="{{ route('admin.users.show', $user) }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
            >
                Save Changes
            </button>

        </div>

    </form>


    {{-- Password --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="border-b border-gray-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-gray-900">
                Change Password
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Set a new password for this account.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('admin.users.password', $user) }}"
            class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2"
        >

            @csrf
            @method('PATCH')

            <div>

                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >

            </div>


            <div>

                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >

            </div>


            <div class="md:col-span-2">

                @error('password')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="mt-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Update Password
                </button>

            </div>

        </form>

    </div>

</div>

@endsection