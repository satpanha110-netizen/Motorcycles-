<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — ហាងលក់ម៉ូតូ​ រិទ្ធស្រីដា
</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}?v={{ filemtime(public_path('assets/css/app.css')) }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

{{-- Top bar --}}
<nav class="navbar navbar-dark bg-dark-navy sticky-top py-0 dash-topbar">
    <div class="container-fluid px-3">
        <button class="btn btn-sm btn-outline-light d-lg-none me-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarNav" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>

        <span class="navbar-brand mb-0 h5 fw-bold text-capitalize">{{ auth()->user()->role }} Panel</span>

        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="{{ route('home') }}" class="text-decoration-none text-white-50 small" target="_blank">
                <i class="bi bi-globe2 me-1"></i>View Site
            </a>
            <div class="dropdown">
                <a class="d-flex align-items-center gap-2 text-white text-decoration-none" href="#"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    @if(auth()->user()->profile_image)
                        <img src="{{ StorageHelper::url(auth()->user()->profile_image) }}" class="rounded-circle" width="34" height="34" style="object-fit: cover;" alt="">
                    @else
                        <span class="brand-logo-circle" style="width: 34px; height: 34px; font-size: .9rem; margin: 0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                    <span class="d-none d-md-inline small fw-semibold">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="d-flex flex-column flex-lg-row">
    <div class="collapse collapse-lg d-lg-block sidebar-wrap" id="sidebarNav">
        <x-dashboard-sidebar />
    </div>

    <main class="flex-grow-1 p-3 p-lg-4" style="min-width: 0; background-color: var(--mm-light);">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alert type="danger" :message="session('error')" />
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
@stack('scripts')
</body>
</html>
