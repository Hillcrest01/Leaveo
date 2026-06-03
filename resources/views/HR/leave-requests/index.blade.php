@extends('layouts.app')

@section('content')
<style>
    /* Content System Design Tokens */
    .page-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }
    
    .table-container {
        border: 1px solid #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    /* Clean Input Components Base Layout */
    .form-control, .form-select {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Clean Solid Stats Grid (No Gradients) */
    .stat-block {
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 1.25rem;
    }

    .stat-label-text {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .stat-number-text {
        font-size: 1.75rem;
        font-weight: 700;
        color: #231F20;
        line-height: 1;
    }

    /* Professional Status Badges */
    .badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
    }
    
    .status-approved { background-color: #ebfbee; color: #2b8a3e; }
    .status-pending { background-color: #fff4e6; color: #d9480f; }
    .status-rejected { background-color: #f1f3f5; color: #495057; border: 1px solid #dee2e6; }

    .type-badge {
        background-color: #f8f9fa;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Button Action Systems Components */
    .btn-brand-primary {
        background-color: #F15929;
        border-color: #F15929;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 38px;
        padding: 0 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-secondary-action {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 4px;
        height: 38px;
        padding: 0 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-secondary-action:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }

    .btn-table-review {
        font-size: 0.825rem;
        font-weight: 600;
        color: #F15929;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .btn-table-review:hover {
        color: #d4481d;
    }

    /* Clean Modular Layout Overlays */
    .modal-content {
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Structural Section Action Layout Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Leave Request Management</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('hr.leave-requests.calendar') }}" class="btn btn-secondary-action">Calendar View</a>
                    <button type="button" class="btn btn-secondary-action" data-bs-toggle="modal" data-bs-target="#exportModal">Export Report</button>
                </div>
            </div>

            {{-- Operational Statistics Row Configuration --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-block">
                        <div class="stat-label-text">Pending</div>
                        <div class="stat-number-text" style="color: #d9480f;">{{ $stats['pending'] }}</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-block">
                        <div class="stat-label-text">Approved</div>
                        <div class="stat-number-text" style="color: #2b8a3e;">{{ $stats['approved'] }}</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-block">
                        <div class="stat-label-text">Rejected</div>
                        <div class="stat-number-text" style="color: #495057;">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-block">
                        <div class="stat-label-text">Total</div>
                        <div class="stat-number-text">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Platform Alerts Execution Output Channels --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Global Metrics Filter Execution Form Block --}}
                <form method="GET" action="{{ route('hr.leave-requests.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search employee..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" placeholder="Date From" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" placeholder="Date To" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-brand-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
                
                {{-- System Bulk Approval Transaction Targets Form --}}
                <form id="bulkForm" action="{{ route('hr.leave-requests.bulk-approve') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <button type="submit" class="btn btn-secondary-action btn-sm" onclick="return confirm('Approve selected requests?')">
                            Bulk Approve Selected
                        </button>
                    </div>
                    
                    {{-- Central Registry Core Grid Component Frame --}}
                    <div class="table-responsive table-container">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="40" class="ps-3"><input type="checkbox" id="selectAll" style="accent-color: #F15929;"></th>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Leave Type</th>
                                    <th>Date Range</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveRequests as $request)
                                    <tr style="border-bottom: 1px solid #e9ecef;">
                                        <td class="ps-3">
                                            @if($request->status == 'pending')
                                                <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" class="request-checkbox" style="accent-color: #F15929;">
                                            @endif
                                        </td>
                                        <td><span style="font-weight: 600; color: #6c757d;">#{{ $request->id }}</span></td>
                                        <td>
                                            @if($request->user)
                                                <span style="font-weight: 600; color: #231F20;">{{ $request->user->name }}</span><br>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $request->user->employee_id ?? 'N/A' }}</span>
                                            @else
                                                <span style="font-weight: 600; color: #231F20;">User not found</span><br>
                                                <span class="text-muted small" style="font-size: 0.75rem;">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">
                                            @if($request->user && $request->user->department)
                                                {{ $request->user->department }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($request->leaveType)
                                                <span class="type-badge">{{ $request->leaveType->name }}</span>
                                            @else
                                                <span class="type-badge">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">
                                            {{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - 
                                            {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                        </td>
                                        <td style="font-weight: 500; color: #231F20;">{{ $request->total_days }}</td>
                                        <td class="text-muted" style="font-size: 0.875rem;">{{ \Illuminate\Support\Str::limit($request->reason, 40) }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge-status status-pending">Pending</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge-status status-approved">Approved</span>
                                            @else
                                                <span class="badge-status status-rejected">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('hr.leave-requests.show', $request) }}" class="btn-table-review">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted small">
                                            No leave requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                
                {{-- Dynamic Core Framework Pagination Controller Container --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $leaveRequests->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Export System Functional Configurations Overlay Modal --}}
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: #231F20; letter-spacing: -0.01em;">Export Leave Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hr.leave-requests.export') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #231F20;">Report Type</label>
                        <select name="type" id="reportType" class="form-select" required>
                            <option value="monthly">Monthly Report</option>
                            <option value="yearly">Yearly Report</option>
                        </select>
                    </div>
                    <div class="mb-3" id="monthlyDiv">
                        <label class="form-label fw-semibold" style="color: #231F20;">Select Month</label>
                        <input type="month" name="month" class="form-control" value="{{ date('Y-m') }}">
                    </div>
                    <div class="mb-3" id="yearlyDiv" style="display: none;">
                        <label class="form-label fw-semibold" style="color: #231F20;">Select Year</label>
                        <select name="year" class="form-select">
                            @for($i = 2020; $i <= date('Y')+1; $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary-action" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Download CSV</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkbox.forEach(cb => cb.checked = this.checked);
    });
    
    document.getElementById('reportType').addEventListener('change', function() {
        if (this.value === 'monthly') {
            document.getElementById('monthlyDiv').style.display = 'block';
            document.getElementById('yearlyDiv').style.display = 'none';
        } else {
            document.getElementById('monthlyDiv').style.display = 'none';
            document.getElementById('yearlyDiv').style.display = 'block';
        }
    });
</script>
@endsection