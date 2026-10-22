@extends('layouts.app')

@section('title', Auth::user()->isAdmin() ? 'All Complaints' : 'My Complaints')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-card-checklist text-primary me-2"></i>
            {{ Auth::user()->isAdmin() ? 'All Complaints Directory' : 'My Complaints' }}
        </h2>
        <p class="text-muted small mb-0">
            {{ Auth::user()->isAdmin() 
                ? 'Review, prioritize, assign, and update student grievances across all campus departments' 
                : 'Track the real-time status and resolutions of your submitted campus grievances' }}
        </p>
    </div>
    @if(Auth::user()->isStudent())
        <div>
            <a href="{{ route('complaints.create') }}" class="btn btn-brand">
                <i class="bi bi-plus-lg me-1"></i> Submit New Complaint
            </a>
        </div>
    @endif
</div>

<!-- Search & Multi-Filter Bar -->
<div class="card card-custom p-3 p-md-4 mb-4">
    <form method="GET" action="{{ route('complaints.index') }}" class="row g-2 align-items-end">
        <!-- Search Keyword -->
        <div class="col-md-4">
            <label class="form-label fw-semibold small text-secondary">Search Keyword</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search ref#, title, description..." value="{{ request('search') }}">
            </div>
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

        <!-- Submit & Reset -->
        <div class="col-sm-6 col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-primary flex-fill" title="Apply Filters">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if(request()->hasAny(['search', 'status', 'department_id', 'priority']))
                <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Complaints Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 130px;">Reference #</th>
                    @if(Auth::user()->isAdmin())
                        <th style="width: 160px;">Complainant</th>
                    @endif
                    <th>Title & Subject</th>
                    <th style="width: 170px;">Department</th>
                    <th style="width: 110px;">Priority</th>
                    <th style="width: 130px;">Status</th>
                    <th style="width: 120px;">Submitted</th>
                    <th style="width: 110px;" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td>
                            <a href="{{ route('complaints.show', $complaint) }}" class="fw-bold text-decoration-none font-monospace text-primary">
                                {{ $complaint->reference_no }}
                            </a>
                        </td>
                        @if(Auth::user()->isAdmin())
                            <td>
                                <div class="fw-semibold text-dark">{{ $complaint->user->name }}</div>
                                <div class="small text-muted">{{ $complaint->user->email }}</div>
                            </td>
                        @endif
                        <td>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 320px;">
                                {{ $complaint->title }}
                            </div>
                            <div class="small text-muted text-truncate" style="max-width: 320px;">
                                {{ Str::limit($complaint->description, 60) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-building me-1"></i> {{ $complaint->department->name }}
                            </span>
                        </td>
                        <td>
                            <x-priority-badge :priority="$complaint->priority" />
                        </td>
                        <td>
                            <x-status-badge :status="$complaint->status" />
                        </td>
                        <td class="small text-muted">
                            {{ $complaint->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-outline-primary" title="View Ticket Details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                @can('update', $complaint)
                                    <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-outline-secondary" title="Edit Complaint">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                @endcan
                                @can('delete', $complaint)
                                    <button type="button" 
                                        class="btn btn-outline-danger" 
                                        title="Delete Complaint"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteConfirmModal"
                                        data-action="{{ route('complaints.destroy', $complaint) }}"
                                        data-title="Delete Complaint: {{ $complaint->reference_no }}"
                                        data-body="Are you sure you want to delete this complaint record?">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? '8' : '7' }}" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-inbox fs-1"></i></div>
                            <h5 class="fw-bold text-secondary">No complaints found</h5>
                            <p class="small text-muted mb-3">No complaint records match your active search and filter criteria.</p>
                            @if(Auth::user()->isStudent())
                                <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-brand">
                                    <i class="bi bi-plus-lg me-1"></i> Submit a Complaint
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
        <div class="card-footer bg-transparent border-top p-3">
            {{ $complaints->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
