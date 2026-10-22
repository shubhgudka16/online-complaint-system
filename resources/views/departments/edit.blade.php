@extends('layouts.app')

@section('title', 'Edit Department - ' . $department->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}" class="text-decoration-none">Departments</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit #{{ $department->id }}</li>
            </ol>
        </nav>

        <div class="card card-custom">
            <div class="card-custom-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary fs-5"></i>
                    <h5 class="mb-0 fw-bold">Edit Department</h5>
                </div>
                <span class="badge bg-light text-secondary border">ID: #{{ $department->id }}</span>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('departments.update', $department) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Department Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small text-secondary">
                            Department Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            id="name" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name', $department->name) }}" 
                            required 
                            autofocus 
                            placeholder="e.g. Electrical & Electronics Maintenance">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-secondary">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="form-control @error('description') is-invalid @enderror" 
                            placeholder="Describe department responsibilities...">{{ old('description', $department->description) }}</textarea>
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
                            <i class="bi bi-check2-circle me-1"></i> Update Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
