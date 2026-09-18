@extends('layouts.admin')

@section('title', 'Create User')

@section('content')

<div class="mx-auto max-w-4xl space-y-4">

    <div class="flex items-center gap-3">

        <a
            href="{{ route('admin.users.index') }}"
            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
        >
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>

        <div>
            <h1 class="text-xl font-semibold text-gray-900">
                Create User
            </h1>

            <p class="text-sm text-gray-500">
                Create an administrator account and assign roles.
            </p>
        </div>

    </div>


    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        class="overflow-hidden rounded-xl border border-gray-200 bg-white"
    >

        @csrf

        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

            {{-- Name --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="Enter full name"
                >

                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Email --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="name@gattc.edu.pk"
                >

                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Password --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="Minimum 8 characters"
                >

                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Confirm --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="Repeat password"
                >
            </div>


            {{-- Roles --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Assign Role(s)
                </label>

                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">

                    @forelse($roles as $role)

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">

                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                @checked(in_array($role->name, old('roles', [])))
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

                    @empty

                        <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                            No roles are available. Please create roles in the Spatie permissions system first.
                        </div>

                    @endforelse

                </div>

                @error('roles')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

            </div>


            {{-- Active --}}
            <div class="md:col-span-2">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <div class="text-sm font-medium text-gray-700">
                            Active Account
                        </div>

                        <div class="text-xs text-gray-500">
                            The user can log in immediately.
                        </div>
                    </div>

                </label>

            </div>

        </div>


        <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-5 py-4">

            <a
                href="{{ route('admin.users.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
            >
                Create User
            </button>

        </div>

    </form>

</div>

@endsection