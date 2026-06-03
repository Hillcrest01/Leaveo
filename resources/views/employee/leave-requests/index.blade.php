@extends('layouts.app')

@section('content')
<style>
    /* Premium Application Architecture System Overrides */
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

    /* Clean Form Selection Matrix */
    .form-select {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-select:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* High-Contrast Print and Display Badges */
    .badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-approved { background-color: #ebfbee; color: #2b8a3e; }
    .status-pending { background-color: #fff4e6; color: #d9480f; }
    .status-rejected { background-color: #f1f3f5; color: #495057; border: 1px solid #dee2e6; }
    .status-cancelled { background-color: #f8f9fa; color: #868e96; border: 1px solid #e9ecef; }

    .type-badge {
        background-color: #f8f9fa;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Minimal Row Link Controls Matrix */
    .action-link-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .action-row-btn {
        font-size: 0.825rem;
        font-weight: 600;
        text-decoration: none;
        color: #6c757d;
        transition: color 0.15s ease;
        background: none;
        border: none;
        padding: 0;
    }

    .action-row-btn:hover {
        color: #F15929;
    }

    .action-row-btn.btn-cancel-action:hover {
        color: #c92a2a;
    }

    /* Enterprise System Buttons Architecture */
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
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Master Content View Section Header Component Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">My Leave Requests</h1>
                </div>
                <div>
                    <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-brand-primary">
                        <i class="fas fa-plus me-2" style="font-size: 0.8rem;"></i> Apply for Leave
                    </a>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Platform Execution Output Session Response Channels --}}
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
                
                {{-- Parameter Evaluation Filter Form Layout Block --}}
                <form method="GET" action="{{ route('employee.leave-requests.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="year" class="form-select">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-brand-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
                
                {{-- Historical Ledger Records Grid Matrix Container Frame --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Leave Type</th>
                                <th>Date Range</th>
                                <th>Total Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $request)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td><span style="font-weight: 600; color: #6c757d;">#{{ $request->id }}</span></td>
                                    <td><span class="type-badge">{{ $request->leaveType->name }}</span></td>
                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}<br>
                                        <span class="small" style="font-size:0.75rem; color:#adb5bd;">to</span><br>
                                        {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                    </td>
                                    <td style="font-weight: 600; color: #231F20;">{{ $request->total_days }}</td>
                                    <td class="text-muted" style="font-size: 0.875rem;">{{ \Illuminate\Support\Str::limit($request->reason, 50) }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge-status status-pending">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge-status status-approved">Approved</span>
                                            @if($request->approved_at)
                                                <div style="font-size: 0.7rem; color: #6c757d; margin-top: 2px;">by {{ $request->approver->name ?? 'HR' }}</div>
                                            @endif
                                        @elseif($request->status == 'rejected')
                                            <span class="badge-status status-rejected">Rejected</span>
                                        @else
                                            <span class="badge-status status-cancelled">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="action-link-group justify-content-end pe-2">
                                            @if($request->status == 'pending')
                                                <form action="{{ route('employee.leave-requests.cancel', $request) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="action-row-btn btn-cancel-action">Cancel</button>
                                                </form>
                                            @else
                                                <a href="{{ route('employee.leave-requests.show', $request) }}" class="action-row-btn">View</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted small">
                                        No leave requests found.
                                        <br>
                                        <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-brand-primary btn-sm mt-3">
                                            Apply for Leave
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Platform Navigation Controller Component Segment --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $leaveRequests->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection