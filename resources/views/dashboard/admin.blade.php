@extends('layouts.app')

@section('title', 'Administrator Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Campus Grievance Command Center
        </h2>
        <p class="text-muted small mb-0">Real-time overview of student grievances, department performance, and resolution pipelines</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-building me-1"></i> Departments
        </a>
        <a href="{{ route('admin.reports') }}" class="btn btn-brand">
            <i class="bi bi-graph-up-arrow me-1"></i> Detailed Reports
        </a>
    </div>
</div>

<!-- Key Performance Indicators Row -->
<div class="row g-3 mb-4">
    <!-- Total Complaints -->
    <div class="col-sm-6 col-lg-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div class="stats-icon-bg"><i class="bi bi-folder2-open"></i></div>
            <div class="small text-uppercase fw-bold text-white-50" style="letter-spacing: 0.5px;">Total Grievances</div>
            <div class="display-6 fw-bold mt-1 mb-2">{{ $metrics['total'] }}</div>
            <a href="{{ route('complaints.index') }}" class="small text-white text-decoration-none fw-semibold">
                Browse All Directory <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Pending Complaints -->
    <div class="col-sm-6 col-lg-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
            <div class="stats-icon-bg"><i class="bi bi-clock-history"></i></div>
            <div class="small text-uppercase fw-bold text-white-50" style="letter-spacing: 0.5px;">Pending Action</div>
            <div class="display-6 fw-bold mt-1 mb-2">{{ $metrics['pending'] }}</div>
            <a href="{{ route('complaints.index', ['status' => 'Pending']) }}" class="small text-white text-decoration-none fw-semibold">
                Review Awaiting <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-sm-6 col-lg-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
            <div class="stats-icon-bg"><i class="bi bi-gear-wide-connected"></i></div>
            <div class="small text-uppercase fw-bold text-white-50" style="letter-spacing: 0.5px;">In Progress</div>
            <div class="display-6 fw-bold mt-1 mb-2">{{ $metrics['in_progress'] }}</div>
            <a href="{{ route('complaints.index', ['status' => 'In Progress']) }}" class="small text-white text-decoration-none fw-semibold">
                Track Workflows <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Resolved Complaints -->
    <div class="col-sm-6 col-lg-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
            <div class="stats-icon-bg"><i class="bi bi-check2-circle"></i></div>
            <div class="small text-uppercase fw-bold text-white-50" style="letter-spacing: 0.5px;">Resolved</div>
            <div class="display-6 fw-bold mt-1 mb-2">{{ $metrics['resolved'] }}</div>
            <a href="{{ route('complaints.index', ['status' => 'Resolved']) }}" class="small text-white text-decoration-none fw-semibold">
                Resolved Archives <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Department Breakdown -->
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-custom-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <h6 class="mb-0 fw-bold">Department-Wise Workload</h6>
                </div>
                <a href="{{ route('departments.index') }}" class="small text-decoration-none fw-semibold">Manage</a>
            </div>

            <div class="card-body p-4">
                @forelse($departments as $dept)
                    @php
                        $percentage = $metrics['total'] > 0 ? round(($dept->complaints_count / $metrics['total']) * 100) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="fw-bold text-dark">{{ $dept->name }}</span>
                            <span class="text-muted fw-semibold">
                                {{ $dept->complaints_count }} tickets ({{ $percentage }}%)
                            </span>
                        </div>
                        <div class="progress" style="height: 7px; border-radius: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">No departments registered.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Complaints Table -->
    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-custom-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <h6 class="mb-0 fw-bold">Recent Influx of Complaints</h6>
                </div>
                <a href="{{ route('complaints.index') }}" class="small text-decoration-none fw-semibold">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Student</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentComplaints as $complaint)
                            <tr>
                                <td>
                                    <a href="{{ route('complaints.show', $complaint) }}" class="font-monospace fw-bold text-decoration-none text-primary">
                                        {{ $complaint->reference_no }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $complaint->user->name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small">{{ $complaint->department->name }}</span>
                                </td>
                                <td>
                                    <x-status-badge :status="$complaint->status" />
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="View details">
                                        <i class="bi bi-arrow-right-short fs-6"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">No complaints registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
