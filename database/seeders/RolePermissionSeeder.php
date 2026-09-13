<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions and roles
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Dashboard
            'view dashboard',

            // Admissions
            'view admissions',
            'create admissions',
            'edit admissions',
            'delete admissions',
            'verify admission fees',
            'approve admissions',
            'reject admissions',

            // Admission sessions
            'view admission sessions',
            'create admission sessions',
            'edit admission sessions',
            'delete admission sessions',
            'open admission sessions',
            'close admission sessions',

            // Courses
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',

            // Course categories
            'view course categories',
            'create course categories',
            'edit course categories',
            'delete course categories',

            // Course batches
            'view course batches',
            'create course batches',
            'edit course batches',
            'delete course batches',

            // Finance
            'view fee types',
            'create fee types',
            'edit fee types',
            'delete fee types',

            'view bank accounts',
            'create bank accounts',
            'edit bank accounts',
            'delete bank accounts',

            'view fee configurations',
            'create fee configurations',
            'edit fee configurations',
            'delete fee configurations',

            'view vouchers',
            'create vouchers',
            'edit vouchers',
            'verify payments',
            'reject payments',

            'view fee receipts',

            // Website content
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',

            'view events',
            'create events',
            'edit events',
            'delete events',

            'view alumni',
            'create alumni',
            'edit alumni',
            'delete alumni',

            'view galleries',
            'create galleries',
            'edit galleries',
            'delete galleries',

            // Users and settings
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',

            'view website settings',
            'edit website settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            'super-admin',
            'admin',
            'admission-officer',
            'accountant',
            'teacher',
            'content-manager',
            'student',
            'parent',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Role permissions
        |--------------------------------------------------------------------------
        */

        $allPermissions = Permission::where('guard_name', 'web')->get();

        // Full access
        Role::findByName('super-admin', 'web')
            ->syncPermissions($allPermissions);

        Role::findByName('admin', 'web')
            ->syncPermissions($allPermissions);

        // Admission officer
        Role::findByName('admission-officer', 'web')
            ->syncPermissions([
                'view dashboard',

                'view admissions',
                'create admissions',
                'edit admissions',
                'verify admission fees',
                'approve admissions',
                'reject admissions',

                'view admission sessions',

                'view courses',
                'view course categories',
                'view course batches',

                'view vouchers',
                'create vouchers',
                'view fee receipts',
            ]);

        // Accountant
        Role::findByName('accountant', 'web')
            ->syncPermissions([
                'view dashboard',

                'view admissions',
                'view admission sessions',

                'view fee types',
                'view bank accounts',
                'view fee configurations',

                'view vouchers',
                'create vouchers',
                'edit vouchers',
                'verify payments',
                'reject payments',

                'view fee receipts',
            ]);

        // Teacher
        Role::findByName('teacher', 'web')
            ->syncPermissions([
                'view dashboard',
                'view courses',
                'view course categories',
                'view course batches',
                'view admissions',
            ]);

        // Content manager
        Role::findByName('content-manager', 'web')
            ->syncPermissions([
                'view dashboard',

                'view announcements',
                'create announcements',
                'edit announcements',
                'delete announcements',

                'view events',
                'create events',
                'edit events',
                'delete events',

                'view alumni',
                'create alumni',
                'edit alumni',
                'delete alumni',

                'view galleries',
                'create galleries',
                'edit galleries',
                'delete galleries',

                'view website settings',
                'edit website settings',

                'view courses',
                'edit courses',
            ]);

        // Student
        Role::findByName('student', 'web')
            ->syncPermissions([
                'view dashboard',
                'view courses',
                'view course categories',
                'view course batches',
            ]);

        // Parent
        Role::findByName('parent', 'web')
            ->syncPermissions([
                'view dashboard',
                'view courses',
                'view course categories',
                'view course batches',
            ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('GATTC roles and permissions created successfully.');
    }
}