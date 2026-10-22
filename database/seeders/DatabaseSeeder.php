<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Administrator User
        User::firstOrCreate(
            ['email' => 'admin@complaint.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Password@123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Demo Student User
        User::firstOrCreate(
            ['email' => 'student@complaint.test'],
            [
                'name' => 'Rahul Sharma (Student)',
                'password' => Hash::make('Password@123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        // 3. Seed Departments
        $this->call(DepartmentSeeder::class);

        // 4. Seed Sample Complaints
        $this->call(ComplaintSeeder::class);
    }
}
