@extends('layouts.app')

@section('title', 'Manage Departments')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-building text-primary me-2"></i>Department Management
        </h2>
        <p class="text-muted small mb-0">Configure academic, administrative, and hostel departments for complaint assignment</p>
    </div>
    <div>
        <a href="{{ route('departments.create') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg me-1"></i> Add Department
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('departments.index') }}" class="row g-2 align-items-center">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search departments by name or description..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if(request('search'))
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary" title="Clear search">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Departments Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th style="width: 160px;" class="text-center">Associated Complaints</th>
                    <th style="width: 140px;">Created Date</th>
                    <th style="width: 130px;" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    <tr>
                        <td class="text-muted small fw-bold">{{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $dept->name }}</div>
                        </td>
                        <td>
                            <div class="text-secondary small" style="max-width: 420px;">
                                {{ $dept->description ?: 'No detailed description provided.' }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($dept->complaints_count > 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-semibold">
                                    <i class="bi bi-card-text me-1"></i> {{ $dept->complaints_count }} {{ Str::plural('ticket', $dept->complaints_count) }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                    0 tickets
                                </span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $dept->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('departments.edit', $dept) }}" class="btn btn-outline-primary" title="Edit Department">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button type="button" 
                                    class="btn btn-outline-danger" 
                                    title="Delete Department"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-action="{{ route('departments.destroy', $dept) }}"
                                    data-title="Delete Department: {{ $dept->name }}"
                                    data-body="Are you sure you want to delete this department? Note: Deletion is strictly prevented if this department contains existing complaint records.">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-building-exclamation fs-1"></i></div>
                            <h5 class="fw-bold text-secondary">No departments found</h5>
                            <p class="small text-muted mb-3">Try adjusting your search criteria or add a new department.</p>
                            <a href="{{ route('departments.create') }}" class="btn btn-sm btn-brand">
                                <i class="bi bi-plus-lg me-1"></i> Add Department
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($departments->hasPages())
        <div class="card-footer bg-transparent border-top p-3">
            {{ $departments->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
