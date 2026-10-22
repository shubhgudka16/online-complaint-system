<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('role', 'student')->first();
        if (! $student) {
            return;
        }

        $deptIt = Department::where('name', 'Computer Science & IT Lab')->first();
        $deptHostel = Department::where('name', 'Hostel & Accommodation')->first();
        $deptAcad = Department::where('name', 'Academic Affairs')->first();
        $deptLib = Department::where('name', 'Library & Learning Resources')->first();
        $deptAcc = Department::where('name', 'Accounts & Fee Section')->first();
        $deptMaint = Department::where('name', 'Campus Facilities & Maintenance')->first();

        $samples = [
            [
                'reference_no' => 'CMP-'.date('Y').'-0001',
                'user_id' => $student->id,
                'department_id' => $deptIt?->id ?? 1,
                'title' => 'Lab 3 PC-14 Monitor flickering intermittently',
                'description' => 'During practical sessions, the monitor on terminal PC-14 keeps blacking out every few minutes, interrupting programming assignments.',
                'priority' => 'High',
                'status' => 'In Progress',
                'admin_remarks' => 'IT technician dispatched to replace faulty VGA/HDMI cable and test graphics card.',
            ],
            [
                'reference_no' => 'CMP-'.date('Y').'-0002',
                'user_id' => $student->id,
                'department_id' => $deptHostel?->id ?? 2,
                'title' => 'Hostel Block B 3rd Floor Water Purifier Leaking',
                'description' => 'The RO water dispenser on the 3rd floor corridor has a constant water leak causing water accumulation near room 305.',
                'priority' => 'High',
                'status' => 'Pending',
                'admin_remarks' => null,
            ],
            [
                'reference_no' => 'CMP-'.date('Y').'-0003',
                'user_id' => $student->id,
                'department_id' => $deptAcad?->id ?? 3,
                'title' => 'Diploma Semester 5 elective subject syllabus clarification',
                'description' => 'Need official updated copy of the elective syllabus for Cloud Computing to prepare for upcoming mid-term tests.',
                'priority' => 'Low',
                'status' => 'Resolved',
                'admin_remarks' => 'Syllabus PDF uploaded to student portal and emailed to student registered address.',
            ],
            [
                'reference_no' => 'CMP-'.date('Y').'-0004',
                'user_id' => $student->id,
                'department_id' => $deptLib?->id ?? 4,
                'title' => 'Digital Library IEEE portal credentials expired',
                'description' => 'Cannot log into the IEEE Xplore digital library portal from the campus network or VPN.',
                'priority' => 'Medium',
                'status' => 'Pending',
                'admin_remarks' => null,
            ],
            [
                'reference_no' => 'CMP-'.date('Y').'-0005',
                'user_id' => $student->id,
                'department_id' => $deptAcc?->id ?? 5,
                'title' => 'Duplicate semester fee receipt required for state scholarship',
                'description' => 'State scholarship portal requires a signed stamped copy of the 1st year fee payment receipt.',
                'priority' => 'Medium',
                'status' => 'Resolved',
                'admin_remarks' => 'Duplicate stamped receipt issued at counter #2.',
            ],
            [
                'reference_no' => 'CMP-'.date('Y').'-0006',
                'user_id' => $student->id,
                'department_id' => $deptMaint?->id ?? 6,
                'title' => 'Request to install personal heater in hostel room',
                'description' => 'Requesting special permission to use an electric room heater during winter in hostel room.',
                'priority' => 'Low',
                'status' => 'Rejected',
                'admin_remarks' => 'High-wattage personal heating appliances are prohibited as per campus fire safety norms.',
            ],
        ];

        foreach ($samples as $sample) {
            Complaint::firstOrCreate(
                ['reference_no' => $sample['reference_no']],
                $sample
            );
        }
    }
}
