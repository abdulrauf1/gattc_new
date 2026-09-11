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
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'GATTC Administrator',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        // Assign the super-admin role if Spatie Permission is installed.
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('super-admin');
        }

        $this->command->info('Default GATTC administrator is ready.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: Admin@12345');
    }
}