@extends('layouts.app')

@section('content')
<style>
    /* Premium Production Matrix Design Tokens */
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

    /* Clean Input Alignment Framework */
    .form-select, .form-control {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-select:focus, .form-control:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Professional Status Badges */
    .dept-badge {
        background-color: #f8f9fa;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
    }

    /* Structured Micro Metrics Grid */
    .metric-row {
        font-size: 0.815rem;
        line-height: 1.4;
        color: #6c757d;
    }

    .metric-val {
        font-weight: 600;
        color: #231F20;
    }

    .metric-val-accent {
        font-weight: 600;
        color: #F15929;
    }

    /* Global Functional Buttons Matrix */
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
        transition: background-color 0.15s ease;
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
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Master Section Grid Page Header Components --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Employee Leave Summary - {{ $year }}</h1>
                </div>
                <div>
                    <a href="{{ route('hr.reports.index') }}" class="btn btn-secondary-action">Back to Reports</a>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Global Parameter Filter Target Form --}}
                <form method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="year" class="form-select">
                                @for($i = 2020; $i <= date('Y')+1; $i++)
                                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-brand-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
                
                {{-- Complex Leave Type Cross-Tabular Balance Ledger Frame --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                @foreach($employees->first()->leaveBalances ?? [] as $balance)
                                    <th>{{ $balance->leaveType->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td>
                                        <span style="font-weight: 600; color: #231F20;">{{ $employee->name }}</span><br>
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $employee->employee_id }}</span>
                                    </td>
                                    <td>
                                        <span class="dept-badge">{{ $employee->department ?? 'N/A' }}</span>
                                    </td>
                                    @foreach($employee->leaveBalances as $balance)
                                        <td>
                                            <div class="metric-row">Total: <span class="metric-val">{{ $balance->total_days }}</span></div>
                                            <div class="metric-row">Used: <span class="metric-val">{{ $balance->used_days }}</span></div>
                                            <div class="metric-row">Remaining: <span class="metric-val-accent">{{ $balance->remaining_days }}</span></div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection