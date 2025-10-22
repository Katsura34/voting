<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['usn' => 'ADMIN001'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'usn' => 'ADMIN001',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'usn_verified_at' => now(),
            ]
        );
        
        echo "Admin user created:\n";
        echo "USN: ADMIN001\n";
        echo "Password: admin123\n";
        echo "Role: admin\n\n";
    }
}