@extends('layouts.app')

@section('content')
<style>
    /* Premium Production Design Overrides */
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

    /* Professional Input Alignment Tokens */
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

    /* Clean Structural Metric Badges */
    .badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-active-count {
        background-color: #fff4e6;
        color: #d9480f;
        border: 1px solid #ffe8cc;
    }
    
    .status-zero-count {
        background-color: #f1f3f5;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    /* Global Application Buttons Grid */
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
            
            {{-- Structural Section Header Layout Components --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Department Report - {{ $year }}</h1>
                </div>
                <div>
                    <a href="{{ route('hr.reports.index') }}" class="btn btn-secondary-action">Back to Reports</a>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Master Filter Processing Form Frame --}}
                <form method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="year" class="form-select">
                                @for($i = 2020; $i <= date('Y')+1; $i++)
                                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-brand-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
                
                {{-- Analytical Organizational Metrics Core Grid Framework --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Employees</th>
                                <th>Total Leaves</th>
                                <th>Average/Employee</th>
                                <th>Pending Requests</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentReport as $dept)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td>
                                        <span style="font-weight: 700; color: #231F20;">{{ $dept->department }}</span>
                                    </td>
                                    <td style="font-weight: 500; color: #231F20;">{{ $dept->employee_count }}</td>
                                    <td class="text-muted">{{ $dept->total_leaves }} days</td>
                                    <td class="text-muted">{{ $dept->avg_leaves }} days</td>
                                    <td>
                                        @if($dept->pending_requests > 0)
                                            <span class="badge-status status-active-count">{{ $dept->pending_requests }}</span>
                                        @else
                                            <span class="badge-status status-zero-count">0</span>
                                        @endif
                                    </td>
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