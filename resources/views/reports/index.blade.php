@extends('layouts.app')

@section('title', 'Campus Grievance Reports & Analytics')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-graph-up-arrow text-primary me-2"></i>Campus Grievance Reports & Analytics
        </h2>
        <p class="text-muted small mb-0">Aggregate statistics, resolution metrics, and audit logs across all academic & residential departments</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-dark" onclick="window.print()">
            <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
        </button>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-custom p-3 p-md-4 mb-4">
    <form method="GET" action="{{ route('admin.reports') }}" class="row g-2 align-items-end">
        <!-- Department Filter -->
        <div class="col-sm-6 col-md-3">
            <label class="form-label fw-semibold small text-secondary">Department</label>
            <select name="department_id" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ (string) request('department_id') === (string) $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="col-sm-6 col-md-2">
            <label class="form-label fw-semibold small text-secondary">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <!-- Priority Filter -->
        <div class="col-sm-6 col-md-2">
            <label class="form-label fw-semibold small text-secondary">Priority</label>
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                @foreach($priorities as $pr)
                    <option value="{{ $pr }}" {{ request('priority') === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                @endforeach
            </select>
        </div>

        <!-- Date Range: From -->
        <div class="col-sm-6 col-md-2">
            <label class="form-label fw-semibold small text-secondary">From Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <!-- Date Range: To -->
        <div class="col-sm-6 col-md-2">
            <label class="form-label fw-semibold small text-secondary">To Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <!-- Action Buttons -->
        <div class="col-sm-6 col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-primary flex-fill" title="Filter Report">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if(request()->hasAny(['department_id', 'status', 'priority', 'start_date', 'end_date']))
                <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary" title="Reset Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- KPI Summary Highlights -->
@php
    $resolutionRate = $reportMetrics['total'] > 0 
        ? round(($reportMetrics['resolved'] / $reportMetrics['total']) * 100, 1) 
        : 0;
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 bg-light border-0">
            <span class="small text-secondary fw-semibold text-uppercase">Records In Scope</span>
            <div class="fs-2 fw-bold text-dark mt-1">{{ $reportMetrics['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 bg-light border-0">
            <span class="small text-secondary fw-semibold text-uppercase">Resolution Rate</span>
            <div class="fs-2 fw-bold text-success mt-1">{{ $resolutionRate }}%</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 bg-light border-0">
            <span class="small text-secondary fw-semibold text-uppercase">Pending Backlog</span>
            <div class="fs-2 fw-bold text-warning-emphasis mt-1">{{ $reportMetrics['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 bg-light border-0">
            <span class="small text-secondary fw-semibold text-uppercase">Under Action</span>
            <div class="fs-2 fw-bold text-primary mt-1">{{ $reportMetrics['in_progress'] }}</div>
        </div>
    </div>
</div>

<!-- Department-Wise Matrix Breakdown Table -->
<div class="card card-custom mb-4 overflow-hidden">
    <div class="card-custom-header d-flex align-items-center gap-2">
        <i class="bi bi-table text-primary"></i>
        <h6 class="mb-0 fw-bold">Department Performance & Resolution Matrix</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th class="text-center" style="width: 100px;">Total</th>
                    <th class="text-center" style="width: 110px;">Pending</th>
                    <th class="text-center" style="width: 120px;">In Progress</th>
                    <th class="text-center" style="width: 110px;">Resolved</th>
                    <th class="text-center" style="width: 110px;">Rejected</th>
                    <th class="text-center" style="width: 140px;">Resolution Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departmentBreakdown as $dept)
                    @php
                        $deptRate = $dept->complaints_count > 0 
                            ? round(($dept->resolved_count / $dept->complaints_count) * 100, 1) 
                            : 0;
                    @endphp
                    <tr>
                        <td class="fw-bold text-dark">{{ $dept->name }}</td>
                        <td class="text-center fw-bold">{{ $dept->complaints_count }}</td>
                        <td class="text-center text-warning-emphasis fw-semibold">{{ $dept->pending_count }}</td>
                        <td class="text-center text-primary fw-semibold">{{ $dept->in_progress_count }}</td>
                        <td class="text-center text-success fw-semibold">{{ $dept->resolved_count }}</td>
                        <td class="text-center text-danger fw-semibold">{{ $dept->rejected_count }}</td>
                        <td class="text-center">
                            <span class="badge {{ $deptRate >= 50 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }} border px-2 py-1 rounded-pill">
                                {{ $deptRate }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Detailed Filtered Complaints Log -->
<div class="card card-custom overflow-hidden">
    <div class="card-custom-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-card-checklist text-primary"></i>
            <h6 class="mb-0 fw-bold">Complaints Audit Log (Filtered Records)</h6>
        </div>
        <span class="badge bg-light text-secondary border">
            Showing {{ $filteredComplaints->count() }} of {{ $filteredComplaints->total() }} records
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 130px;">Ref #</th>
                    <th>Complainant</th>
                    <th>Department</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredComplaints as $complaint)
                    <tr>
                        <td>
                            <a href="{{ route('complaints.show', $complaint) }}" class="font-monospace fw-bold text-decoration-none text-primary">
                                {{ $complaint->reference_no }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $complaint->user->name }}</div>
                            <small class="text-muted">{{ $complaint->user->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $complaint->department->name }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate text-dark" style="max-width: 280px;">{{ $complaint->title }}</div>
                        </td>
                        <td>
                            <x-priority-badge :priority="$complaint->priority" />
                        </td>
                        <td>
                            <x-status-badge :status="$complaint->status" />
                        </td>
                        <td class="small text-muted">{{ $complaint->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">No complaints found for the selected report filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($filteredComplaints->hasPages())
        <div class="card-footer bg-transparent border-top p-3">
            {{ $filteredComplaints->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
