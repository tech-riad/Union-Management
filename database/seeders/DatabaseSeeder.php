<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default users or factories (optional)
        // \App\Models\User::factory(10)->create();

        // Call CertificateTypeSeeder
        $this->call(\Database\Seeders\CertificateTypeSeeder::class);

        // Call DemoUserSeeder
        $this->call(\Database\Seeders\DemoUserSeeder::class);
    }
}
