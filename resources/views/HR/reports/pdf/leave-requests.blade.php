<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Requests Report</title>
    <style>
        /* Modern Native Print & Report CSS Architecture */
        :root {
            --color-brand: #F15929;
            --color-dark: #231F20;
            --color-muted: #6c757d;
            --color-border: #ced4da;
            --color-border-light: #e9ecef;
            --color-bg-light: #f8f9fa;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: var(--color-dark);
            margin: 0;
            padding: 20px;
            background-color: #FFFFFF;
            -webkit-print-color-adjust: exact;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid var(--color-brand);
            padding-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            color: var(--color-dark);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .header h2 {
            margin: 4px 0;
            color: var(--color-brand);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .header p {
            margin: 4px 0;
            color: var(--color-muted);
            font-size: 9.5px;
        }

        /* Clean Tabular Print Framework */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid var(--color-border-light);
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: var(--color-bg-light);
            color: var(--color-dark);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--color-border);
        }

        tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        /* High-Contrast Print-Ready Status Indicators */
        .status-approved {
            color: #2b8a3e;
            font-weight: 700;
        }

        .status-pending {
            color: #d9480f;
            font-weight: 700;
        }

        .status-rejected {
            color: #c92a2a;
            font-weight: 700;
        }

        .type-pill {
            font-weight: 600;
            color: var(--color-dark);
        }

        /* Solid Summary Panel (Gradients completely removed) */
        .summary-stats {
            margin-top: 15px;
            margin-bottom: 30px;
            padding: 12px;
            background-color: var(--color-bg-light);
            border: 1px solid var(--color-border-light);
            border-radius: 4px;
            font-size: 10px;
            color: var(--color-dark);
        }

        .summary-stats strong {
            color: var(--color-dark);
            font-weight: 700;
        }

        /* Secure Validation Layout Blocks */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 100%;
        }

        .signature-row {
            width: 100%;
            display: block;
        }

        .signature-col {
            width: 45%;
            float: left;
            text-align: left;
        }

        .signature-col:last-child {
            float: right;
        }

        .signature-line {
            margin-top: 45px;
            border-top: 1px solid var(--color-border);
            width: 100%;
        }

        .signature-name {
            margin-top: 6px;
            font-weight: 700;
            font-size: 11px;
            color: var(--color-dark);
        }

        .signature-title {
            font-size: 10px;
            color: var(--color-muted);
            margin-top: 2px;
        }

        .signature-date-line {
            margin-top: 6px;
            font-size: 10px;
            color: var(--color-muted);
        }

        .clearfix {
            clear: both;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8.5px;
            color: var(--color-muted);
            border-top: 1px solid var(--color-border-light);
            padding-top: 6px;
            background-color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DSL Systems & Solutions Limited</h1>
        <h2>Leave Requests Report</h2>
        <p>Generated on: {{ $generated_date }}</p>
        @if($status)
            <p style="font-weight: 600; color: var(--color-dark);">Status Filter: {{ ucfirst($status) }}</p>
        @endif
        @if($dateFrom && $dateTo)
            <p>Date Range: {{ $dateFrom }} to {{ $dateTo }}</p>
        @elseif($dateFrom)
            <p>From Date: {{ $dateFrom }}</p>
        @elseif($dateTo)
            <p>To Date: {{ $dateTo }}</p>
        @endif
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveRequests as $request)
                <tr style="border-bottom: 1px solid var(--color-border-light);">
                    <td>
                        <span style="font-weight: 600; color: var(--color-dark);">{{ $request->user->name }}</span><br>
                        <span style="color: var(--color-muted); font-size: 8.5px;">{{ $request->user->employee_id }}</span>
                    </td>
                    <td class="text-muted">{{ $request->user->department ?? 'N/A' }}</td>
                    <td><span class="type-pill">{{ $request->leaveType->name }}</span></td>
                    <td class="text-muted">{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</td>
                    <td class="text-muted">{{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</td>
                    <td style="font-weight: 600; color: var(--color-dark);">{{ $request->total_days }}</td>
                    <td>
                        @if($request->status == 'approved')
                            <span class="status-approved">Approved</span>
                        @elseif($request->status == 'pending')
                            <span class="status-pending">Pending</span>
                        @else
                            <span class="status-rejected">Rejected</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                    <td class="text-muted" style="font-size: 9px;">{{ Str::limit($request->remarks ?? '-', 30) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--color-muted); padding: 20px;">No leave requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @php
        $totalRequests = $leaveRequests->count();
        $approvedCount = $leaveRequests->where('status', 'approved')->count();
        $pendingCount = $leaveRequests->where('status', 'pending')->count();
        $rejectedCount = $leaveRequests->where('status', 'rejected')->count();
        $totalDays = $leaveRequests->where('status', 'approved')->sum('total_days');
    @endphp
    
    <div class="summary-stats">
        <strong>Summary:</strong> 
        Total Requests: {{ $totalRequests }} | 
        Approved: {{ $approvedCount }} | 
        Pending: {{ $pendingCount }} | 
        Rejected: {{ $rejectedCount }} | 
        Total Approved Days: {{ $totalDays }}
    </div>
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-row">
                <div class="signature-col">
                    <div class="signature-line"></div>
                    <div class="signature-name">Hellen K. Safu</div>
                    <div class="signature-title">Human Resources Manager</div>
                    <div class="signature-date-line">
                        <span>Date: ________________________</span>
                    </div>
                </div>
                
                <div class="signature-col">
                    <div class="signature-line"></div>
                    <div class="signature-name">Benard Kiplagat</div>
                    <div class="signature-title">Chief Executive Officer</div>
                    <div class="signature-date-line">
                        <span>Date: ________________________</span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            
            </div>
    </div>
    
    <div class="footer">
        <p>DSL Systems & Solutions Limited | Confidential Report</p>
    </div>
</body>
</html>