<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_departments(): void
    {
        $response = $this->get(route('departments.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_students_cannot_access_departments(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('departments.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_departments_list(): void
    {
        $admin = User::factory()->admin()->create();
        $dept = Department::factory()->create(['name' => 'Physics Laboratory']);

        $response = $this->actingAs($admin)->get(route('departments.index'));

        $response->assertOk();
        $response->assertSee('Physics Laboratory');
    }

    public function test_admin_can_create_department_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('departments.store'), [
            'name' => 'Sports & Gymnasium',
            'description' => 'Oversees campus sports equipment and tournament scheduling.',
        ]);

        $response->assertRedirect(route('departments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('departments', [
            'name' => 'Sports & Gymnasium',
        ]);
    }

    public function test_department_creation_requires_unique_name(): void
    {
        $admin = User::factory()->admin()->create();
        Department::factory()->create(['name' => 'Sports & Gymnasium']);

        $response = $this->actingAs($admin)->post(route('departments.store'), [
            'name' => 'Sports & Gymnasium',
            'description' => 'Another description.',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_can_update_department(): void
    {
        $admin = User::factory()->admin()->create();
        $dept = Department::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put(route('departments.update', $dept), [
            'name' => 'Updated Department Name',
            'description' => 'Updated Description.',
        ]);

        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('departments', [
            'id' => $dept->id,
            'name' => 'Updated Department Name',
        ]);
    }

    public function test_admin_cannot_delete_department_with_associated_complaints(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create(['name' => 'Robotics Lab']);

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('departments.destroy', $dept));

        $response->assertRedirect(route('departments.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('departments', ['id' => $dept->id]);
    }

    public function test_admin_can_delete_department_with_zero_complaints(): void
    {
        $admin = User::factory()->admin()->create();
        $dept = Department::factory()->create(['name' => 'Empty Department']);

        $response = $this->actingAs($admin)->delete(route('departments.destroy', $dept));

        $response->assertRedirect(route('departments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('departments', ['id' => $dept->id]);
    }
}
