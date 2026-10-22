@extends('layouts.app')

@section('title', 'Submit Complaint')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}" class="text-decoration-none">My Complaints</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Ticket</li>
            </ol>
        </nav>

        <div class="card card-custom">
            <div class="card-custom-header d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-plus text-primary fs-5"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Submit a New Complaint</h5>
                    <small class="text-muted">Fill out the details below to register your grievance with the appropriate department</small>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('complaints.store') }}" novalidate>
                    @csrf

                    <div class="row g-3 mb-3">
                        <!-- Department Selection -->
                        <div class="col-md-7">
                            <label for="department_id" class="form-label fw-semibold small text-secondary">
                                Target Department <span class="text-danger">*</span>
                            </label>
                            <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">-- Choose Relevant Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priority Selection -->
                        <div class="col-md-5">
                            <label for="priority" class="form-label fw-semibold small text-secondary">
                                Severity / Priority <span class="text-danger">*</span>
                            </label>
                            <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                @foreach($priorities as $pr)
                                    <option value="{{ $pr }}" {{ old('priority', 'Medium') === $pr ? 'selected' : '' }}>
                                        {{ $pr }} Priority
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Complaint Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold small text-secondary">
                            Complaint Subject / Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            id="title" 
                            name="title" 
                            class="form-control @error('title') is-invalid @enderror" 
                            value="{{ old('title') }}" 
                            required 
                            placeholder="e.g. Laboratory terminal 5 monitor display issue during practicals">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text small text-muted">A clear, concise summary of the issue (up to 150 characters).</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-secondary">
                            Detailed Description <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="6" 
                            class="form-control @error('description') is-invalid @enderror" 
                            required 
                            placeholder="Please provide full details including room numbers, equipment IDs, exact dates/times, and steps to reproduce the issue...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Policy Notice -->
                    <div class="alert alert-light border small text-muted mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success fs-5"></i>
                        <div>
                            Once submitted, your complaint will be assigned a unique tracking number (e.g. <code>CMP-{{ date('Y') }}-0001</code>). You may edit or withdraw your complaint while it remains in <strong>Pending</strong> status.
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-brand px-4">
                            <i class="bi bi-send-fill me-1"></i> Submit Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
