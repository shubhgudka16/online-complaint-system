@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-6">
        <div class="card card-custom p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="brand-icon mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h3 class="fw-bold mb-1">Create Student Account</h3>
                <p class="text-muted small">Register to file campus grievances, track status updates, and view resolutions</p>
            </div>

            <div class="alert alert-light border small text-muted d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                <div>
                    <strong>Institutional Note:</strong> New registrations receive <strong>Student</strong> privileges. Administrative accounts are provisioned directly by institution administrators.
                </div>
            </div>

            <form method="POST" action="{{ route('register.store') }}" novalidate>
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small text-secondary">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Rahul Sharma">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small text-secondary">Campus / Personal Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="e.g. student@complaint.test">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small text-secondary">Password (Min. 8 characters)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" name="password" required placeholder="Choose a secure password">
                        <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('password', this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold small text-secondary">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                        <input id="password_confirmation" type="password" class="form-control border-start-0 border-end-0 ps-0" name="password_confirmation" required placeholder="Re-enter your password">
                        <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('password_confirmation', this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i> Register Student Account
                </button>

                <div class="text-center pt-2 border-top">
                    <p class="small text-muted mb-0">Already registered? 
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-primary">Sign In here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
