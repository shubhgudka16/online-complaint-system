<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Administrator dashboard with campus-wide metrics.
     */
    public function adminDashboard(): View
    {
        $metrics = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('status', 'In Progress')->count(),
            'resolved' => Complaint::where('status', 'Resolved')->count(),
            'rejected' => Complaint::where('status', 'Rejected')->count(),
        ];

        $departments = Department::withCount([
            'complaints',
            'complaints as pending_count' => fn ($q) => $q->where('status', 'Pending'),
            'complaints as resolved_count' => fn ($q) => $q->where('status', 'Resolved'),
        ])->orderByDesc('complaints_count')->get();

        $recentComplaints = Complaint::with(['user', 'department'])
            ->latest('id')
            ->take(6)
            ->get();

        return view('dashboard.admin', compact('metrics', 'departments', 'recentComplaints'));
    }

    /**
     * Display the Student dashboard with personal complaint tracking.
     */
    public function studentDashboard(Request $request): View
    {
        $user = $request->user();

        $metrics = [
            'total' => $user->complaints()->count(),
            'pending' => $user->complaints()->where('status', 'Pending')->count(),
            'in_progress' => $user->complaints()->where('status', 'In Progress')->count(),
            'resolved' => $user->complaints()->where('status', 'Resolved')->count(),
            'rejected' => $user->complaints()->where('status', 'Rejected')->count(),
        ];

        $recentComplaints = $user->complaints()
            ->with('department')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.student', compact('metrics', 'recentComplaints'));
    }

    /**
     * Display comprehensive analytical reports with filtering.
     */
    public function reports(Request $request): View
    {
        $departmentId = $request->query('department_id');
        $status = $request->query('status');
        $priority = $request->query('priority');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Complaint::with(['user', 'department'])
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($priority, fn ($q) => $q->where('priority', $priority))
            ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));

        $filteredComplaints = (clone $query)->latest('id')->paginate(15)->withQueryString();

        $reportMetrics = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'in_progress' => (clone $query)->where('status', 'In Progress')->count(),
            'resolved' => (clone $query)->where('status', 'Resolved')->count(),
            'rejected' => (clone $query)->where('status', 'Rejected')->count(),
        ];

        $departments = Department::orderBy('name')->get();
        $statuses = Complaint::STATUSES;
        $priorities = Complaint::PRIORITIES;

        $departmentBreakdown = Department::withCount([
            'complaints',
            'complaints as pending_count' => fn ($q) => $q->where('status', 'Pending'),
            'complaints as in_progress_count' => fn ($q) => $q->where('status', 'In Progress'),
            'complaints as resolved_count' => fn ($q) => $q->where('status', 'Resolved'),
            'complaints as rejected_count' => fn ($q) => $q->where('status', 'Rejected'),
        ])->get();

        return view('reports.index', compact(
            'filteredComplaints',
            'reportMetrics',
            'departments',
            'statuses',
            'priorities',
            'departmentBreakdown'
        ));
    }
}
