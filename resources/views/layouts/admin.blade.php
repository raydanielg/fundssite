<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
            :root { --admin-sidebar-w: 260px; }
            body { background: #f2f4f7; }
            .admin-shell { min-height: 100vh; }
            .admin-sidebar { width: var(--admin-sidebar-w); }
            .admin-content { min-width: 0; }
            .admin-topbar { height: 58px; }
            .admin-main { padding: 18px; }
            @media (min-width: 992px) {
                .admin-main { padding: 22px; }
            }
            .admin-sidebar { background: linear-gradient(180deg, #0f7a38 0%, #0b4f25 100%); }
            .side-link { color: rgba(255,255,255,0.80); font-weight: 600; }
            .side-link:hover { color: rgba(255,255,255,0.95); background: rgba(255,255,255,0.08); }
            .side-link.active { color: #0b2f17; background: #a6f4c5; }
            .side-section { font-size: 0.70rem; letter-spacing: 0.10em; text-transform: uppercase; color: rgba(255,255,255,0.55); margin-top: 16px; margin-bottom: 8px; padding-left: 10px; }
            .brand-row { display:flex; align-items:center; gap:10px; padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.14); }
            .brand-badge { width: 34px; height: 34px; border-radius: 10px; background: rgba(255,255,255,0.14); display:flex; align-items:center; justify-content:center; }
            .brand-text { font-weight: 900; letter-spacing: 0.10em; }
            .side-collapse-toggle { display:flex; align-items:center; justify-content: space-between; gap: 10px; }
            .side-collapse-toggle .chev { opacity: 0.8; }
            .side-sub { padding-left: 10px; }
            .side-sub .side-link { font-weight: 600; opacity: 0.92; }
        </style>
    </head>
    <body>
        <div class="admin-shell d-flex">
            <aside class="admin-sidebar d-none d-lg-flex flex-column bg-dark text-white">
                <div class="brand-row">
                    <div class="brand-badge"><i class="bi bi-shield-check text-white"></i></div>
                    <div class="brand-text">ADMIN</div>
                </div>

                <div class="p-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control bg-secondary border-0 text-white" placeholder="Search..." aria-label="Search">
                        <button class="btn btn-secondary" type="button"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <nav class="px-2 pb-3">
                    <div class="side-section">Main</div>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/transactions') ? 'active' : '' }}" href="{{ url('/admin/transactions') }}">
                        <i class="bi bi-credit-card-2-front me-2"></i> Payments
                    </a>

                    <div class="side-section">Management</div>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 side-collapse-toggle" data-bs-toggle="collapse" href="#mgmtMenu" role="button" aria-expanded="true" aria-controls="mgmtMenu">
                        <span><i class="bi bi-sliders me-2"></i> Management</span>
                        <i class="bi bi-chevron-down chev"></i>
                    </a>
                    <div class="collapse show" id="mgmtMenu">
                        <div class="side-sub">
                            <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/fundraiser') ? 'active' : '' }}" href="{{ url('/admin/fundraiser') }}">
                                <i class="bi bi-gear me-2"></i> Fundraiser Settings
                            </a>
                            <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/transactions/manual') ? 'active' : '' }}" href="{{ url('/admin/transactions/manual') }}">
                                <i class="bi bi-plus-circle me-2"></i> Manual Donations
                            </a>
                            <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/expenses') ? 'active' : '' }}" href="{{ url('/admin/expenses') }}">
                                <i class="bi bi-receipt me-2"></i> Expenses
                            </a>
                        </div>
                    </div>

                    <div class="side-section">Account</div>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                    <a class="nav-link side-link rounded-3 px-3 py-2 mb-1" href="{{ url('/') }}">
                        <i class="bi bi-box-arrow-up-right me-2"></i> View Website
                    </a>
                </nav>
            </aside>

            <div class="admin-content flex-grow-1 d-flex flex-column">
                <nav class="admin-topbar navbar navbar-expand bg-white border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                            <i class="bi bi-list"></i>
                        </button>

                        <div class="d-none d-md-flex align-items-center ms-2 text-muted small">
                            <span class="fw-semibold text-dark">@yield('page_title', 'Dashboard')</span>
                        </div>

                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <span class="badge rounded-pill text-bg-light border me-2">{{ auth()->user()->name ?? 'Admin' }}</span>
                                    <i class="bi bi-person-circle fs-5"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Sign out</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="admin-main flex-grow-1">
                    @yield('content')
                </main>

                <footer class="py-3 bg-white border-top">
                    <div class="container-fluid">
                        <div class="text-muted small text-center">
                            <strong>&copy; {{ date('Y') }} {{ config('app.name') }}</strong>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="adminSidebarLabel">Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-2">
                <div class="px-2 pb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control bg-secondary border-0 text-white" placeholder="Search..." aria-label="Search">
                        <button class="btn btn-secondary" type="button"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div class="side-section">Main</div>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ url('/admin/users') }}"><i class="bi bi-people me-2"></i>Users</a>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/transactions') ? 'active' : '' }}" href="{{ url('/admin/transactions') }}"><i class="bi bi-credit-card-2-front me-2"></i>Payments</a>

                <div class="side-section">Management</div>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1" data-bs-toggle="collapse" href="#mgmtMenuMobile" role="button" aria-expanded="true" aria-controls="mgmtMenuMobile"><i class="bi bi-sliders me-2"></i>Management <span class="float-end"><i class="bi bi-chevron-down"></i></span></a>
                <div class="collapse show" id="mgmtMenuMobile">
                    <div class="side-sub">
                        <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/fundraiser') ? 'active' : '' }}" href="{{ url('/admin/fundraiser') }}"><i class="bi bi-gear me-2"></i>Fundraiser Settings</a>
                        <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/transactions/manual') ? 'active' : '' }}" href="{{ url('/admin/transactions/manual') }}"><i class="bi bi-plus-circle me-2"></i>Manual Donations</a>
                        <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('admin/expenses') ? 'active' : '' }}" href="{{ url('/admin/expenses') }}"><i class="bi bi-receipt me-2"></i>Expenses</a>
                    </div>
                </div>

                <div class="side-section">Account</div>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1 {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
                <a class="nav-link side-link rounded-3 px-3 py-2 mb-1" href="{{ url('/') }}">View Website</a>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
