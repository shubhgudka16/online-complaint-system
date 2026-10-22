@extends('layouts.app')

@section('title', 'Add Department')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}" class="text-decoration-none">Departments</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New</li>
            </ol>
        </nav>

        <div class="card card-custom">
            <div class="card-custom-header d-flex align-items-center gap-2">
                <i class="bi bi-building-add text-primary fs-5"></i>
                <h5 class="mb-0 fw-bold">Add New Department</h5>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('departments.store') }}" novalidate>
                    @csrf

                    <!-- Department Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small text-secondary">
                            Department Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            id="name" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            placeholder="e.g. Electrical & Electronics Maintenance">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text small text-muted">Must be unique across all departments.</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-secondary">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="form-control @error('description') is-invalid @enderror" 
                            placeholder="Describe the department's responsibilities, staff roles, and the types of complaints it handles...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-brand px-4">
                            <i class="bi bi-check2-circle me-1"></i> Save Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
