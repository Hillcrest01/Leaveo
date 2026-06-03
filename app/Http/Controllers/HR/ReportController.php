<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Main reports dashboard
     */
    public function index()
    {
        $currentYear = date('Y');
        
        // Department statistics
        $departmentStats = User::where('role', 'employee')
            ->select('department', DB::raw('count(*) as total_employees'))
            ->groupBy('department')
            ->get();
        
        // Leave trends by month
        $monthlyTrends = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyTrends[$month] = LeaveRequest::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->where('status', 'approved')
                ->sum('total_days');
        }
        
        // Leave type utilization
        $leaveTypeUtilization = LeaveType::withCount(['leaveRequests as total_requests' => function($q) use ($currentYear) {
                $q->whereYear('created_at', $currentYear);
            }])
            ->withSum(['leaveRequests as total_days_used' => function($q) use ($currentYear) {
                $q->whereYear('created_at', $currentYear)->where('status', 'approved');
            }], 'total_days')
            ->get();
        
        // Top employees with most leaves
        $topLeaveTakers = User::where('role', 'employee')
            ->withSum(['leaveRequests as total_leaves' => function($q) use ($currentYear) {
                $q->whereYear('created_at', $currentYear)->where('status', 'approved');
            }], 'total_days')
            ->orderBy('total_leaves', 'desc')
            ->limit(5)
            ->get();
        
        // Pending requests count
        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        
        // This month's leave summary
        $thisMonthLeaves = LeaveRequest::whereYear('start_date', $currentYear)
            ->whereMonth('start_date', date('m'))
            ->where('status', 'approved')
            ->sum('total_days');
        
        return view('hr.reports.index', compact(
            'departmentStats',
            'monthlyTrends',
            'leaveTypeUtilization',
            'topLeaveTakers',
            'pendingCount',
            'thisMonthLeaves',
            'currentYear'
        ));
    }
    
    /**
     * Employee leave summary report (View)
     */
    public function employeeSummary(Request $request)
    {
        $year = $request->year ?? date('Y');
        $department = $request->department;
        
        $query = User::where('role', 'employee');
        
        if ($department) {
            $query->where('department', $department);
        }
        
        $employees = $query->with(['leaveBalances' => function($q) use ($year) {
            $q->where('year', $year)->with('leaveType');
        }])->get();
        
        $departments = User::where('role', 'employee')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');
        
        return view('hr.reports.employee-summary', compact('employees', 'departments', 'year'));
    }
    
    /**
     * Export Employee Summary as PDF
     */
    public function exportEmployeeSummaryPDF(Request $request)
    {
        $year = $request->year ?? date('Y');
        $department = $request->department;
        
        $query = User::where('role', 'employee');
        
        if ($department) {
            $query->where('department', $department);
        }
        
        $employees = $query->with(['leaveBalances' => function($q) use ($year) {
            $q->where('year', $year)->with('leaveType');
        }])->get();
        
        $data = [
            'employees' => $employees,
            'year' => $year,
            'department' => $department,
            'generated_date' => now()->format('F d, Y h:i A'),
            'company_name' => config('app.name'),
        ];
        
        $pdf = Pdf::loadView('hr.reports.pdf.employee-summary', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = "employee_leave_summary_{$year}.pdf";
        if ($department) {
            $filename = "employee_leave_summary_{$department}_{$year}.pdf";
        }
        
        return $pdf->download($filename);
    }
    
    /**
     * Department wise leave report (View)
     */
    public function departmentReport(Request $request)
    {
        $year = $request->year ?? date('Y');
        
        $departmentReport = User::where('role', 'employee')
            ->select('department', DB::raw('count(*) as employee_count'))
            ->groupBy('department')
            ->get();
        
        foreach ($departmentReport as $dept) {
            // Total leaves taken by department
            $dept->total_leaves = LeaveRequest::whereHas('user', function($q) use ($dept) {
                    $q->where('department', $dept->department);
                })
                ->whereYear('created_at', $year)
                ->where('status', 'approved')
                ->sum('total_days');
            
            // Average leaves per employee
            $dept->avg_leaves = $dept->employee_count > 0 
                ? round($dept->total_leaves / $dept->employee_count, 1) 
                : 0;
            
            // Pending requests in department
            $dept->pending_requests = LeaveRequest::whereHas('user', function($q) use ($dept) {
                    $q->where('department', $dept->department);
                })
                ->where('status', 'pending')
                ->count();
        }
        
        return view('hr.reports.department-report', compact('departmentReport', 'year'));
    }
    
    /**
     * Export Department Report as PDF
     */
    public function exportDepartmentReportPDF(Request $request)
    {
        $year = $request->year ?? date('Y');
        
        $departmentReport = User::where('role', 'employee')
            ->select('department', DB::raw('count(*) as employee_count'))
            ->groupBy('department')
            ->get();
        
        foreach ($departmentReport as $dept) {
            $dept->total_leaves = LeaveRequest::whereHas('user', function($q) use ($dept) {
                    $q->where('department', $dept->department);
                })
                ->whereYear('created_at', $year)
                ->where('status', 'approved')
                ->sum('total_days');
            
            $dept->avg_leaves = $dept->employee_count > 0 
                ? round($dept->total_leaves / $dept->employee_count, 1) 
                : 0;
            
            $dept->pending_requests = LeaveRequest::whereHas('user', function($q) use ($dept) {
                    $q->where('department', $dept->department);
                })
                ->where('status', 'pending')
                ->count();
        }
        
        $data = [
            'departmentReport' => $departmentReport,
            'year' => $year,
            'generated_date' => now()->format('F d, Y h:i A'),
            'company_name' => config('app.name'),
        ];
        
        $pdf = Pdf::loadView('hr.reports.pdf.department-report', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = "department_report_{$year}.pdf";
        
        return $pdf->download($filename);
    }
    
    /**
     * Export Leave Requests Report as PDF
     */
    public function exportLeaveRequestsPDF(Request $request)
    {
        $status = $request->status;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        
        $query = LeaveRequest::with(['user', 'leaveType', 'approver']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->get();
        
        $data = [
            'leaveRequests' => $leaveRequests,
            'status' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generated_date' => now()->format('F d, Y h:i A'),
            'company_name' => config('app.name'),
        ];
        
        $pdf = Pdf::loadView('hr.reports.pdf.leave-requests', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = "leave_requests_report.pdf";
        if ($status) {
            $filename = "leave_requests_{$status}_report.pdf";
        }
        
        return $pdf->download($filename);
    }
}