<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Leave Summary</title>
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
            padding: 15px;
            background-color: #FFFFFF;
            -webkit-print-color-adjust: exact;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid var(--color-brand);
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: var(--color-dark);
            font-size: 20px;
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
            margin: 3px 0;
            color: var(--color-muted);
            font-size: 9px;
        }

        /* Complex Grid Layout Adjustments */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }

        th, td {
            border: 1px solid var(--color-border-light);
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: var(--color-bg-light);
            color: var(--color-dark);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.03em;
            text-align: center;
        }

        thead tr:first-child th {
            border-bottom: 1px solid var(--color-border);
        }

        tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        .leave-type-cell {
            text-align: left;
            min-width: 85px;
        }

        .leave-details {
            font-size: 8px;
            line-height: 1.4;
            color: #495057;
        }

        .leave-details strong {
            color: var(--color-dark);
        }

        /* Clean Total Columns & Summary Rows */
        .total-row {
            font-weight: 700;
        }

        .total-row td {
            background-color: var(--color-bg-light) !important;
            color: var(--color-dark) !important;
            border-top: 2px solid var(--color-border);
            border-bottom: 2px solid var(--color-border);
        }

        /* Secure Signature Workflow Modules */
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
            margin-top: 40px;
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
            font-size: 9.5px;
            color: var(--color-muted);
            margin-top: 2px;
        }

        .signature-date-line {
            margin-top: 6px;
            font-size: 9.5px;
            color: var(--color-muted);
        }

        .stamp-box {
            margin-top: 30px;
            text-align: center;
        }

        .stamp-placeholder {
            border: 1px dashed var(--color-border);
            padding: 12px 28px;
            display: inline-block;
            border-radius: 4px;
            color: var(--color-muted);
            font-size: 9px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            background-color: #fafafa;
            font-style: normal;
        }

        .clearfix {
            clear: both;
        }

        .report-date {
            margin-top: 20px;
            font-size: 9px;
            color: var(--color-muted);
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
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
        <h2>Employee Leave Summary Report</h2>
        <p>Generated on: {{ $generated_date }}</p>
        <p style="font-weight: 600; color: var(--color-dark);">Reporting Year: {{ $year }}</p>
        @if($department)
            <p>Department: {{ $department }}</p>
        @else
            <p>Department: All Departments</p>
        @endif
    </div>
    
    @php
        // Get all unique leave types for the header
        $leaveTypes = [];
        foreach($employees as $employee) {
            foreach($employee->leaveBalances as $balance) {
                if (!in_array($balance->leaveType->name, $leaveTypes)) {
                    $leaveTypes[] = $balance->leaveType->name;
                }
            }
        }
        
        // Calculate totals
        $totalEmployees = $employees->count();
        $grandTotalDays = 0;
        $grandUsedDays = 0;
        $grandRemainingDays = 0;
    @endphp
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">#</th>
                <th rowspan="2" style="width: 10%;">Employee ID</th>
                <th rowspan="2" style="width: 18%;">Employee Name</th>
                <th rowspan="2" style="width: 12%;">Department</th>
                <th rowspan="2" style="width: 10%;">Join Date</th>
                <th colspan="{{ count($leaveTypes) }}" style="text-align: center;">Leave Type Details</th>
                <th rowspan="2" style="width: 8%;">Total<br>Allocated</th>
                <th rowspan="2" style="width: 8%;">Total<br>Used</th>
                <th rowspan="2" style="width: 8%;">Total<br>Remaining</th>
            </tr>
            <tr>
                @foreach($leaveTypes as $leaveType)
                    <th style="text-align: center; font-size: 8px;">{{ $leaveType }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($employees as $employee)
                @php
                    $employeeTotalDays = 0;
                    $employeeUsedDays = 0;
                    $employeeRemainingDays = 0;
                    $leaveTypeValues = [];
                    
                    foreach($leaveTypes as $leaveTypeName) {
                        $found = false;
                        foreach($employee->leaveBalances as $balance) {
                            if($balance->leaveType->name == $leaveTypeName) {
                                $leaveTypeValues[$leaveTypeName] = [
                                    'total' => $balance->total_days,
                                    'used' => $balance->used_days,
                                    'remaining' => $balance->remaining_days
                                ];
                                $employeeTotalDays += $balance->total_days;
                                $employeeUsedDays += $balance->used_days;
                                $employeeRemainingDays += $balance->remaining_days;
                                $found = true;
                                break;
                            }
                        }
                        if(!$found) {
                            $leaveTypeValues[$leaveTypeName] = ['total' => 0, 'used' => 0, 'remaining' => 0];
                        }
                    }
                    
                    $grandTotalDays += $employeeTotalDays;
                    $grandUsedDays += $employeeUsedDays;
                    $grandRemainingDays += $employeeRemainingDays;
                @endphp
                <tr>
                    <td style="text-align: center; color: var(--color-muted);">{{ $counter++ }}</td>
                    <td><span style="font-weight: 600;">{{ $employee->employee_id }}</span></td>
                    <td><span style="font-weight: 600; color: var(--color-dark);">{{ $employee->name }}</span></td>
                    <td class="text-muted">{{ $employee->department ?? 'N/A' }}</td>
                    <td class="text-muted">{{ $employee->join_date ?? 'N/A' }}</td>
                    
                    @foreach($leaveTypes as $leaveTypeName)
                        <td class="leave-type-cell leave-details">
                            @if($leaveTypeValues[$leaveTypeName]['total'] > 0)
                                <div>Allocated: {{ $leaveTypeValues[$leaveTypeName]['total'] }}</div>
                                <div>Used: {{ $leaveTypeValues[$leaveTypeName]['used'] }}</div>
                                <div><strong>Remaining: {{ $leaveTypeValues[$leaveTypeName]['remaining'] }}</strong></div>
                            @else
                                <div style="text-align: center; color: var(--color-muted);">—</div>
                            @endif
                        </td>
                    @endforeach
                    
                    <td style="text-align: center; font-weight: 600;">{{ $employeeTotalDays }}</td>
                    <td style="text-align: center; font-weight: 600;">{{ $employeeUsedDays }}</td>
                    <td style="text-align: center; font-weight: 700; color: var(--color-brand);">{{ $employeeRemainingDays }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 5 + count($leaveTypes) + 3 }}" style="text-align: center; color: var(--color-muted); padding: 20px;">No employees found.</td>
                </tr>
            @endforelse
            
            {{-- Totals Row --}}
            @if($employees->count() > 0)
                <tr class="total-row">
                    <td colspan="5" style="text-align: right; text-transform: uppercase; letter-spacing: 0.02em;"><strong>Grand Totals:</strong></td>
                    @foreach($leaveTypes as $leaveTypeName)
                        @php
                            $typeTotal = 0;
                            $typeUsed = 0;
                            $typeRemaining = 0;
                            foreach($employees as $employee) {
                                foreach($employee->leaveBalances as $balance) {
                                    if($balance->leaveType->name == $leaveTypeName) {
                                        $typeTotal += $balance->total_days;
                                        $typeUsed += $balance->used_days;
                                        $typeRemaining += $balance->remaining_days;
                                    }
                                }
                            }
                        @endphp
                        <td class="leave-type-cell leave-details">
                            <div>Total: {{ $typeTotal }}</div>
                            <div>Used: {{ $typeUsed }}</div>
                            <div><strong>Remaining: {{ $typeRemaining }}</strong></div>
                        </td>
                    @endforeach
                    <td style="text-align: center;">{{ $grandTotalDays }}</td>
                    <td style="text-align: center;">{{ $grandUsedDays }}</td>
                    <td style="text-align: center; color: var(--color-brand);">{{ $grandRemainingDays }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    
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