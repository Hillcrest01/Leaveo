@extends('layouts.app')

@section('content')
<style>
    /* Premium Architecture Tokens Override */
    .details-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }

    .section-block {
        border: 1px solid #e9ecef;
        border-radius: 4px;
        background: #ffffff;
        overflow: hidden;
    }

    .section-title-bar {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #231F20;
        margin: 0;
    }

    /* Clean Information Layout Columns */
    .info-table th {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        padding: 0.625rem 1rem;
    }

    .info-table td {
        font-size: 0.875rem;
        color: #231F20;
        padding: 0.625rem 1rem;
    }

    .data-table th {
        background-color: #f8f9fa;
        color: #231F20;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
    }

    .data-table td {
        font-size: 0.875rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    /* Enterprise Status Labels */
    .badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
    }

    .status-approved { background-color: #ebfbee; color: #2b8a3e; }
    .status-pending { background-color: #fff4e6; color: #d9480f; }
    .status-rejected { background-color: #f8f9fa; color: #495057; border: 1px solid #dee2e6; }

    .dept-label {
        background-color: #f1f3f5;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Buttons Configuration Matrix */
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

    .form-control {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-control:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Unified Structural Page Header Header Component --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Employee Profile</h1>
                    <div class="text-muted small mt-1">Details for: <span style="font-weight: 600; color: #231F20;">{{ $employee->name }}</span></div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('hr.employees.edit', $employee) }}" class="btn btn-brand-primary">Edit Profile</a>
                    <a href="{{ route('hr.employees.index') }}" class="btn btn-secondary-action">Back to List</a>
                </div>
            </div>

            <div class="details-card p-4">
                {{-- Dynamic Alert Validation Pipes --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Grid Partition Segment for Core Content Blocks --}}
                <div class="row mb-4 g-4">
                    {{-- Personal Information Segment --}}
                    <div class="col-lg-6">
                        <div class="section-block h-100">
                            <div class="section-title-bar">
                                <h2 class="section-title">Personal Information</h2>
                            </div>
                            <div class="p-2">
                                <table class="table info-table table-sm table-borderless mb-0">
                                    <tr>
                                        <th width="30%">Employee ID:</th>
                                        <td style="font-weight: 600; color: #231F20;">{{ $employee->employee_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name:</th>
                                        <td style="font-weight: 500;">{{ $employee->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email Address:</th>
                                        <td class="text-muted">{{ $employee->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td>{{ $employee->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td><span class="dept-label">{{ $employee->department ?? 'Not assigned' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Join Date:</th>
                                        <td class="text-muted">{{ $employee->join_date ? $employee->join_date : 'Not set' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td class="text-muted">{{ $employee->address ?? 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Leave Allocations Metric Segment --}}
                    <div class="col-lg-6">
                        <div class="section-block h-100">
                            <div class="section-title-bar">
                                <h2 class="section-title">Leave Balances ({{ date('Y') }})</h2>
                            </div>
                            <div class="p-3">
                                @if($leaveBalances->count() > 0)
                                    <div class="table-responsive" style="border: 1px solid #e9ecef; border-radius: 4px;">
                                        <table class="table data-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Leave Type</th>
                                                    <th>Total</th>
                                                    <th>Used</th>
                                                    <th class="text-end">Remaining</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($leaveBalances as $balance)
                                                    <tr>
                                                        <td style="font-weight: 500;">{{ $balance->leaveType->name }}</td>
                                                        <td class="text-muted">{{ $balance->total_days }}</td>
                                                        <td class="text-muted">{{ $balance->used_days }}</td>
                                                        <td class="text-end" style="font-weight: 600; color: #231F20;">
                                                            {{ $balance->remaining_days }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted small">
                                        No active leave type ledger entries configurations discovered for this period.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Historical Requests Pipeline Tracker Component --}}
                <div class="section-block mb-4">
                    <div class="section-title-bar">
                        <h2 class="section-title">Recent Leave History</h2>
                    </div>
                    <div class="p-3">
                        @if($recentRequests->count() > 0)
                            <div class="table-responsive" style="border: 1px solid #e9ecef; border-radius: 4px;">
                                <table class="table data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date Duration</th>
                                            <th>Leave Classification</th>
                                            <th>Total Days</th>
                                            <th>Reason Details</th>
                                            <th>Operational Status</th>
                                            <th class="text-end">Submitted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentRequests as $request)
                                            <tr>
                                                <td style="font-weight: 500;">{{ $request->start_date }} to {{ $request->end_date }}</td>
                                                <td>{{ $request->leaveType->name }}</td>
                                                <td class="text-muted">{{ $request->total_days }}</td>
                                                <td class="text-muted">{{ Str::limit($request->reason, 45) }}</td>
                                                <td>
                                                    @if($request->status == 'pending')
                                                        <span class="badge-status status-pending">Pending</span>
                                                    @elseif($request->status == 'approved')
                                                        <span class="badge-status status-approved">Approved</span>
                                                    @else
                                                        <span class="badge-status status-rejected">Rejected</span>
                                                    @endif
                                                </td>
                                                <td class="text-end text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted small">
                                No leave history.
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Account Access Control Security Target Block --}}
                <div class="section-block col-md-12">
                    <div class="section-title-bar">
                        <h2 class="section-title">Change Password</h2>
                    </div>
                    <div class="p-3">
                        <form action="{{ route('hr.employees.reset-password', $employee) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <input type="password" 
                                           name="password" 
                                           class="form-control" 
                                           placeholder="Assign New Access Password" 
                                           required>
                                </div>
                                <div class="col-md-5">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Confirm Access Password" 
                                           required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-brand-primary w-100">Reset Password</button>
                                </div>
                            </div>
                        </form>
                        <div class="text-muted mt-2 ps-1" style="font-size: 0.75rem;">Requires 8 characters minimum.</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection