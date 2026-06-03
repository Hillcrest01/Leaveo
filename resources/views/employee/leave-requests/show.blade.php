@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F15929; border-bottom: none;">
                    <h3 class="mb-0 text-white">Leave Request Details</h3>
                    <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-sm" style="background-color: #FFFFFF; color: #F15929; font-weight: 500;">Back to List</a>
                </div>
                
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        @if($leaveRequest->status == 'pending')
                            <span class="badge p-3" style="background-color: #F7AB93; color: #231F20; font-size: 1rem;">Pending Approval</span>
                        @elseif($leaveRequest->status == 'approved')
                            <span class="badge p-3" style="background-color: #F15929; color: #FFFFFF; font-size: 1rem;">Approved</span>
                        @elseif($leaveRequest->status == 'rejected')
                            <span class="badge p-3" style="background-color: #231F20; color: #FFFFFF; font-size: 1rem;">Rejected</span>
                        @else
                            <span class="badge p-3" style="background-color: #F7AB93; color: #231F20; font-size: 1rem;">Cancelled</span>
                        @endif
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header" style="background-color: #231F20;">
                                    <strong class="text-white">Request Information</strong>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%" style="color: #231F20;">Request ID:</th>
                                            <td><span style="color: #F15929; font-weight: 500;">#{{ $leaveRequest->id }}</span></td>
                                        </tr>
                                        <tr>
                                            <th style="color: #231F20;">Leave Type:</th>
                                            <td><span class="badge" style="background-color: #F7AB93; color: #231F20;">{{ $leaveRequest->leaveType->name }}</span></td>
                                        </tr>
                                        <tr>
                                            <th style="color: #231F20;">Start Date:</th>
                                            <td>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('l, F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="color: #231F20;">End Date:</th>
                                            <td>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('l, F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="color: #231F20;">Total Days:</th>
                                            <td><strong style="color: #F15929;">{{ $leaveRequest->total_days }}</strong> day(s)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header" style="background-color: #231F20;">
                                    <strong class="text-white">Additional Information</strong>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%" style="color: #231F20;">Submitted:</th>
                                            <td>{{ $leaveRequest->created_at->format('l, F d, Y h:i A') }}</td>
                                        </tr>
                                        @if($leaveRequest->approved_at)
                                        <tr>
                                            <th style="color: #231F20;">Approved/Rejected:</th>
                                            <td>{{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('l, F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="color: #231F20;">Processed By:</th>
                                            <td>{{ $leaveRequest->approver->name ?? 'System' }}</td>
                                        </tr>
                                        @endif
                                        @if($leaveRequest->remarks)
                                        <tr>
                                            <th style="color: #231F20;">Remarks:</th>
                                            <td>{{ $leaveRequest->remarks }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-header" style="background-color: #231F20;">
                            <strong class="text-white">Reason for Leave</strong>
                        </div>
                        <div class="card-body" style="background-color: #FFFFFF;">
                            {{ $leaveRequest->reason }}
                        </div>
                    </div>
                    
                    @if($leaveRequest->status == 'pending')
                        <div class="alert mt-4" style="background-color: #F7AB93; border-left: 4px solid #F15929; color: #231F20;">
                            <i class="fas fa-clock"></i>
                            This request is pending approval. You can cancel it if needed.
                        </div>
                        <form action="{{ route('employee.leave-requests.cancel', $leaveRequest) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn px-4 py-2" style="background-color: #231F20; color: #FFFFFF;">Cancel Request</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.75rem;
    }
    
    .btn:hover {
        opacity: 0.85;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        border-radius: 2rem;
    }
</style>
@endsection