<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference_no' => 'CMP-'.date('Y').'-'.fake()->unique()->numberBetween(1000, 9999),
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(3),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'status' => fake()->randomElement(['Pending', 'In Progress', 'Resolved', 'Rejected']),
            'admin_remarks' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'Pending',
            'admin_remarks' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => [
            'status' => 'In Progress',
            'admin_remarks' => 'Under active review by department staff.',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn () => [
            'status' => 'Resolved',
            'admin_remarks' => 'Issue inspected and resolved satisfactorily.',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'Rejected',
            'admin_remarks' => 'Does not comply with department policies.',
        ]);
    }
}
