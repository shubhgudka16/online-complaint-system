<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_complaints(): void
    {
        $response = $this->get(route('complaints.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_student_only_sees_their_own_complaints(): void
    {
        $studentA = User::factory()->student()->create();
        $studentB = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaintA = Complaint::factory()->create([
            'user_id' => $studentA->id,
            'department_id' => $dept->id,
            'title' => 'Student A Lab Monitor Broken',
        ]);

        $complaintB = Complaint::factory()->create([
            'user_id' => $studentB->id,
            'department_id' => $dept->id,
            'title' => 'Student B Hostel Water Leak',
        ]);

        $response = $this->actingAs($studentA)->get(route('complaints.index'));

        $response->assertOk();
        $response->assertSee('Student A Lab Monitor Broken');
        $response->assertDontSee('Student B Hostel Water Leak');
    }

    public function test_student_cannot_view_another_students_complaint(): void
    {
        $studentA = User::factory()->student()->create();
        $studentB = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaintB = Complaint::factory()->create([
            'user_id' => $studentB->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($studentA)->get(route('complaints.show', $complaintB));

        $response->assertForbidden();
    }

    public function test_admin_can_view_any_students_complaint(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
            'title' => 'Specific Grievance Title',
        ]);

        $response = $this->actingAs($admin)->get(route('complaints.show', $complaint));

        $response->assertOk();
        $response->assertSee('Specific Grievance Title');
    }

    public function test_student_can_submit_complaint_with_valid_data(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $response = $this->actingAs($student)->post(route('complaints.store'), [
            'department_id' => $dept->id,
            'title' => 'Classroom 302 Projector Bulb Blown',
            'description' => 'The ceiling projector is unable to turn on and the orange error lamp is flashing continuously.',
            'priority' => 'High',
        ]);

        $complaint = Complaint::first();
        $this->assertNotNull($complaint);
        $response->assertRedirect(route('complaints.show', $complaint));
        $this->assertEquals('Pending', $complaint->status);
        $this->assertStringStartsWith('CMP-', $complaint->reference_no);
        $this->assertEquals($student->id, $complaint->user_id);
    }

    public function test_complaint_submission_requires_mandatory_fields(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->post(route('complaints.store'), []);

        $response->assertSessionHasErrors(['department_id', 'title', 'description', 'priority']);
    }

    public function test_student_can_edit_own_pending_complaint(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->pending()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
            'title' => 'Original Title',
        ]);

        $response = $this->actingAs($student)->put(route('complaints.update', $complaint), [
            'department_id' => $dept->id,
            'title' => 'Corrected Title After Edit',
            'description' => 'Detailed updated description with extra details.',
            'priority' => 'Low',
        ]);

        $response->assertRedirect(route('complaints.show', $complaint));
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'title' => 'Corrected Title After Edit',
        ]);
    }

    public function test_student_cannot_edit_complaint_once_in_progress_or_resolved(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->inProgress()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($student)->get(route('complaints.edit', $complaint));
        $response->assertForbidden();

        $updateResponse = $this->actingAs($student)->put(route('complaints.update', $complaint), [
            'department_id' => $dept->id,
            'title' => 'Attempted Edit While In Progress',
            'description' => 'Should fail due to policy status rule.',
            'priority' => 'High',
        ]);
        $updateResponse->assertForbidden();
    }

    public function test_student_can_delete_own_pending_complaint(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->pending()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($student)->delete(route('complaints.destroy', $complaint));

        $response->assertRedirect(route('complaints.index'));
        $this->assertDatabaseMissing('complaints', ['id' => $complaint->id]);
    }

    public function test_student_cannot_delete_complaint_once_in_progress(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->inProgress()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($student)->delete(route('complaints.destroy', $complaint));

        $response->assertForbidden();
        $this->assertDatabaseHas('complaints', ['id' => $complaint->id]);
    }

    public function test_admin_can_update_complaint_status_and_remarks(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->pending()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($admin)->patch(route('complaints.status', $complaint), [
            'status' => 'Resolved',
            'admin_remarks' => 'Issue verified and replacement part installed.',
        ]);

        $response->assertRedirect(route('complaints.show', $complaint));
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'Resolved',
            'admin_remarks' => 'Issue verified and replacement part installed.',
        ]);
    }

    public function test_student_cannot_update_complaint_status(): void
    {
        $student = User::factory()->student()->create();
        $dept = Department::factory()->create();

        $complaint = Complaint::factory()->pending()->create([
            'user_id' => $student->id,
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($student)->patch(route('complaints.status', $complaint), [
            'status' => 'Resolved',
            'admin_remarks' => 'Student trying to resolve their own ticket.',
        ]);

        $response->assertForbidden();
    }

    public function test_complaints_search_and_filters(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $deptA = Department::factory()->create(['name' => 'Hostel A']);
        $deptB = Department::factory()->create(['name' => 'Hostel B']);

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $deptA->id,
            'title' => 'Unique Water Purifier Failure',
            'status' => 'Pending',
            'priority' => 'High',
        ]);

        Complaint::factory()->create([
            'user_id' => $student->id,
            'department_id' => $deptB->id,
            'title' => 'Routine Painting Request',
            'status' => 'Resolved',
            'priority' => 'Low',
        ]);

        $response = $this->actingAs($admin)->get(route('complaints.index', [
            'search' => 'Water Purifier',
            'status' => 'Pending',
            'department_id' => $deptA->id,
            'priority' => 'High',
        ]));

        $response->assertOk();
        $response->assertSee('Unique Water Purifier Failure');
        $response->assertDontSee('Routine Painting Request');
    }
}
