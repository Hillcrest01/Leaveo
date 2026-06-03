@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">My Dashboard</h3>
                </div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }}!</h4>
                    <p>Employee ID: {{ Auth::user()->employee_id }}</p>
                    <p>Department: {{ Auth::user()->department ?? 'Not Assigned' }}</p>
                    
                    {{-- Quick Stats --}}
                    <div class="row mt-4 mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Pending Requests</h5>
                                    <h2 class="mb-0">{{ $pendingCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Approved Days ({{ date('Y') }})</h5>
                                    <h2 class="mb-0">{{ $approvedDays ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Leave Types Available</h5>
                                    <h2 class="mb-0">{{ $leaveBalances->where('remaining_days', '>', 0)->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    {{-- Leave Balances --}}
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>My Leave Balances ({{ date('Y') }})</h5>
                            @if($leaveBalances->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Leave Type</th>
                                                <th>Total</th>
                                                <th>Used</th>
                                                <th>Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($leaveBalances as $balance)
                                                <tr>
                                                    <td>{{ $balance->leaveType->name }}</td>
                                                    <td>{{ $balance->total_days }}</td>
                                                    <td>{{ $balance->used_days }}</td>
                                                    <td>
                                                        <strong class="text-{{ $balance->remaining_days > 5 ? 'success' : ($balance->remaining_days > 0 ? 'warning' : 'danger') }}">
                                                            {{ $balance->remaining_days }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-primary mt-2">
                                    Apply for Leave
                                </a>
                            @else
                                <p class="text-muted">No leave balances found.</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Recent Leave Requests</h5>
                            @if($recentRequests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dates</th>
                                                <th>Type</th>
                                                <th>Days</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentRequests as $request)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d') }}</td>
                                                    <td>{{ $request->leaveType->name }}</td>
                                                    <td>{{ $request->total_days }}</td>
                                                    <td>
                                                        @if($request->status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($request->status == 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-secondary mt-2">
                                    View All Requests
                                </a>
                            @else
                                <p class="text-muted">No leave requests found.</p>
                                <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-primary mt-2">
                                    Apply for Leave
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection