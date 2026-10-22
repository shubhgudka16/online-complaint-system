<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Academic Affairs',
                'description' => 'Handles curriculum, examinations, timetables, lecturer allocations, and academic grievances.',
            ],
            [
                'name' => 'Computer Science & IT Lab',
                'description' => 'Manages computer labs, internet connectivity, software installation, and workstation hardware.',
            ],
            [
                'name' => 'Hostel & Accommodation',
                'description' => 'Oversees student residences, room allotment, mess food hygiene, and hostel facilities.',
            ],
            [
                'name' => 'Library & Learning Resources',
                'description' => 'Manages textbook borrowing, digital library access, study halls, and reference materials.',
            ],
            [
                'name' => 'Accounts & Fee Section',
                'description' => 'Assists with tuition fee receipts, scholarship disbursements, exam fee verification, and refunds.',
            ],
            [
                'name' => 'Campus Facilities & Maintenance',
                'description' => 'Maintains electricity, air conditioning, sanitation, clean drinking water, and infrastructure repairs.',
            ],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['name' => $dept['name']],
                ['description' => $dept['description']]
            );
        }
    }
}
