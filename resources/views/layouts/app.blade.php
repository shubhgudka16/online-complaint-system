<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CampusVoice') - Online Complaint Registration System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-primary: #4f46e5;
            --brand-primary-hover: #4338ca;
            --brand-dark: #0f172a;
            --brand-surface: #f8fafc;
            --brand-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.25);
            padding: 0.85rem 1rem;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.25rem;
        }

        .brand-icon {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35);
        }

        .nav-link {
            font-weight: 500;
            color: #cbd5e1 !important;
            padding: 0.5rem 0.85rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .main-container {
            flex: 1 0 auto;
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom-header {
            background-color: transparent;
            border-bottom: 1px solid var(--brand-border);
            padding: 1.25rem 1.5rem;
            font-weight: 700;
        }

        .btn-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        .badge-pill-custom {
            padding: 0.35rem 0.75rem;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            letter-spacing: 0.3px;
        }

        .table-custom th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 2px solid var(--brand-border);
            padding: 0.9rem 1rem;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 0.9rem 1rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .footer-custom {
            background: #ffffff;
            border-top: 1px solid var(--brand-border);
            padding: 1.5rem 0;
            color: #64748b;
            font-size: 0.875rem;
        }

        .stats-card {
            border-radius: 14px;
            border: none;
            color: #ffffff;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stats-icon-bg {
            position: absolute;
            right: 1rem;
            bottom: -0.5rem;
            font-size: 4.5rem;
            opacity: 0.15;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header & Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="brand-icon"><i class="bi bi-shield-check"></i></span>
                <span>CampusVoice</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}" href="{{ route('complaints.index') }}">
                                    <i class="bi bi-card-checklist me-1"></i> All Complaints
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                                    <i class="bi bi-building me-1"></i> Departments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                                    <i class="bi bi-graph-up-arrow me-1"></i> Reports
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> My Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}" href="{{ route('complaints.create') }}">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Submit Complaint
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('complaints.index') ? 'active' : '' }}" href="{{ route('complaints.index') }}">
                                    <i class="bi bi-list-task me-1"></i> My Complaints
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-sm btn-outline-light px-3 py-1 fw-semibold" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Student Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white text-dark rounded-circle fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                                <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase" style="font-size: 0.65rem;">
                                    {{ Auth::user()->role }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown" style="border-radius: 12px; min-width: 200px;">
                                <li class="px-3 py-2 border-bottom">
                                    <small class="text-muted d-block">Signed in as</small>
                                    <div class="fw-bold text-truncate">{{ Auth::user()->email }}</div>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="p-1">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 rounded py-2">
                                            <i class="bi bi-box-arrow-right"></i> Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="main-container">
        <div class="container">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show card-custom border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                        <div><strong>Success:</strong> {{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show card-custom border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                        <div><strong>Error:</strong> {{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show card-custom border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                        <div>{{ session('info') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any() && !request()->routeIs('login') && !request()->routeIs('register'))
                <div class="alert alert-danger alert-dismissible fade show card-custom border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
                        <strong>Please resolve the following input errors:</strong>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Universal Confirmation Modal for Deletions -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteConfirmModalLabel">
                        <i class="bi bi-exclamation-octagon-fill"></i> Confirm Action
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-1 text-secondary" id="deleteModalBodyText">Are you sure you want to delete this record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteModalForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 fw-semibold" id="deleteModalSubmitBtn">
                            <i class="bi bi-trash-fill me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom mt-auto">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start mb-2 mb-md-0">
                    <span class="fw-bold text-dark">CampusVoice</span> — Online Complaint Registration System
                    <div class="small text-muted">Diploma in Computer Engineering — Major Project I</div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="bi bi-shield-lock-fill me-1"></i> Secured with Laravel & MariaDB
                    </span>
                    <span class="ms-2 small text-muted">&copy; {{ date('Y') }} All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Global Delete Modal Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const actionUrl = button.getAttribute('data-action');
                    const title = button.getAttribute('data-title');
                    const body = button.getAttribute('data-body');

                    const form = document.getElementById('deleteModalForm');
                    const modalTitle = document.getElementById('deleteConfirmModalLabel');
                    const modalBody = document.getElementById('deleteModalBodyText');

                    if (actionUrl) {
                        form.setAttribute('action', actionUrl);
                    }
                    if (title) {
                        modalTitle.innerHTML = '<i class="bi bi-exclamation-octagon-fill"></i> ' + title;
                    }
                    if (body) {
                        modalBody.innerText = body;
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
