<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Define application permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.activate',
            'users.password',

            // Admission Sessions
            'admission-sessions.view',
            'admission-sessions.create',
            'admission-sessions.edit',
            'admission-sessions.delete',
            'admission-sessions.open',
            'admission-sessions.close',

            // Admissions
            'admissions.view',
            'admissions.approve',
            'admissions.reject',
            'admissions.student-card',

            // Vouchers
            'vouchers.view',
            'vouchers.create',
            'vouchers.delete',
            'vouchers.print',

            // Payments
            'payments.view',
            'payments.update',
            'payments.approve',
            'payments.reject',

            // Course Categories
            'course-categories.view',
            'course-categories.create',
            'course-categories.edit',
            'course-categories.delete',

            // Courses
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.delete',

            // Bank Accounts
            'bank-accounts.view',
            'bank-accounts.create',
            'bank-accounts.edit',
            'bank-accounts.delete',

            // Gallery
            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

            // Announcements
            'announcements.view',
            'announcements.create',
            'announcements.edit',
            'announcements.delete',

            // Events
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',

            // Alumni
            'alumni.view',
            'alumni.create',
            'alumni.edit',
            'alumni.delete',
            'alumni.approve',
            'alumni.reject',

            // Contact Messages
            'contact-messages.view',
            'contact-messages.delete',
            'contact-messages.notes',

            // Website Settings
            'website-settings.view',
            'website-settings.edit',
        ];


        /*
        |--------------------------------------------------------------------------
        | Create permissions
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $administrator = Role::firstOrCreate([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);

        $admissionOfficer = Role::firstOrCreate([
            'name' => 'Admission Officer',
            'guard_name' => 'web',
        ]);

        $financeOfficer = Role::firstOrCreate([
            'name' => 'Finance Officer',
            'guard_name' => 'web',
        ]);

        $websiteManager = Role::firstOrCreate([
            'name' => 'Website Manager',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        | Super Admin receives every permission.
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        */

        $administratorPermissions = [
            'dashboard.view',

            'admission-sessions.view',
            'admission-sessions.create',
            'admission-sessions.edit',
            'admission-sessions.open',
            'admission-sessions.close',

            'admissions.view',
            'admissions.approve',
            'admissions.reject',
            'admissions.student-card',

            'vouchers.view',
            'vouchers.create',
            'vouchers.print',

            'payments.view',
            'payments.update',
            'payments.approve',
            'payments.reject',

            'course-categories.view',
            'course-categories.create',
            'course-categories.edit',

            'courses.view',
            'courses.create',
            'courses.edit',

            'bank-accounts.view',

            'gallery.view',
            'gallery.create',
            'gallery.edit',

            'announcements.view',
            'announcements.create',
            'announcements.edit',

            'events.view',
            'events.create',
            'events.edit',

            'alumni.view',
            'alumni.create',
            'alumni.edit',
            'alumni.approve',
            'alumni.reject',

            'contact-messages.view',
            'contact-messages.notes',

            'website-settings.view',
            'website-settings.edit',
        ];

        $administrator->syncPermissions(
            Permission::whereIn('name', $administratorPermissions)
                ->where('guard_name', 'web')
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Admission Officer
        |--------------------------------------------------------------------------
        */

        $admissionOfficerPermissions = [
            'dashboard.view',

            'admission-sessions.view',

            'admissions.view',
            'admissions.approve',
            'admissions.reject',
            'admissions.student-card',

            'vouchers.view',
            'vouchers.create',
            'vouchers.print',

            'payments.view',
        ];

        $admissionOfficer->syncPermissions(
            Permission::whereIn('name', $admissionOfficerPermissions)
                ->where('guard_name', 'web')
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Finance Officer
        |--------------------------------------------------------------------------
        */

        $financeOfficerPermissions = [
            'dashboard.view',

            'vouchers.view',
            'vouchers.create',
            'vouchers.print',

            'payments.view',
            'payments.update',
            'payments.approve',
            'payments.reject',

            'bank-accounts.view',
        ];

        $financeOfficer->syncPermissions(
            Permission::whereIn('name', $financeOfficerPermissions)
                ->where('guard_name', 'web')
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Website Manager
        |--------------------------------------------------------------------------
        */

        $websiteManagerPermissions = [
            'dashboard.view',

            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

            'announcements.view',
            'announcements.create',
            'announcements.edit',
            'announcements.delete',

            'events.view',
            'events.create',
            'events.edit',
            'events.delete',

            'alumni.view',
            'alumni.create',
            'alumni.edit',
            'alumni.approve',
            'alumni.reject',

            'contact-messages.view',
            'contact-messages.delete',
            'contact-messages.notes',

            'website-settings.view',
            'website-settings.edit',
        ];

        $websiteManager->syncPermissions(
            Permission::whereIn('name', $websiteManagerPermissions)
                ->where('guard_name', 'web')
                ->get()
        );


        $this->command->info(
            'GATTC roles and permissions created successfully.'
        );
    }
}