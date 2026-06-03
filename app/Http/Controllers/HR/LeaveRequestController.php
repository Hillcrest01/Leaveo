<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user', 'leaveType', 'approver']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show pending first
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')");
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('department', $request->department);
            });
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }
        
        // Search by employee name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get departments for filter
        $departments = User::where('role', 'employee')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');
        
        // Get statistics
        $stats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'total' => LeaveRequest::count(),
        ];
        
        return view('hr.leave-requests.index', compact('leaveRequests', 'departments', 'stats'));
    }

    /**
     * Display a specific leave request for review
     */
    public function show(LeaveRequest $leaveRequest)
{
    // dd('hereee');
    // Debug: Check if we're getting the data
    \Log::info('Leave Request Show Method Called', ['id' => $leaveRequest->id]);
    
    // Load relationships
    $leaveRequest->load(['user', 'leaveType', 'approver']);
    
    // Debug: Check if user exists
    \Log::info('Leave Request Data', [
        'user_name' => $leaveRequest->user->name ?? 'No user',
        'leave_type' => $leaveRequest->leaveType->name ?? 'No leave type'
    ]);
    
    // Get employee's current leave balances
    $balances = $leaveRequest->user->leaveBalances()
        ->with('leaveType')
        ->where('year', date('Y'))
        ->get();
    
    // Get employee's leave history
    $history = $leaveRequest->user->leaveRequests()
        ->with('leaveType')
        ->where('id', '!=', $leaveRequest->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        // dd($history);
    
    // Debug: Check if view exists
    if (!view()->exists('hr.leave-requests.show')) {
        \Log::error('View hr.leave-requests.show does not exist!');
        abort(500, 'View file not found. Please create resources/views/hr/leave-requests/show.blade.php');
    }
    
    return view('hr.leave-requests.show', compact('leaveRequest', 'balances', 'history'));
}

    /**
     * Approve a leave request
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        // Only pending requests can be approved
        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This leave request has already been processed.');
        }
        
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Get employee's leave balance
            $balance = LeaveBalance::where('user_id', $leaveRequest->user_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)
                ->where('year', date('Y'))
                ->first();
            
            if (!$balance) {
                throw new \Exception('Leave balance not found for this employee.');
            }
            
            // Check if enough balance remains
            if ($balance->remaining_days < $leaveRequest->total_days) {
                throw new \Exception("Insufficient balance. Employee has {$balance->remaining_days} days remaining but requested {$leaveRequest->total_days} days.");
            }
            
            // Update leave balance
            $balance->used_days += $leaveRequest->total_days;
            $balance->remaining_days -= $leaveRequest->total_days;
            $balance->save();
            
            // Update leave request
            $leaveRequest->status = 'approved';
            $leaveRequest->approved_by = Auth::id();
            $leaveRequest->approved_at = now();
            $leaveRequest->remarks = $request->remarks;
            $leaveRequest->save();
            
            DB::commit();
            
            return redirect()->route('hr.leave-requests.index')
                ->with('success', "Leave request for {$leaveRequest->user->name} has been approved. {$leaveRequest->total_days} day(s) deducted from their balance.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to approve leave: ' . $e->getMessage());
        }
    }

    /**
     * Reject a leave request
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        // Only pending requests can be rejected
        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This leave request has already been processed.');
        }
        
        $request->validate([
            'remarks' => 'required|string|min:10|max:500',
        ]);
        
        // Update leave request
        $leaveRequest->status = 'rejected';
        $leaveRequest->approved_by = Auth::id();
        $leaveRequest->approved_at = now();
        $leaveRequest->remarks = $request->remarks;
        $leaveRequest->save();
        
        return redirect()->route('hr.leave-requests.index')
            ->with('success', "Leave request for {$leaveRequest->user->name} has been rejected.");
    }

    /**
     * Bulk approve selected leave requests
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:leave_requests,id',
        ]);
        
        $successCount = 0;
        $failCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($request->request_ids as $id) {
                $leaveRequest = LeaveRequest::find($id);
                
                // Skip if not pending
                if ($leaveRequest->status !== 'pending') {
                    $failCount++;
                    $errors[] = "Request #{$id} is not pending.";
                    continue;
                }
                
                // Get balance
                $balance = LeaveBalance::where('user_id', $leaveRequest->user_id)
                    ->where('leave_type_id', $leaveRequest->leave_type_id)
                    ->where('year', date('Y'))
                    ->first();
                
                if (!$balance || $balance->remaining_days < $leaveRequest->total_days) {
                    $failCount++;
                    $errors[] = "Insufficient balance for {$leaveRequest->user->name} ({$leaveRequest->leaveType->name}).";
                    continue;
                }
                
                // Update balance
                $balance->used_days += $leaveRequest->total_days;
                $balance->remaining_days -= $leaveRequest->total_days;
                $balance->save();
                
                // Update request
                $leaveRequest->status = 'approved';
                $leaveRequest->approved_by = Auth::id();
                $leaveRequest->approved_at = now();
                $leaveRequest->save();
                
                $successCount++;
            }
            
            DB::commit();
            
            $message = "Bulk approval completed. Approved: {$successCount}, Failed: {$failCount}.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(' ', array_slice($errors, 0, 3));
            }
            
            return redirect()->route('hr.leave-requests.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Bulk approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Display leave calendar view
     */
    public function calendar(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        
        // Get all approved leaves for the selected month
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $leaveRequests = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();
        
        // Organize leaves by date
        $calendarData = [];
        foreach ($leaveRequests as $leave) {
            $current = max($leave->start_date, $startDate);
            $end = min($leave->end_date, $endDate);
            
            while ($current <= $end) {
                $dateKey = $current;
                if (!isset($calendarData[$dateKey])) {
                    $calendarData[$dateKey] = [];
                }
                $calendarData[$dateKey][] = [
                    'employee' => $leave->user->name,
                    'department' => $leave->user->department,
                    'type' => $leave->leaveType->name,
                    'request_id' => $leave->id,
                ];
                $current = date('Y-m-d', strtotime($current . ' +1 day'));
            }
        }
        
        return view('hr.leave-requests.calendar', compact('calendarData', 'year', 'month'));
    }

    /**
     * Export leave reports
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:monthly,yearly',
            'month' => 'required_if:type,monthly|nullable|date_format:Y-m',
            'year' => 'required_if:type,yearly|nullable|integer|min:2020|max:2100',
        ]);
        
        if ($request->type === 'monthly') {
            $startDate = $request->month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $filename = "leave_report_{$request->month}.csv";
        } else {
            $startDate = $request->year . '-01-01';
            $endDate = $request->year . '-12-31';
            $filename = "leave_report_{$request->year}.csv";
        }
        
        $leaveRequests = LeaveRequest::with(['user', 'leaveType', 'approver'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Generate CSV
        $handle = fopen('php://temp', 'w+');
        
        // Headers
        fputcsv($handle, [
            'Request ID', 'Employee Name', 'Employee ID', 'Department', 
            'Leave Type', 'Start Date', 'End Date', 'Total Days', 
            'Status', 'Reason', 'Remarks', 'Submitted', 'Processed By', 'Processed At'
        ]);
        
        // Data
        foreach ($leaveRequests as $request) {
            fputcsv($handle, [
                $request->id,
                $request->user->name,
                $request->user->employee_id,
                $request->user->department,
                $request->leaveType->name,
                $request->start_date,
                $request->end_date,
                $request->total_days,
                $request->status,
                $request->reason,
                $request->remarks ?? '',
                $request->created_at,
                $request->approver->name ?? '-',
                $request->approved_at ?? '-',
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}