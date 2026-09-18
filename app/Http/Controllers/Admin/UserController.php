<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display users.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->with('roles')
            ->orderBy('name');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        if ($request->status === 'active') {
            $query->where('is_active', true);
        }

        if ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query
            ->paginate(15)
            ->withQueryString();

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        return view('admin.users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'activeUsers',
            'inactiveUsers'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $roles = Role::query()
            ->orderBy('name')
            ->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'roles' => [
                'required',
                'array',
                'min:1',
            ],

            'roles.*' => [
                'required',
                'exists:roles,name',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account created successfully.');
    }

    /**
     * Display user.
     */
    public function show(User $user)
    {
        $user->load('roles.permissions');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        $user->load('roles');

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        return view('admin.users.edit', compact(
            'user',
            'roles'
        ));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'roles' => [
                'required',
                'array',
                'min:1',
            ],

            'roles.*' => [
                'required',
                'exists:roles,name',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent administrator from disabling their own account
        |--------------------------------------------------------------------------
        */
        if ($request->user()->id === $user->id && !$request->boolean('is_active')) {
            return back()
                ->withInput()
                ->withErrors([
                    'is_active' => 'You cannot deactivate your own account.',
                ]);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with(
            'success',
            'User password updated successfully.'
        );
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $user->update([
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            'User account activated.'
        );
    }

    /**
     * Deactivate user.
     */
    public function deactivate(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->with(
                'error',
                'You cannot deactivate your own account.'
            );
        }

        $user->update([
            'is_active' => false,
        ]);

        return back()->with(
            'success',
            'User account deactivated.'
        );
    }

    /**
     * Delete user.
     */
    public function destroy(Request $request, User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deleting yourself
        |--------------------------------------------------------------------------
        */
        if ($request->user()->id === $user->id) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent deleting the final Super Admin
        |--------------------------------------------------------------------------
        */
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = User::role('Super Admin')->count();

            if ($superAdminCount <= 1) {
                return back()->with(
                    'error',
                    'The last Super Admin account cannot be deleted.'
                );
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account deleted successfully.');
    }
}