@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card card-custom p-4 mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #ffffff;">
    <div class="row align-items-center">
        <div class="col-md-8">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill mb-2 fw-semibold">
                Student Grievance Portal
            </span>
            <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-white-50 small mb-0">
                You can easily register issues regarding IT labs, campus maintenance, academics, library, or hostel facilities and track resolutions in real-time.
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('complaints.create') }}" class="btn btn-light px-4 py-2 fw-bold text-primary shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Register New Complaint
            </a>
        </div>
    </div>
</div>

<!-- Personal Stats Counters -->
<div class="row g-3 mb-4">
    <!-- Total Submitted -->
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 border-start border-4 border-dark h-100">
            <div class="small text-secondary fw-semibold text-uppercase" style="font-size: 0.7rem;">Total Submitted</div>
            <div class="fs-2 fw-bold text-dark my-1">{{ $metrics['total'] }}</div>
            <a href="{{ route('complaints.index') }}" class="small text-decoration-none fw-semibold">View all &rarr;</a>
        </div>
    </div>

    <!-- Pending Review -->
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 border-start border-4 border-warning h-100">
            <div class="small text-secondary fw-semibold text-uppercase" style="font-size: 0.7rem;">Pending Review</div>
            <div class="fs-2 fw-bold text-warning-emphasis my-1">{{ $metrics['pending'] }}</div>
            <span class="small text-muted">Awaiting department staff</span>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 border-start border-4 border-primary h-100">
            <div class="small text-secondary fw-semibold text-uppercase" style="font-size: 0.7rem;">Under Action</div>
            <div class="fs-2 fw-bold text-primary my-1">{{ $metrics['in_progress'] }}</div>
            <span class="small text-muted">Active review & repair</span>
        </div>
    </div>

    <!-- Resolved -->
    <div class="col-6 col-lg-3">
        <div class="card card-custom p-3 border-start border-4 border-success h-100">
            <div class="small text-secondary fw-semibold text-uppercase" style="font-size: 0.7rem;">Resolved</div>
            <div class="fs-2 fw-bold text-success my-1">{{ $metrics['resolved'] }}</div>
            <span class="small text-muted">Successfully closed</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Complaints Table -->
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-custom-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <h6 class="mb-0 fw-bold">My Recent Complaints</h6>
                </div>
                <a href="{{ route('complaints.index') }}" class="small text-decoration-none fw-semibold">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Department</th>
                            <th>Title</th>
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
                                    <span class="badge bg-light text-dark border small">{{ $complaint->department->name }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-truncate text-dark" style="max-width: 250px;">
                                        {{ $complaint->title }}
                                    </div>
                                </td>
                                <td>
                                    <x-status-badge :status="$complaint->status" />
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-outline-primary" title="View details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @can('update', $complaint)
                                            <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-outline-secondary" title="Edit pending ticket">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="bi bi-chat-square-text fs-1"></i></div>
                                    <h6 class="fw-bold text-secondary">You haven't filed any complaints yet</h6>
                                    <p class="small text-muted mb-3">Notice an issue with lab equipment, hostel, or academics? Submit a ticket anytime.</p>
                                    <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-brand">
                                        <i class="bi bi-plus-lg me-1"></i> Submit Complaint
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Student Support & FAQs -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-custom-header d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill text-primary"></i>
                <h6 class="mb-0 fw-bold">Campus Guidelines</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3 pb-3 border-bottom">
                    <h6 class="fw-bold small mb-1 text-dark">
                        <i class="bi bi-1-circle-fill text-primary me-1"></i> Transparent Tracking
                    </h6>
                    <p class="small text-muted mb-0">Every complaint is assigned a permanent reference number that cannot be altered or removed once reviewed.</p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <h6 class="fw-bold small mb-1 text-dark">
                        <i class="bi bi-2-circle-fill text-primary me-1"></i> Modification Rules
                    </h6>
                    <p class="small text-muted mb-0">You may update or delete your complaint only while it is in <strong>Pending</strong> status. Once under active review, editing is locked.</p>
                </div>
                <div>
                    <h6 class="fw-bold small mb-1 text-dark">
                        <i class="bi bi-3-circle-fill text-primary me-1"></i> Emergency Grievances
                    </h6>
                    <p class="small text-muted mb-0">For fire, power outage, or medical emergencies, please directly contact campus security control room immediately.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
