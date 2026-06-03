<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Department Leave Report</title>
    <style>
        /* Modern Native Print & Report CSS Architecture */
        :root {
            --color-brand: #F15929;
            --color-brand-light: #fff0ec;
            --color-dark: #231F20;
            --color-muted: #6c757d;
            --color-border: #ced4da;
            --color-border-light: #e9ecef;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: var(--color-dark);
            margin: 0;
            padding: 20px;
            background-color: #FFFFFF;
            -webkit-print-color-adjust: exact;
        }

        .header {
            margin-bottom: 25px;
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
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .header-meta {
            font-size: 11px;
            color: var(--color-muted);
            margin-top: 6px;
        }

        /* Clean Enterprise Grid Overhaul */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid var(--color-border-light);
            padding: 10px 12px;
            text-align: left;
            font-size: 11.5px;
        }

        th {
            background-color: #f8f9fa;
            color: var(--color-dark);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--color-border);
        }

        td {
            color: #333333;
        }

        tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        /* Minimalist Solid Statistics Overview Block */
        .stat-box {
            margin-top: 25px;
            margin-bottom: 30px;
            padding: 16px;
            background-color: #f8f9fa;
            border: 1px solid var(--color-border-light);
            border-radius: 4px;
        }

        .stat-box h3 {
            color: var(--color-dark);
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .stat-box p {
            color: #495057;
            margin: 6px 0;
            font-size: 12px;
        }

        .stat-box strong {
            color: var(--color-dark);
            font-weight: 600;
        }

        /* Secure Validation Layout blocks */
        .signature-section {
            margin-top: 50px;
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
            margin-top: 8px;
            font-weight: 700;
            font-size: 12px;
            color: var(--color-dark);
        }

        .signature-title {
            font-size: 11px;
            color: var(--color-muted);
            margin-top: 2px;
        }

        .signature-date-line {
            margin-top: 8px;
            font-size: 11px;
            color: var(--color-muted);
        }

        .stamp-box {
            margin-top: 40px;
            text-align: center;
        }

        .stamp-placeholder {
            border: 1px dashed var(--color-border);
            padding: 18px 36px;
            display: inline-block;
            border-radius: 4px;
            color: var(--color-muted);
            font-size: 11px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            background-color: #fafafa;
        }

        .clearfix {
            clear: both;
        }

        .report-date {
            margin-top: 30px;
            font-size: 11px;
            color: var(--color-muted);
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9.5px;
            border-top: 1px solid var(--color-border-light);
            padding-top: 8px;
            color: var(--color-muted);
            background-color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DSL Systems & Solutions Limited</h1>
        <h2>Department Leave Report</h2>
        <div class="header-meta">
            <div>Generated on: {{ $generated_date }}</div>
            <div style="font-weight: 600; color: var(--color-dark); margin-top: 2px;">Reporting Year: {{ $year }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th>Total Employees</th>
                <th>Total Leaves Taken</th>
                <th>Average Leaves/Employee</th>
                <th>Pending Requests</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departmentReport as $dept)
                <tr>
                    <td><span style="font-weight: 600; color: var(--color-dark);">{{ $dept->department }}</span></td>
                    <td>{{ $dept->employee_count }}</td>
                    <td>{{ $dept->total_leaves }} days</td>
                    <td>{{ $dept->avg_leaves }} days</td>
                    <td>
                        @if($dept->pending_requests > 0)
                            <span style="color: var(--color-brand); font-weight: 700;">{{ $dept->pending_requests }}</span>
                        @else
                            <span style="color: var(--color-muted);">0</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--color-muted);">No departments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @php
        $totalEmployees = $departmentReport->sum('employee_count');
        $totalLeaves = $departmentReport->sum('total_leaves');
        $totalPending = $departmentReport->sum('pending_requests');
    @endphp
    
    <div class="stat-box">
        <h3>Summary Statistics</h3>
        <p><strong>Total Employees:</strong> {{ $totalEmployees }}</p>
        <p><strong>Total Leaves Taken:</strong> {{ $totalLeaves }} days</p>
        <p><strong>Average Leaves per Employee:</strong> {{ $totalEmployees > 0 ? round($totalLeaves / $totalEmployees, 1) : 0 }} days</p>
        <p><strong>Total Pending Requests:</strong> {{ $totalPending }}</p>
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
            
            <div class="stamp-box">
                <div class="stamp-placeholder">
                    Company Stamp
                </div>
            </div>
        </div>
    </div>
    
    <div class="report-date">
        <p>This report is system-generated and requires signatures for validation.</p>
    </div>
    
    <div class="footer">
        <p>DSL Systems & Solutions Limited - Leave Management System | Confidential Report</p>
    </div>
</body>
</html>