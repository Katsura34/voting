<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Party;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'usn' => 'ADMIN001',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample student users
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'usn' => 'STU001',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'usn' => 'STU002',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // Create default parties
        Party::create([
            'name' => 'Progressive Party',
            'slogan' => 'Progress for All Students',
            'color' => '#3B82F6', // Blue
            'is_active' => true,
        ]);

        Party::create([
            'name' => 'Unity Party',
            'slogan' => 'United We Stand',
            'color' => '#EF4444', // Red
            'is_active' => true,
        ]);
    }
}