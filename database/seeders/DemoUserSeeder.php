<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate([
            'email' => 'superadmin@example.com'
        ],[
            'name' => 'Super Admin',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // Admin
        User::updateOrCreate([
            'email' => 'admin@example.com'
        ],[
            'name' => 'Admin User',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Secretary
        User::updateOrCreate([
            'email' => 'secretary@example.com'
        ],[
            'name' => 'Secretary User',
            'password' => Hash::make('password123'),
            'role' => 'secretary',
        ]);

        // Citizen
        User::updateOrCreate([
            'email' => 'citizen@example.com'
        ],[
            'name' => 'Citizen User',
            'password' => Hash::make('password123'),
            'role' => 'citizen',
        ]);
    }
}
