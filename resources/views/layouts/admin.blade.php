<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
            :root { --admin-sidebar-w: 280px; }
            body { background: #f9fafb; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
            .admin-shell { min-height: 100vh; }
            .admin-sidebar { width: var(--admin-sidebar-w); height: 100vh; position: sticky; top: 0; background: #ffffff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow-y: auto; }
            .admin-sidebar::-webkit-scrollbar { width: 4px; }
            .admin-sidebar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
            .admin-content { min-width: 0; display: flex; flex-direction: column; min-height: 100vh; background: #f9fafb; }
            .admin-main { padding: 24px; flex-grow: 1; }
            
            /* Sidebar Modern UI */
            .sidebar-user { padding: 20px 16px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: background 0.2s; text-decoration: none; border-bottom: 1px solid #f3f4f6; margin-bottom: 16px; width: 100%; border-left: none; border-right: none; border-top: none; background: transparent; }
            .sidebar-user:hover { background: #f9fafb; }
            .user-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 0 1px #e5e7eb; }
            .user-info { flex-grow: 1; min-width: 0; text-align: left; }
            .user-name { font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .user-email { font-size: 0.75rem; color: #6b7280; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .user-chevron { color: #9ca3af; font-size: 0.85rem; }

            .sidebar-search { padding: 0 16px 16px; }
            .search-box { position: relative; }
            .search-box input { width: 100%; background: #f3f4f6; border: 1px solid transparent; border-radius: 10px; padding: 10px 12px 10px 40px; font-size: 0.875rem; transition: all 0.2s; color: #111827; }
            .search-box input::placeholder { color: #9ca3af; }
            .search-box input:focus { background: #ffffff; border-color: #3b82f6; outline: none; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
            .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1rem; }

            .side-link { display: flex; align-items: center; gap: 12px; padding: 10px 12px; color: #4b5563; font-size: 0.9375rem; font-weight: 500; text-decoration: none; border-radius: 10px; margin: 2px 12px; transition: all 0.2s; }
            .side-link i { font-size: 1.25rem; color: #6b7280; transition: color 0.2s; }
            .side-link:hover { background: #f3f4f6; color: #111827; }
            .side-link:hover i { color: #111827; }
            .side-link.active { background: #f3f4f6; color: #111827; font-weight: 600; }
            .side-link.active i { color: #111827; }

            .side-section { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em; margin: 24px 16px 8px; padding-left: 12px; display: flex; align-items: center; justify-content: space-between; }
            .section-add { color: #9ca3af; cursor: pointer; transition: color 0.2s; font-size: 1rem; }
            .section-add:hover { color: #4b5563; }

            .admin-topbar { height: 64px; background: #ffffff !important; border-bottom: 1px solid #e5e7eb; padding: 0 24px; }
            footer { background: #ffffff !important; border-top: 1px solid #e5e7eb; padding: 20px 0; }
        </style>
    </head>
    <body>
        <div class="admin-shell d-flex">
            <aside class="admin-sidebar d-none d-lg-flex">
                <button class="sidebar-user dropdown-toggle border-0" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=random" class="user-avatar" alt="Avatar">
                    <div class="user-info">
                        <p class="user-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="user-email">{{ auth()->user()->email ?? 'admin@fundssite.com' }}</p>
                    </div>
                    <i class="bi bi-chevron-expand user-chevron"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; width: 240px;">
                    <li><a class="dropdown-item small py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item small py-2" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item small py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                        </form>
                    </li>
                </ul>

                <div class="sidebar-search">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" placeholder="Search projects">
                    </div>
                </div>

                <nav class="flex-grow-1">
                    <a class="side-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                        <i class="bi bi-activity"></i> Activity
                    </a>
                    <a class="side-link {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                        <i class="bi bi-person-lines-fill"></i> Contacts
                    </a>
                    <a class="side-link {{ request()->is('admin/tasks') ? 'active' : '' }}" href="#">
                        <i class="bi bi-clipboard-check"></i> Tasks
                    </a>

                    <div class="side-section">
                        <span>Collections</span>
                        <i class="bi bi-plus-circle-fill section-add"></i>
                    </div>
                    
                    <a class="side-link {{ request()->is('admin/transactions') ? 'active' : '' }}" href="{{ url('/admin/transactions') }}">
                        <i class="bi bi-cart3"></i> Sales
                    </a>
                    <a class="side-link" href="#">
                        <i class="bi bi-palette"></i> Design
                    </a>
                    <a class="side-link" href="{{ url('/admin/fundraiser') }}">
                        <i class="bi bi-megaphone"></i> Fundraising
                    </a>
                    <a class="side-link" href="#">
                        <i class="bi bi-display"></i> Internal
                    </a>
                    <a class="side-link" href="#">
                        <i class="bi bi-lightbulb"></i> Customer Success
                    </a>
                    <a class="side-link" href="#">
                        <i class="bi bi-people"></i> Networking
                    </a>
                    <a class="side-link" href="#">
                        <i class="bi bi-journal-bookmark"></i> Legal
                    </a>
                    
                    <a class="side-link mt-2 text-muted" href="#">
                        <i class="bi bi-plus-lg"></i> Add collection
                    </a>
                </nav>
            </aside>

            <div class="admin-content flex-grow-1 d-flex flex-column">
                <nav class="admin-topbar navbar navbar-expand bg-white border-bottom">
                    <div class="container-fluid px-4">
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                            <i class="bi bi-list"></i>
                        </button>

                        <div class="d-none d-md-flex align-items-center text-muted small">
                            <span class="fw-semibold text-dark fs-6">@yield('page_title', 'Dashboard')</span>
                        </div>

                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item me-3">
                                <button class="btn btn-link text-secondary p-0 position-relative">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                </button>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link d-flex align-items-center gap-2 p-0" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=random" class="rounded-circle border" width="32" height="32" alt="Profile">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px;">
                                    <li class="px-3 py-2 border-bottom mb-1">
                                        <p class="small fw-bold mb-0 text-dark">{{ auth()->user()->name ?? 'Admin' }}</p>
                                        <p class="x-small text-muted mb-0">{{ auth()->user()->email ?? 'admin@fundssite.com' }}</p>
                                    </li>
                                    <li><a class="dropdown-item small" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                                    <li><a class="dropdown-item small" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item small text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
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

        <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel" style="width: var(--admin-sidebar-w);">
            <div class="offcanvas-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=random" class="user-avatar" width="32" height="32" alt="Avatar">
                    <span class="fw-bold small">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="py-3 px-2">
                    <div class="sidebar-search mb-3">
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" placeholder="Search projects">
                        </div>
                    </div>

                    <nav>
                        <a class="side-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                            <i class="bi bi-activity"></i> Activity
                        </a>
                        <a class="side-link {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                            <i class="bi bi-person-lines-fill"></i> Contacts
                        </a>
                        <a class="side-link" href="#"><i class="bi bi-clipboard-check"></i> Tasks</a>

                        <div class="side-section">Collections</div>
                        <a class="side-link {{ request()->is('admin/transactions') ? 'active' : '' }}" href="{{ url('/admin/transactions') }}">
                            <i class="bi bi-cart3"></i> Sales
                        </a>
                        <a class="side-link" href="{{ url('/admin/fundraiser') }}">
                            <i class="bi bi-megaphone"></i> Fundraising
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
