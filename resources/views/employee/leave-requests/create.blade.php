@extends('layouts.app')

@section('content')
<style>
    /* Premium Production Design Overrides */
    .form-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #231F20;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        height: 40px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    textarea.form-control {
        height: auto;
    }

    .form-control:focus, .form-select:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Balanced Informational Metrics Summary Box (Gradients removed) */
    .system-notice {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 1rem;
        font-size: 0.875rem;
        color: #231F20;
    }

    /* Platform Button Architecture Modules */
    .btn-brand-primary {
        background-color: #F15929;
        border-color: #F15929;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 40px;
        padding: 0 1.5rem;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease;
        border: 1px solid transparent;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-cancel {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 4px;
        height: 40px;
        padding: 0 1.5rem;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .btn-cancel:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }

    .btn-list-back {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.815rem;
        font-weight: 600;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-list-back:hover {
        background-color: #f8f9fa;
        color: #231F20;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        font-weight: 500;
        color: #F15929;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            
            {{-- Unified Structural Content Page Header Component Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Apply for Leave</h1>
                </div>
                <div>
                    <a href="{{ route('employee.leave-requests.index') }}" class="btn-list-back">Back to History</a>
                </div>
            </div>

            <div class="form-card p-4 col-md-11 mx-auto">
                {{-- Platform Error Alerts Pipelines --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Conditional System Check Logic Target Configuration --}}
                @if($availableLeaveTypes->count() == 0)
                    <div class="system-notice mb-4" style="border-left-color: #c92a2a; background-color: #fff5f5;">
                        <h5 style="color: #c92a2a; font-weight:700;" class="mb-1">No Leave Balance Available</h5>
                        <p class="text-secondary mb-3">You don't have any leave balance remaining for this year. Please contact HR for assistance.</p>
                        <a href="{{ route('employee.index') }}" class="btn btn-brand-primary" style="background-color: #c92a2a; border-color: #c92a2a;">Back to Dashboard</a>
                    </div>
                @else
                    <form action="{{ route('employee.leave-requests.store') }}" method="POST" id="leaveForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label fw-semibold" style="color: #231F20;">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror" 
                                    id="leave_type_id" 
                                    name="leave_type_id" 
                                    required>
                                <option value="">Select Leave Type</option>
                                @foreach($availableLeaveTypes as $type)
                                    <option value="{{ $type->id }}" 
                                            data-remaining="{{ $type->remaining_days }}"
                                            {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->remaining_days }} days remaining)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @php
                            $today = Carbon\Carbon::today()->format('Y-m-d');
                        @endphp
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold" style="color: #231F20;">Start Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       min="{{ $today }}"
                                       name="start_date" 
                                       value="{{ old('start_date') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-semibold" style="color: #231F20;">End Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       min="{{ $today }}"
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Live Metric Evaluation Notice Box --}}
                        <div class="system-notice mb-3">
                            <div class="d-flex flex-column gap-1">
                                <div><strong>Total Days:</strong> <span id="totalDays">0</span> day(s)</div>
                                <div><strong>Remaining Balance:</strong> <span id="remainingBalance">0</span> day(s)</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold" style="color: #231F20;">Reason for Leave <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4" 
                                      required
                                      placeholder="Please provide a detailed reason for your leave request...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">Minimum 10 characters. Be specific about your leave purpose.</div>
                        </div>
                        
                        <div class="d-flex gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-brand-primary" id="submitBtn">Submit Request</button>
                            <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-cancel">Cancel</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function calculateDays() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                document.getElementById('totalDays').textContent = diffDays;
                
                const remaining = parseFloat(document.getElementById('remainingBalance').textContent);
                if (diffDays > remaining) {
                    document.getElementById('totalDays').style.color = '#231F20';
                    document.getElementById('submitBtn').disabled = true;
                } else {
                    document.getElementById('totalDays').style.color = '#F15929';
                    document.getElementById('submitBtn').disabled = false;
                }
            } else {
                document.getElementById('totalDays').textContent = '0';
                document.getElementById('submitBtn').disabled = true;
            }
        }
    }
    
    function updateRemainingBalance() {
        const select = document.getElementById('leave_type_id');
        const selectedOption = select.options[select.selectedIndex];
        const remaining = selectedOption.getAttribute('data-remaining');
        
        if (remaining) {
            document.getElementById('remainingBalance').textContent = remaining;
        } else {
            document.getElementById('remainingBalance').textContent = '0';
        }
        
        calculateDays();
    }
    
    document.getElementById('leave_type_id').addEventListener('change', updateRemainingBalance);
    document.getElementById('start_date').addEventListener('change', calculateDays);
    document.getElementById('end_date').addEventListener('change', calculateDays);
    
    if (document.getElementById('leave_type_id').value) {
        updateRemainingBalance();
    }
</script>
@endsection