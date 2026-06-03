<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <style>
            /* System Token Matrix definitions */
            :root {
                --system-font: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                --color-brand: #F15929;
                --color-brand-muted: #F7AB93;
                --color-dark: #231F20;
                --color-muted: #6c757d;
                --color-border: #e9ecef;
                --color-bg-main: #f8f9fa;
                --color-bg-card: #ffffff;
            }

            body {
                font-family: var(--system-font);
                background-color: var(--color-bg-main);
                color: var(--color-dark);
                letter-spacing: -0.01em;
                -webkit-font-smoothing: antialiased;
            }
            
            /* Navbar Elements Structure Overhaul */
            .navbar-brand img {
                max-height: 36px;
                width: auto;
            }
            
            .navbar {
                background-color: var(--color-bg-card) !important;
                border-bottom: 1px solid var(--color-border);
                padding: 0.75rem 1rem;
            }
            
            .navbar .nav-link {
                color: var(--color-dark);
                font-weight: 500;
                font-size: 0.925rem;
                padding: 0.5rem 1rem;
                border-radius: 4px;
                transition: color 0.15s ease, background-color 0.15s ease;
                display: inline-flex;
                align-items: center;
            }
            
            .navbar .nav-link i {
                color: var(--color-muted);
                width: 1.25rem;
                transition: color 0.15s ease;
            }
            
            .navbar .nav-link:hover {
                color: var(--color-brand);
                background-color: var(--color-bg-main);
            }

            .navbar .nav-link:hover i {
                color: var(--color-brand);
            }
            
            .navbar .nav-link.active {
                color: var(--color-brand);
                background-color: #fff0ec;
                font-weight: 600;
            }

            .navbar .nav-link.active i {
                color: var(--color-brand);
            }
            
            /* Dropdown Sub-menu Architecture */
            .dropdown-menu {
                border: 1px solid var(--color-border);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                border-radius: 6px;
                padding: 0.375rem;
                background-color: var(--color-bg-card);
            }
            
            .dropdown-item {
                color: var(--color-dark);
                font-size: 0.875rem;
                font-weight: 500;
                padding: 0.5rem 0.75rem;
                border-radius: 4px;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
            }
            
            .dropdown-item i {
                width: 1.5rem;
                color: var(--color-muted);
            }
            
            .dropdown-item:hover {
                background-color: var(--color-bg-main);
                color: var(--color-brand);
            }

            .dropdown-item:hover i {
                color: var(--color-brand);
            }
            
            /* Clean Platform Buttons (No Gradients) */
            .btn-primary {
                background-color: var(--color-brand);
                border-color: var(--color-brand);
                font-size: 0.875rem;
                font-weight: 600;
                border-radius: 4px;
                padding: 0.5rem 1rem;
            }
            
            .btn-primary:hover {
                background-color: #d4481d;
                border-color: #d4481d;
            }
            
            .btn-outline-primary {
                border-color: var(--color-brand);
                color: var(--color-brand);
                font-size: 0.875rem;
                font-weight: 600;
                border-radius: 4px;
                padding: 0.5rem 1rem;
            }
            
            .btn-outline-primary:hover {
                background-color: var(--color-brand);
                border-color: var(--color-brand);
                color: #FFFFFF;
            }
            
            /* Platform Header Components */
            .header-section {
                background-color: var(--color-bg-card);
                border-bottom: 1px solid var(--color-border);
            }
            
            .header-section h1, .header-section h2, .header-section h3 {
                color: var(--color-dark);
                font-weight: 700;
                letter-spacing: -0.02em;
            }
            
            /* Dashboard Data Tables Architecture */
            .table th {
                background-color: var(--color-bg-main);
                color: var(--color-dark);
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                border-bottom: 2px solid var(--color-border);
                padding: 0.75rem 1rem;
            }

            .table td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                vertical-align: middle;
            }
            
            .table-hover tbody tr:hover {
                background-color: var(--color-bg-main);
            }
            
            /* Clean Minimalist Badges */
            .badge {
                font-weight: 600;
                font-size: 0.75rem;
                padding: 0.35rem 0.5rem;
                border-radius: 4px;
                letter-spacing: 0.02em;
            }
            
            .badge-primary {
                background-color: #fff0ec;
                color: var(--color-brand);
            }
            
            .badge-secondary {
                background-color: var(--color-bg-main);
                color: var(--color-dark);
                border: 1px solid var(--color-border);
            }

            /* System Native Form Focus Inputs */
            .form-control, .form-select {
                border-radius: 4px;
                border-color: #ced4da;
                font-size: 0.875rem;
            }

            .form-control:focus, .form-select:focus {
                border-color: var(--color-brand-muted);
                box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
            }

            /* Pagination Component Layout */
            .pagination .page-link {
                color: var(--color-dark);
                font-size: 0.875rem;
                border-color: var(--color-border);
            }
            
            .pagination .page-item.active .page-link {
                background-color: var(--color-brand);
                border-color: var(--color-brand);
                color: #FFFFFF;
            }
            
            .pagination .page-link:hover {
                color: var(--color-brand);
                background-color: var(--color-bg-main);
            }

            /* Functional System Messages Alerts */
            .alert {
                border-radius: 4px;
                font-size: 0.9rem;
                border: 1px solid transparent;
            }

            .alert-danger {
                background-color: #fff5f5;
                border-color: #ffe3e3;
                color: #c92a2a;
            }
            
            .alert-info {
                background-color: #f8f9fa;
                border-color: var(--color-border);
                color: var(--color-dark);
            }
            
            .alert-success {
                background-color: #ebfbee;
                border-color: #d3f9d8;
                color: #2b8a3e;
            }
            
            /* Professional Dashboard Card Base */
            .card {
                border: 1px solid var(--color-border);
                border-radius: 6px;
                background-color: var(--color-bg-card);
                box-shadow: none;
                transition: border-color 0.15s ease;
            }
            
            .card:hover {
                border-color: #ced4da;
                box-shadow: none;
            }
            
            /* Footer Grid Section */
            footer {
                background-color: var(--color-bg-card);
                color: var(--color-muted);
                border-top: 1px solid var(--color-border);
                padding: 1.25rem 0;
                margin-top: auto;
            }

            .navbar-toggler {
                border-color: var(--color-border);
                padding: 0.25rem 0.5rem;
                border-radius: 4px;
            }
            
            .navbar-toggler:focus {
                box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
            }
        </style>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="min-vh-100 d-flex flex-column">
            @include('layouts.navigation')

            @isset($header)
                <header class="header-section py-3 mb-4">
                    <div class="container-fluid px-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-grow-1">
                <div class="container-fluid px-4">
                    
                    @auth
                    <nav class="navbar navbar-expand-lg rounded mb-4">
                        <div class="container-fluid p-0">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav me-auto g-1">
                                    @if(Auth::user()->role === 'admin')
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('admin.leave-types.*') ? 'active' : '' }}" href="{{ route('admin.leave-types.index') }}">
                                                <i class="fas fa-calendar-alt me-2"></i>Leave Types
                                            </a>
                                        </li>
                                    @elseif(Auth::user()->role === 'hr')
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.index') ? 'active' : '' }}" href="{{ route('hr.index') }}">
                                                <i class="fas fa-chart-line me-2"></i>Dashboard
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.employees.*') ? 'active' : '' }}" href="{{ route('hr.employees.index') }}">
                                                <i class="fas fa-user-tie me-2"></i>Employees
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.leave-requests.index') ? 'active' : '' }}" href="{{ route('hr.leave-requests.index') }}">
                                                <i class="fas fa-envelope-open-text me-2"></i>Leave Requests
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.leave-types.index') ? 'active' : '' }}" href="{{ route('hr.leave-types.index') }}">
                                                <i class="fas fa-tags me-2"></i>Leave Types
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.public-holidays.index') ? 'active' : '' }}" href="{{ route('hr.public-holidays.index') }}">
                                                <i class="fas fa-globe me-2"></i>Public Holidays
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('hr.leave-requests.calendar') ? 'active' : '' }}" href="{{ route('hr.leave-requests.calendar') }}">
                                                <i class="fas fa-calendar-week me-2"></i>Calendar
                                            </a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle px-3" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-chart-bar me-2"></i>Reports
                                            </a>
                                            <ul class="dropdown-menu mt-1">
                                                <li><a class="dropdown-item py-2" href="{{ route('hr.reports.index') }}">
                                                    <i class="fas fa-chart-pie me-2"></i>Reports Dashboard
                                                </a></li>
                                                <li><a class="dropdown-item py-2" href="{{ route('hr.reports.employee-summary') }}">
                                                    <i class="fas fa-file-alt me-2"></i>Employee Summary
                                                </a></li>
                                                <li><a class="dropdown-item py-2" href="{{ route('hr.reports.department-report') }}">
                                                    <i class="fas fa-building me-2"></i>Department Report
                                                </a></li>
                                            </ul>
                                        </li>
                                    @elseif(Auth::user()->role === 'employee')
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('employee.index') ? 'active' : '' }}" href="{{ route('employee.index') }}">
                                                <i class="fas fa-home me-2"></i>Dashboard
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('employee.leave-requests.create') ? 'active' : '' }}" href="{{ route('employee.leave-requests.create') }}">
                                                <i class="fas fa-paper-plane me-2"></i>Apply for Leave
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-3 {{ request()->routeIs('employee.leave-requests.index') ? 'active' : '' }}" href="{{ route('employee.leave-requests.index') }}">
                                                <i class="fas fa-history me-2"></i>My Leave History
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </nav>
                    @endauth
                    
                    @yield('content')
                </div>
            </main>
            
            <footer>
                <div class="container-fluid px-4 text-center">
                    <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
                </div>
            </footer>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>