@extends('layouts.app')

@section('title', 'Ticket #' . $complaint->reference_no)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}" class="text-decoration-none">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}" class="text-decoration-none">Complaints</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $complaint->reference_no }}</li>
            </ol>
        </nav>

        <!-- Ticket Header Card -->
        <div class="card card-custom mb-4">
            <div class="p-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fs-4 fw-bold font-monospace text-primary">{{ $complaint->reference_no }}</span>
                        <x-status-badge :status="$complaint->status" />
                        <x-priority-badge :priority="$complaint->priority" />
                    </div>
                    <h4 class="fw-bold mb-1 text-dark">{{ $complaint->title }}</h4>
                    <div class="text-muted small">
                        Submitted on {{ $complaint->created_at->format('F d, Y \a\t h:i A') }} ({{ $complaint->created_at->diffForHumans() }})
                    </div>
                </div>

                <!-- Action buttons for authorized student/admin -->
                <div class="d-flex gap-2">
                    @can('update', $complaint)
                        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil-fill me-1"></i> Edit Complaint
                        </a>
                    @endcan
                    @can('delete', $complaint)
                        <button type="button" 
                            class="btn btn-outline-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            data-action="{{ route('complaints.destroy', $complaint) }}"
                            data-title="Delete Complaint #{{ $complaint->reference_no }}"
                            data-body="Are you sure you want to permanently delete this complaint ticket? This action cannot be reversed.">
                            <i class="bi bi-trash-fill me-1"></i> Delete Ticket
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Meta details row -->
            <div class="p-4 bg-light bg-opacity-50 border-bottom">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-3">
                        <small class="text-secondary fw-semibold text-uppercase d-block" style="font-size: 0.7rem;">Target Department</small>
                        <span class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="bi bi-building text-primary"></i> {{ $complaint->department->name }}
                        </span>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-secondary fw-semibold text-uppercase d-block" style="font-size: 0.7rem;">Student / Submitter</small>
                        <span class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle text-secondary"></i> {{ $complaint->user->name }}
                        </span>
                        <small class="text-muted d-block">{{ $complaint->user->email }}</small>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-secondary fw-semibold text-uppercase d-block" style="font-size: 0.7rem;">Priority Level</small>
                        <span class="fw-bold text-dark">{{ $complaint->priority }}</span>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <small class="text-secondary fw-semibold text-uppercase d-block" style="font-size: 0.7rem;">Last Updated</small>
                        <span class="text-dark">{{ $complaint->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Full Complaint Description -->
            <div class="p-4">
                <h6 class="fw-bold text-secondary text-uppercase small mb-3" style="letter-spacing: 0.5px;">
                    <i class="bi bi-card-text me-1 text-primary"></i> Complaint Details
                </h6>
                <div class="p-3 rounded-3 bg-light border text-dark" style="white-space: pre-wrap; line-height: 1.7;">{{ $complaint->description }}</div>
            </div>
        </div>

        <!-- Administrative Remarks & Status Resolution Section -->
        @if($complaint->admin_remarks)
            <div class="card card-custom mb-4 border-primary-subtle shadow-sm">
                <div class="card-custom-header bg-primary-subtle bg-opacity-25 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-chat-left-quote-fill fs-5"></i>
                    <h6 class="mb-0 fw-bold">Official Department / Administrative Remarks</h6>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.6;">{{ $complaint->admin_remarks }}</p>
                </div>
            </div>
        @endif

        <!-- Admin Management Controls (Status Transition & Remarks) -->
        @if(Auth::user()->isAdmin())
            <div class="card card-custom border-primary shadow-sm mb-4">
                <div class="card-custom-header bg-light d-flex align-items-center gap-2">
                    <i class="bi bi-gear-wide-connected text-primary fs-5"></i>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Administrator Grievance Resolution Panel</h6>
                        <small class="text-muted">Update ticket workflow status and communicate feedback back to the student</small>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('complaints.status', $complaint) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label for="status" class="form-label fw-semibold small text-secondary">
                                    Ticket Status <span class="text-danger">*</span>
                                </label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" {{ old('status', $complaint->status) === $st ? 'selected' : '' }}>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <label for="admin_remarks" class="form-label fw-semibold small text-secondary">
                                    Administrative Action / Resolution Remarks
                                </label>
                                <textarea id="admin_remarks" 
                                    name="admin_remarks" 
                                    rows="3" 
                                    class="form-control @error('admin_remarks') is-invalid @enderror" 
                                    placeholder="Explain steps taken, technician assigned, or reason for resolution/rejection...">{{ old('admin_remarks', $complaint->admin_remarks) }}</textarea>
                                @error('admin_remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Status & Save Remarks
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Student Status Notice -->
        @if(Auth::user()->isStudent() && !$complaint->isPending())
            <div class="alert alert-secondary border d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-lock-fill fs-5 text-secondary"></i>
                <div class="small">
                    <strong>Notice:</strong> This complaint is currently marked as <strong>{{ $complaint->status }}</strong>. To ensure audit integrity, student modifications and deletions are disabled once review has commenced.
                </div>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Complaints List
            </a>
        </div>
    </div>
</div>
@endsection
