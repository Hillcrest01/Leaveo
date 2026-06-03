@extends('layouts.app')

@section('content')
<style>
    /* Premium Data Dashboard Token Matrix */
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

    /* Minimalist Metrics Frame Blocks (Gradients entirely removed) */
    .metric-panel {
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 1.5rem;
    }

    .metric-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .metric-data {
        font-size: 2.25rem;
        font-weight: 700;
        color: #231F20;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .metric-note {
        font-size: 0.825rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Visual Graph & Progress Panels */
    .chart-panel {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background-color: #ffffff;
        overflow: hidden;
    }

    .chart-header-bar {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 0.85rem 1.25rem;
    }

    .chart-panel-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #231F20;
        margin: 0;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: #f1f3f5;
    }

    .progress-bar {
        border-radius: 3px;
    }

    /* Interactive Inputs & Button Modules */
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

    .btn-brand-muted {
        background-color: #fff0ec;
        border-color: #fff0ec;
        color: #F15929;
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

    .btn-brand-muted:hover {
        background-color: #ffdcd3;
        color: #d4481d;
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
        transition: background-color 0.15s ease;
    }

    .btn-brand-dark:hover {
        background-color: #110f10;
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

    /* Modal Architecture Layout */
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
            
            {{-- Master Layout Page Header Title Block --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Reports Dashboard</h1>
                </div>
            </div>

            {{-- Operational High Contrast Analytical Cards Grid --}}
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="metric-panel">
                        <div class="metric-title">{{ $currentYear }} Total Leaves</div>
                        <div class="metric-data" style="color: #F15929;">{{ array_sum($monthlyTrends) }}</div>
                        <div class="metric-note">days approved</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-panel">
                        <div class="metric-title">Pending Approvals</div>
                        <div class="metric-data" style="color: #d9480f;">{{ $pendingCount }}</div>
                        <div class="metric-note">requests waiting</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-panel">
                        <div class="metric-title">This Month</div>
                        <div class="metric-data">{{ $thisMonthLeaves }}</div>
                        <div class="metric-note">days taken</div>
                    </div>
                </div>
            </div>
            
            {{-- Data Visualization Graph Frame Layer --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-panel">
                        <div class="chart-header-bar">
                            <h2 class="chart-panel-title">Monthly Leave Trends ({{ $currentYear }})</h2>
                        </div>
                        <div class="p-4 bg-white">
                            <canvas id="monthlyChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="chart-panel">
                        <div class="chart-header-bar">
                            <h2 class="chart-panel-title">Leave Type Utilization</h2>
                        </div>
                        <div class="p-4 bg-white">
                            <canvas id="leaveTypeChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Tabular Registry & Ranking Segment Row --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-panel">
                        <div class="chart-header-bar">
                            <h2 class="chart-panel-title">Department Statistics</h2>
                        </div>
                        <div class="p-3 bg-white">
                            <div class="table-responsive table-container">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Employees</th>
                                            <th class="text-end">% of Company</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalEmployees = $departmentStats->sum('total_employees'); @endphp
                                        @foreach($departmentStats as $dept)
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td style="font-weight: 600; color: #231F20;">{{ $dept->department }}</td>
                                                <td class="text-muted">{{ $dept->total_employees }}</td>
                                                <td class="text-end fw-semibold" style="color: #231F20;">{{ round(($dept->total_employees / $totalEmployees) * 100, 1) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="chart-panel">
                        <div class="chart-header-bar">
                            <h2 class="chart-panel-title">Top Leave Takers ({{ $currentYear }})</h2>
                        </div>
                        <div class="p-4 bg-white">
                            @if($topLeaveTakers->count() > 0)
                                @foreach($topLeaveTakers as $employee)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span style="font-weight: 500; color: #231F20;">{{ $employee->name }}</span>
                                            <span style="font-size: 0.815rem; font-weight: 700; color: #F15929;">{{ $employee->total_leaves ?? 0 }} days</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ min(($employee->total_leaves ?? 0) / 30 * 100, 100) }}%; background-color: #F15929;">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-muted small">No data available</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Document Export Pipeline Module --}}
            <div class="chart-panel">
                <div class="chart-header-bar">
                    <h2 class="chart-panel-title">Download PDF Reports</h2>
                </div>
                <div class="p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-brand-primary w-100" data-bs-toggle="modal" data-bs-target="#employeeSummaryModal">
                                Employee Summary PDF
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-brand-muted w-100" data-bs-toggle="modal" data-bs-target="#departmentReportModal">
                                Department Report PDF
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-brand-dark w-100" data-bs-toggle="modal" data-bs-target="#leaveRequestsModal">
                                Leave Requests PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Employee Summary Parameter Extraction Modal --}}
<div class="modal fade" id="employeeSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: #231F20;">Download Employee Summary PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hr.reports.export-employee-summary-pdf') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #231F20;">Year</label>
                        <select name="year" class="form-select">
                            @for($i = 2020; $i <= date('Y')+1; $i++)
                                <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #231F20;">Department (Optional)</label>
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departmentStats as $dept)
                                <option value="{{ $dept->department }}">{{ $dept->department }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary-action" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Download PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Department Parameter Extraction Modal --}}
<div class="modal fade" id="departmentReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: #231F20;">Download Department Report PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hr.reports.export-department-report-pdf') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #231F20;">Year</label>
                        <select name="year" class="form-select">
                            @for($i = 2020; $i <= date('Y')+1; $i++)
                                <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary-action" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Download PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Leave History Parameter Extraction Modal --}}
<div class="modal fade" id="leaveRequestsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: #231F20;">Download Leave Requests PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hr.reports.export-leave-requests-pdf') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #231F20;">Status Filter</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #231F20;">Date From</label>
                            <input type="date" name="date_from" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #231F20;">Date To</label>
                            <input type="date" name="date_to" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary-action" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-dark">Download PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Analytic Charts Presentation Matrix Engine --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Leave Days Taken',
                data: [
                    {{ $monthlyTrends[1] }}, {{ $monthlyTrends[2] }}, {{ $monthlyTrends[3] }},
                    {{ $monthlyTrends[4] }}, {{ $monthlyTrends[5] }}, {{ $monthlyTrends[6] }},
                    {{ $monthlyTrends[7] }}, {{ $monthlyTrends[8] }}, {{ $monthlyTrends[9] }},
                    {{ $monthlyTrends[10] }}, {{ $monthlyTrends[11] }}, {{ $monthlyTrends[12] }}
                ],
                backgroundColor: 'rgba(241, 89, 41, 0.04)',
                borderColor: '#F15929',
                borderWidth: 2,
                pointBackgroundColor: '#F15929',
                pointBorderColor: '#ffffff',
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#F15929',
                pointRadius: 4,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        font: { family: "system-ui", size: 12, weight: "500" },
                        color: "#231F20"
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: "#6c757d", font: { family: "system-ui" } } },
                y: { grid: { color: "#f1f3f5" }, ticks: { color: "#6c757d", font: { family: "system-ui" } } }
            }
        }
    });
    
    const leaveTypeCtx = document.getElementById('leaveTypeChart').getContext('2d');
    new Chart(leaveTypeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($leaveTypeUtilization->pluck('name')) !!},
            datasets: [{
                label: 'Total Days Used',
                data: {!! json_encode($leaveTypeUtilization->pluck('total_days_used')) !!},
                backgroundColor: '#231F20',
                hoverBackgroundColor: '#F15929',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        font: { family: "system-ui", size: 12, weight: "500" },
                        color: "#231F20"
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: "#6c757d", font: { family: "system-ui" } } },
                y: { beginAtZero: true, grid: { color: "#f1f3f5" }, ticks: { color: "#6c757d", font: { family: "system-ui" } } }
            }
        }
    });
</script>
@endsection