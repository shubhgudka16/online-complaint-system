@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="brand-icon mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h3 class="fw-bold mb-1">Welcome Back</h3>
                <p class="text-muted small">Sign in to manage and track your campus complaints</p>
            </div>

            <!-- Demo Quick-Fill Buttons -->
            <div class="p-3 mb-4 rounded-3 bg-light border">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px; font-size: 0.7rem;">
                        <i class="bi bi-magic me-1"></i> Quick Demo Logins
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">1-Click Fill</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger flex-fill py-1 fw-semibold" onclick="fillCredentials('admin@complaint.test', 'Password@123')">
                        <i class="bi bi-person-gear me-1"></i> Admin Demo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary flex-fill py-1 fw-semibold" onclick="fillCredentials('student@complaint.test', 'Password@123')">
                        <i class="bi bi-mortarboard me-1"></i> Student Demo
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small text-secondary">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="name@institution.edu">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" name="password" required placeholder="Enter your password">
                        <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('password', this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-secondary" for="remember">Remember me on this browser</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Account
                </button>

                <div class="text-center pt-2 border-top">
                    <p class="small text-muted mb-0">Don't have an account yet? 
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-primary">Register as Student</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

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
