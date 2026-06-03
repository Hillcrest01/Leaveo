@extends('layouts.app')

@section('content')
<style>
    /* Premium, market-ready design system tokens */
    :root {
        --system-font: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        --color-brand: #F15929;
        --color-dark: #231F20;
        --color-muted: #6c757d;
        --color-border: #e9ecef;
        --color-bg-light: #f8f9fa;
        --color-bg-card: #ffffff;
    }

    body, .dashboard-container {
        font-family: var(--system-font);
        color: var(--color-dark);
        letter-spacing: -0.01em;
        background-color: var(--color-bg-light);
    }

    /* Clean Card Layouts (No AI-like floating elements or gradients) */
    .dashboard-card {
        background-color: var(--color-bg-card);
        border: 1px solid var(--color-border);
        border-radius: 6px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-card:hover {
        border-color: #ced4da;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    /* Typography & Structure Updates */
    .dashboard-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-dark);
        letter-spacing: -0.02em;
    }

    .dashboard-subtitle {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        color: var(--color-muted);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: var(--color-dark);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--color-muted);
        font-weight: 500;
    }

    /* Modular Action Cards */
    .action-card {
        background-color: var(--color-bg-card);
        border: 1px solid var(--color-border);
        border-radius: 6px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.5rem;
    }

    .action-card .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff0ec;
        color: var(--color-brand);
        margin-bottom: 1.25rem;
    }

    .action-card.alt-wrapper .icon-wrapper {
        background-color: #f1f3f5;
        color: var(--color-dark);
    }

    .action-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--color-dark);
        margin-bottom: 0.5rem;
    }

    .action-desc {
        font-size: 0.9rem;
        color: var(--color-muted);
        margin-bottom: 1.5rem;
        line-height: 1.4;
    }

    /* Minimalist, Clean Buttons */
    .btn-action-link {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-brand);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        padding: 0;
        background: transparent;
        border: none;
        transition: color 0.2s ease;
    }

    .btn-action-link:hover {
        color: #d4481d;
    }

    .btn-action-link i {
        transition: transform 0.2s ease;
    }

    .btn-action-link:hover i {
        transform: translateX(4px);
    }

    .btn-action-link.dark-link {
        color: var(--color-dark);
    }
    
    .btn-action-link.dark-link:hover {
        color: var(--color-brand);
    }
</style>

<div class="container-fluid px-4 dashboard-container mt-4">
    {{-- Header Section --}}
    <div class="row align-items-center mb-4 pb-3 border-bottom">
        <div class="col-sm-6">
            <span class="dashboard-subtitle">Role: Human Resources</span>
            <h1 class="dashboard-title mt-1">Welcome, <span style="color: var(--color-brand);">{{ Auth::user()->name }}</span>!</h1>
        </div>
        <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
            {{-- <div class="text-muted small">System Portal</div> --}}
            <div class="font-weight-bold" style="font-weight:600; color: var(--color-dark);">HR Dashboard</div>
        </div>
    </div>

    {{-- Performance & Quick Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="p-4 dashboard-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Pending Requests</span>
                    {{-- <i class="fas fa-clock text-warning"></i> --}}
                </div>
                <div class="stat-number" id="pendingCount">0</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-4 dashboard-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Approved This Month</span>
                    {{-- <i class="fas fa-check-circle text-success"></i> --}}
                </div>
                <div class="stat-number" id="approvedCount">0</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-4 dashboard-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Total Employees</span>
                    <i class="fas fa-users" style="color: var(--color-dark);"></i>
                </div>
                <div class="stat-number" id="employeeCount">0</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-4 dashboard-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Leave Days Taken</span>
                    <i class="fas fa-chart-line text-info"></i>
                </div>
                <div class="stat-number" id="leaveDaysCount">0</div>
            </div>
        </div>
    </div>

    {{-- System Navigation Modules Grid --}}
    <div class="row g-3">
        {{-- Leave Requests --}}
        <div class="col-md-6 col-xl-4">
            <div class="action-card">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-envelope-open-text fw-bold"></i>
                    </div>
                    <h3 class="action-title">Leave Requests</h3>
                    <p class="action-desc">Review and manage leave requests</p>
                </div>
                <a href="{{ route('hr.leave-requests.index') }}" class="btn-action-link">
                    View requests <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        {{-- Employee List --}}
        <div class="col-md-6 col-xl-4">
            <div class="action-card alt-wrapper">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="action-title">Employee List</h3>
                    <p class="action-desc">View all employees and their leave balances</p>
                </div>
                <a href="{{ route('hr.employees.index') }}" class="btn-action-link dark-link">
                    View all <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        {{-- Leave Calendar --}}
        <div class="col-md-6 col-xl-4">
            <div class="action-card">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="action-title">Leave Calendar</h3>
                    <p class="action-desc">See who's on leave when</p>
                </div>
                <a href="{{ route('hr.leave-requests.calendar') }}" class="btn-action-link">
                    View calendar <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        {{-- Reports --}}
        <div class="col-md-6 col-xl-4">
            <div class="action-card alt-wrapper">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="action-title">Reports</h3>
                    <p class="action-desc">Generate leave reports and analytics</p>
                </div>
                <a href="{{ route('hr.reports.index') }}" class="btn-action-link dark-link">
                    View reports <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        {{-- Leave Types Card --}}
        @if(Auth::user()->role === 'admin')
        <div class="col-md-6 col-xl-4">
            <div class="action-card">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3 class="action-title">Leave Types</h3>
                    <p class="action-desc">Configure and manage leave types</p>
                </div>
                <a href="{{ route('admin.leave-types.index') }}" class="btn-action-link">
                    Manage types <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- Add Employee Card --}}
        <div class="col-md-6 col-xl-4">
            <div class="action-card alt-wrapper">
                <div>
                    <div class="icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="action-title">Add Employee</h3>
                    <p class="action-desc">Register a new employee</p>
                </div>
                <a href="{{ route('hr.employees.create') }}" class="btn-action-link dark-link">
                    Add employee <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
    // Fetch and display pending requests count
    fetch('{{ route("hr.leave-requests.index") }}?status=pending')
        .then(response => response.text())
        .then(html => {
            document.getElementById('pendingCount').textContent = '{{ \App\Models\LeaveRequest::where("status", "pending")->count() }}';
        })
        .catch(error => console.error('Error:', error));
    
    // Set employee count
    document.getElementById('employeeCount').textContent = '{{ \App\Models\User::where("role", "employee")->count() }}';
    
    // Set approved leaves this month
    document.getElementById('approvedCount').textContent = '{{ \App\Models\LeaveRequest::where("status", "approved")->whereMonth("created_at", date("m"))->whereYear("created_at", date("Y"))->count() }}';
    
    // Set total leave days taken this year
    document.getElementById('leaveDaysCount').textContent = '{{ \App\Models\LeaveRequest::where("status", "approved")->whereYear("created_at", date("Y"))->sum("total_days") }}';
</script>
@endsection