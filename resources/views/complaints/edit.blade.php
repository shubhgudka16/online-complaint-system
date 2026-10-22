@extends('layouts.app')

@section('title', 'Edit Ticket #' . $complaint->reference_no)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}" class="text-decoration-none">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}" class="text-decoration-none">Complaints</a></li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.show', $complaint) }}" class="text-decoration-none">{{ $complaint->reference_no }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="card card-custom">
            <div class="card-custom-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary fs-5"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">Edit Complaint</h5>
                        <small class="text-muted">Ticket: <span class="font-monospace fw-bold">{{ $complaint->reference_no }}</span></small>
                    </div>
                </div>
                <x-status-badge :status="$complaint->status" />
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('complaints.update', $complaint) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <!-- Department Selection -->
                        <div class="col-md-7">
                            <label for="department_id" class="form-label fw-semibold small text-secondary">
                                Target Department <span class="text-danger">*</span>
                            </label>
                            <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $complaint->department_id) == $dept->id ? 'selected' : '' }}>
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
                                Priority Level <span class="text-danger">*</span>
                            </label>
                            <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                @foreach($priorities as $pr)
                                    <option value="{{ $pr }}" {{ old('priority', $complaint->priority) === $pr ? 'selected' : '' }}>
                                        {{ $pr }} Priority
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold small text-secondary">
                            Complaint Subject / Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            id="title" 
                            name="title" 
                            class="form-control @error('title') is-invalid @enderror" 
                            value="{{ old('title', $complaint->title) }}" 
                            required 
                            placeholder="e.g. Broken bench in room 204">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            required>{{ old('description', $complaint->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-brand px-4">
                            <i class="bi bi-check2-circle me-1"></i> Update Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
