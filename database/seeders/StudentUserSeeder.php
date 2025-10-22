<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample student users
        $students = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'usn' => '1AB21CS001',
                'password' => Hash::make('student123'),
                'role' => 'student',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'usn' => '1AB21CS002',
                'password' => Hash::make('student123'),
                'role' => 'student',
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'usn' => '1AB21CS003',
                'password' => Hash::make('student123'),
                'role' => 'student',
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'usn' => '1AB21CS004',
                'password' => Hash::make('student123'),
                'role' => 'student',
            ],
            [
                'first_name' => 'Carol',
                'last_name' => 'Brown',
                'usn' => '1AB21CS005',
                'password' => Hash::make('student123'),
                'role' => 'student',
            ]
        ];
        
        foreach ($students as $studentData) {
            User::firstOrCreate(
                ['usn' => $studentData['usn']],
                $studentData
            );
        }
        
        echo "Sample student users created:\n";
        foreach ($students as $student) {
            echo "USN: {$student['usn']} | Name: {$student['first_name']} {$student['last_name']}\n";
        }
        echo "Password for all students: student123\n\n";
    }
}