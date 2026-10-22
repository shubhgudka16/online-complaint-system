<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_dashboards_or_reports(): void
    {
        $this->get(route('student.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.reports'))->assertRedirect(route('login'));
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $student = User::factory()->student()->create(['name' => 'Kavita Patel']);
        $dept = Department::factory()->create();

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($student)->get(route('student.dashboard'));

        $response->assertOk();
        $response->assertSee('Kavita Patel');
        $response->assertSee('Student Grievance Portal');
    }

    public function test_student_is_forbidden_from_admin_dashboard_and_reports(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($student)->get(route('admin.reports'))->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();
        $dept = Department::factory()->create(['name' => 'Hostel Affairs']);

        Complaint::factory()->create([
            'department_id' => $dept->id,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Campus Grievance Command Center');
        $response->assertSee('Hostel Affairs');
    }

    public function test_admin_can_filter_reports(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $deptA = Department::factory()->create(['name' => 'IT Infrastructure']);
        $deptB = Department::factory()->create(['name' => 'Accounts Desk']);

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $deptA->id,
            'title' => 'WiFi Access Point 4 Reboot Request',
            'status' => 'Resolved',
        ]);

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $deptB->id,
            'title' => 'Duplicate Fee Slip',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports', [
            'department_id' => $deptA->id,
            'status' => 'Resolved',
        ]));

        $response->assertOk();
        $response->assertSee('WiFi Access Point 4 Reboot Request');
        $response->assertDontSee('Duplicate Fee Slip');
    }
}
