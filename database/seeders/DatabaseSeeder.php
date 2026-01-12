<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => 'password', // Password akan di-hash oleh mutator/factory jika ada, atau oleh UserFactory default
        ]);

        // Santri
        User::factory()->create([
            'name' => 'Santri User',
            'email' => 'santri@santri.com',
            'role' => 'santri',
            'password' => 'password',
        ]);
    }
}
