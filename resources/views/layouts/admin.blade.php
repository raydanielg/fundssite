<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .app-main .app-content { padding-top: 0.75rem !important; }
            .app-main .container-fluid { padding-left: 1rem; padding-right: 1rem; }
            .app-sidebar .sidebar-wrapper { padding-bottom: 1rem; }
            .app-sidebar .nav.sidebar-menu .nav-link { padding-top: 0.55rem; padding-bottom: 0.55rem; }
            .app-sidebar .nav-header { margin-bottom: 0.5rem !important; }
        </style>
    </head>
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <div class="wrapper">
            <nav class="app-header navbar navbar-expand bg-body shadow-sm">
                <div class="container-fluid">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i class="bi bi-list fs-4"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-md-block ms-2">
                            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> View Site
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                                <div class="bg-primary-subtle rounded-circle p-1 me-2">
                                    <i class="bi bi-person-fill text-primary"></i>
                                </div>
                                <span class="d-none d-md-inline fw-semibold text-dark">{{ auth()->user()->name ?? 'Admin' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-lg border-0 mt-2">
                                <li class="user-header bg-primary text-white p-4 text-center rounded-top">
                                    <i class="bi bi-person-circle fs-1 shadow-sm"></i>
                                    <p class="mt-3 mb-0 fw-bold fs-5">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <small class="opacity-75">Administrator since {{ auth()->user()->created_at->format('M. Y') }}</small>
                                </li>
                                <li class="user-footer p-3 d-flex justify-content-between bg-light rounded-bottom">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-white border btn-sm px-3 fw-bold">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <aside class="app-sidebar bg-dark shadow-lg" data-bs-theme="dark">
                <div class="sidebar-brand p-3 border-bottom border-white border-opacity-10">
                    <a href="{{ url('/admin') }}" class="brand-link text-decoration-none d-flex align-items-center">
                        <div class="bg-primary rounded-3 p-1 me-2 shadow-sm">
                            <i class="bi bi-shield-lock-fill text-white fs-5"></i>
                        </div>
                        <span class="brand-text fw-bold text-white letter-spacing-1">ADMIN</span>
                    </a>
                </div>

                <div class="sidebar-wrapper">
                    <div class="px-3 py-3">
                        <div class="input-group">
                            <input type="text" class="form-control bg-secondary border-0 text-white small" placeholder="Search..." aria-label="Search">
                            <button class="btn btn-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <nav class="mt-1 px-2">
                        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                            <li class="nav-header text-white-50 small fw-bold text-uppercase ps-3 mb-2">MAIN</li>
                            <li class="nav-item">
                                <a href="{{ url('/admin') }}" class="nav-link py-2 rounded-3 mb-1 {{ request()->is('admin') ? 'active bg-primary shadow-sm text-white' : 'text-white-50' }}">
                                    <i class="nav-icon bi bi-speedometer2 me-2"></i>
                                    <p class="fw-medium">Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/transactions') }}" class="nav-link py-2 rounded-3 mb-1 {{ request()->is('admin/transactions') ? 'active bg-primary shadow-sm text-white' : 'text-white-50' }}">
                                    <i class="nav-icon bi bi-credit-card-2-front me-2"></i>
                                    <p class="fw-medium">Payments</p>
                                </a>
                            </li>

                            <li class="nav-header mt-3 mb-2 text-white-50 small fw-bold text-uppercase ps-3">MANAGEMENT</li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/fundraiser') }}" class="nav-link py-2 rounded-3 mb-1 {{ request()->is('admin/fundraiser') ? 'active bg-primary shadow-sm text-white' : 'text-white-50' }}">
                                    <i class="nav-icon bi bi-gear me-2"></i>
                                    <p class="fw-medium">Fundraiser Settings</p>
                                </a>
                            </li>

                            <li class="nav-header mt-3 mb-2 text-white-50 small fw-bold text-uppercase ps-3">ACCOUNT</li>

                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}" class="nav-link py-2 rounded-3 mb-1 {{ request()->is('profile') ? 'active bg-primary shadow-sm text-white' : 'text-white-50' }}">
                                    <i class="nav-icon bi bi-person me-2"></i>
                                    <p class="fw-medium">Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/') }}" class="nav-link py-2 rounded-3 mb-1 text-white-50">
                                    <i class="nav-icon bi bi-house me-2"></i>
                                    <p class="fw-medium">View Website</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <main class="app-main bg-light">
                <div class="app-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            </main>

            <footer class="app-footer py-4 bg-white border-top text-center">
                <div class="container-fluid">
                    <p class="text-muted small mb-0">
                        <strong>&copy; {{ date('Y') }} {{ config('app.name') }}</strong> · Professional Fundraising Dashboard
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
