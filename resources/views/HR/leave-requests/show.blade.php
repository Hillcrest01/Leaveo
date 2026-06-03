@extends('layouts.app')

@section('content')
<style>
    /* Premium Application Architecture Overrides */
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

    /* Clean Info Table Layouts */
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

    /* Contextual Process State Blocks (Gradients Removed) */
    .state-banner {
        border-radius: 4px;
        padding: 1.25rem;
        border: 1px solid transparent;
    }

    .state-banner-pending {
        background-color: #fff4e6;
        border-color: #ffe8cc;
        color: #d9480f;
    }

    .state-banner-approved {
        background-color: #ebfbee;
        border-color: #d3f9d8;
        color: #2b8a3e;
    }

    .state-banner-rejected {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #231F20;
    }

    .type-label {
        background-color: #f1f3f5;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Functional Inputs and Form Typography */
    .form-control {
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-control:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #231F20;
        margin-bottom: 0.5rem;
    }

    /* Button Layout Components */
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
        border: 1px solid transparent;
        transition: background-color 0.15s ease;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-brand-dark {
        background-color: #231F20;
        border-color: #231F20;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 38px;
        padding: 0 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        transition: background-color 0.15s ease;
    }

    .btn-brand-dark:hover {
        background-color: #110f10;
        border-color: #110f10;
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
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Master Structural Content Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Leave Request Details</h1>
                </div>
                <div>
                    <a href="{{ route('hr.leave-requests.index') }}" class="btn btn-secondary-action">Back to List</a>
                </div>
            </div>

            <div class="details-card p-4">
                @if(!isset($leaveRequest))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> Leave request data not found.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @else
                    {{-- Status Banner Condition Tracker --}}
                    @if($leaveRequest->status == 'pending')
                        <div class="state-banner state-banner-pending mb-4">
                            <h2 class="h5 mb-0 fw-bold">⏳ Pending Approval</h2>
                        </div>
                    @elseif($leaveRequest->status == 'approved')
                        <div class="state-banner state-banner-approved mb-4">
                            <h2 class="h5 mb-1 fw-bold"> Approved</h2>
                            <span class="small opacity-90">By: {{ $leaveRequest->approver->name ?? 'System' }} on {{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('F d, Y h:i A') }}</span>
                        </div>
                    @elseif($leaveRequest->status == 'rejected')
                        <div class="state-banner state-banner-rejected mb-4">
                            <h2 class="h5 mb-1 fw-bold"> Rejected</h2>
                            <span class="small text-muted">By: {{ $leaveRequest->approver->name ?? 'System' }} on {{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('F d, Y h:i A') }}</span>
                        </div>
                    @endif
                    
                    {{-- General Layout Row Splitter --}}
                    <div class="row g-4">
                        {{-- Employee Information Column Block --}}
                        <div class="col-md-6">
                            <div class="section-block h-100">
                                <div class="section-title-bar">
                                    <h3 class="section-title">Employee Information</h3>
                                </div>
                                <div class="p-2">
                                    <table class="table info-table table-sm table-borderless mb-0">
                                        <tr>
                                            <th width="35%">Name:</th>
                                            <td style="font-weight: 600; color: #231F20;">{{ $leaveRequest->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Employee ID:</th>
                                            <td class="text-muted">{{ $leaveRequest->user->employee_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department:</th>
                                            <td><span class="type-label">{{ $leaveRequest->user->department ?? 'N/A' }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td class="text-muted">{{ $leaveRequest->user->email ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Leave Parameters Column Block --}}
                        <div class="col-md-6">
                            <div class="section-block h-100">
                                <div class="section-title-bar">
                                    <h3 class="section-title">Leave Details</h3>
                                </div>
                                <div class="p-2">
                                    <table class="table info-table table-sm table-borderless mb-0">
                                        <tr>
                                            <th width="35%">Leave Type:</th>
                                            <td><span class="type-label">{{ $leaveRequest->leaveType->name ?? 'N/A' }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Start Date:</th>
                                            <td class="text-muted">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date:</th>
                                            <td class="text-muted">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Days:</th>
                                            <td><span style="font-weight: 700; color: #F15929;">{{ $leaveRequest->total_days }}</span> day(s)</td>
                                        </tr>
                                        <tr>
                                            <th>Submitted:</th>
                                            <td class="text-muted">{{ $leaveRequest->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Textarea Submission Content Frame --}}
                    <div class="section-block mt-4">
                        <div class="section-title-bar">
                            <h3 class="section-title">Reason for Leave</h3>
                        </div>
                        <div class="p-3 text-secondary" style="font-size: 0.925rem; line-height: 1.5; background-color: #ffffff;">
                            {{ $leaveRequest->reason }}
                        </div>
                    </div>
                    
                    {{-- Process Decisions Panel Matrix conditional check --}}
                    @if($leaveRequest->status == 'pending')
                        <div class="row mt-4 g-4">
                            {{-- Approval Action Module Card --}}
                            <div class="col-md-6">
                                <div class="section-block">
                                    <div class="section-title-bar" style="border-bottom: 1px solid #e9ecef;">
                                        <h3 class="section-title">Approve</h3>
                                    </div>
                                    <div class="p-3">
                                        <form action="{{ route('hr.leave-requests.approve', $leaveRequest) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label">Remarks (Optional)</label>
                                                <textarea name="remarks" class="form-control" rows="2" placeholder="Provide notes regarding approval decision..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-brand-primary" onclick="return confirm('Approve this request?')">
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Rejection Action Module Card --}}
                            <div class="col-md-6">
                                <div class="section-block">
                                    <div class="section-title-bar" style="border-bottom: 1px solid #e9ecef;">
                                        <h3 class="section-title">Reject</h3>
                                    </div>
                                    <div class="p-3">
                                        <form action="{{ route('hr.leave-requests.reject', $leaveRequest) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                                <textarea name="remarks" class="form-control" rows="2" placeholder="Provide reasoning justification for rejection..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-brand-dark" onclick="return confirm('Reject this request?')">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection