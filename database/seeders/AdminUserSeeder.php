<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default GATTC administrator.
     */
    public function run(): void
    {
       $user = User::firstOrCreate(
            [
                'email' => 'admin@gattc.edu.pk',
            ],
            [
                'name' => 'GATTC Super Admin',
                'email' => 'admin@gattc.edu.pk',
                'password' => Hash::make('Admin@12345'),
                'is_active' => true,
            ]
        );

        $user->syncRoles([
            'Super Admin',
        ]);
        
        // // Assign the super-admin role if Spatie Permission is installed.
        // if (method_exists($user, 'assignRole')) {
        //     $user->assignRole('super-admin');
        // }

        $this->command->info('Default GATTC administrator is ready.');
        $this->command->info('Email: admin@gattc.edu.pk');
        $this->command->info('Password: Admin@12345');
    }
}