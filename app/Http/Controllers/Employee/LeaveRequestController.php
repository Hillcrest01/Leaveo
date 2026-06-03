<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display employee dashboard with leave balances
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get current year leave balances
        $leaveBalances = $user->leaveBalances()
            ->with('leaveType')
            ->where('year', date('Y'))
            ->get();
        
        // Get recent leave requests (last 10)
        $recentRequests = $user->leaveRequests()
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get pending requests count
        $pendingCount = $user->leaveRequests()
            ->where('status', 'pending')
            ->count();
        
        // Get approved leaves this year (total days)
        $approvedDays = $user->leaveRequests()
            ->where('status', 'approved')
            ->whereYear('created_at', date('Y'))
            ->sum('total_days');
        
        return view('employee.dashboard', compact(
            'leaveBalances', 
            'recentRequests', 
            'pendingCount', 
            'approvedDays'
        ));
    }

    /**
     * Display list of employee's leave requests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->leaveRequests()->with('leaveType');
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by year if provided
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get available years for filter
        $years = $user->leaveRequests()
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('employee.leave-requests.index', compact('leaveRequests', 'years'));
    }

    /**
     * Show form to apply for leave
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get available leave types with remaining balance > 0
       $availableLeaveTypes = $user->leaveBalances()
    ->with('leaveType')
    ->where('year', date('Y'))
    ->where('remaining_days', '>', 0)
    ->whereHas('leaveType', function($query) {
        $query->where('is_active', true);
    })
    ->get()
    ->filter(function($balance) {
        // Additional check to ensure leave type is available for employee's gender
        return $balance->leaveType->is_active == true;
    })
    ->map(function($balance) {
        return (object) [
            'id' => $balance->leaveType->id,
            'name' => $balance->leaveType->name,
            'remaining_days' => $balance->remaining_days,
        ];
    });
        
        return view('employee.leave-requests.create', compact('availableLeaveTypes'));
    }

    /**
     * Calculate total days between dates (excluding weekends? Optional)
     */
    // private function calculateTotalDays($startDate, $endDate)
    // {
    //     $start = Carbon::parse($startDate);
    //     $end = Carbon::parse($endDate);
        
    //     // If we want to exclude weekends (optional)
    //     // Uncomment below code to exclude Saturdays and Sundays
    //     /*
    //     $days = 0;
    //     $current = $start->copy();
    //     while ($current <= $end) {
    //         if (!$current->isWeekend()) {
    //             $days++;
    //         }
    //         $current->addDay();
    //     }
    //     return $days;
    //     */
        
    //     // Simple calculation including all days
    //     return $start->diffInDays($end) + 1;
    // }

private function calculateTotalDays($startDate, $endDate)
{
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);
    
    $days = 0;
    $current = $start->copy();
    
    while ($current <= $end) {
        // Skip weekends (optional - remove if weekends are working days)
        if (!$current->isWeekend()) {
            
        // Skip public holidays
        if (!PublicHoliday::isHoliday($current)) {
            $days++;
        }
        }
        
        $current->addDay();
    }
    
    return $days;
}

    /**
     * Store a new leave request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            // 'start_date' => 'required|date|after_or_equal:today',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
        ]);
        
        // Get the leave type
        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        
        // Check if leave type is active
        if (!$leaveType->is_active) {
            return redirect()->back()
                ->with('error', 'This leave type is currently inactive.')
                ->withInput();
        }
        
        // Calculate total days
        $totalDays = $this->calculateTotalDays(
            $validated['start_date'], 
            $validated['end_date']
        );
        
        // Get current year balance
        $balance = $user->leaveBalances()
            ->where('leave_type_id', $leaveType->id)
            ->where('year', date('Y'))
            ->first();
        
        // Check if balance exists and has enough days
        if (!$balance) {
            return redirect()->back()
                ->with('error', 'No leave balance found for this leave type.')
                ->withInput();
        }
        
        if ($balance->remaining_days < $totalDays) {
            return redirect()->back()
                ->with('error', "Insufficient balance. You have {$balance->remaining_days} days remaining, but requested {$totalDays} days.")
                ->withInput();
        }
        
        // Check for overlapping leave requests
        $overlapping = $user->leaveRequests()
            ->where('status', '!=', 'rejected')
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();
        
        if ($overlapping) {
            return redirect()->back()
                ->with('error', 'You already have a leave request for these dates. Please check your existing requests.')
                ->withInput();
        }
        
        // Create the leave request
        $leaveRequest = $user->leaveRequests()->create([
            'leave_type_id' => $leaveType->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('employee.leave-requests.index')
            ->with('success', "Leave request submitted successfully! Your request for {$totalDays} day(s) is pending approval.");
    }

    /**
     * Display a specific leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Ensure employee can only view their own requests
        if ($leaveRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('employee.leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Cancel a pending leave request
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        // Ensure employee can only cancel their own requests
        if ($leaveRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Only pending requests can be cancelled
        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending leave requests can be cancelled.');
        }
        
        $leaveRequest->update(['status' => 'cancelled']);
        
        return redirect()->route('employee.leave-requests.index')
            ->with('success', 'Leave request cancelled successfully.');
    }
    
    /**
     * Get available leave balance for a specific leave type (AJAX)
     */
    public function getBalance($leaveTypeId)
    {
        $user = Auth::user();
        
        $balance = $user->leaveBalances()
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', date('Y'))
            ->first();
        
        return response()->json([
            'remaining_days' => $balance ? $balance->remaining_days : 0,
            'total_days' => $balance ? $balance->total_days : 0,
            'used_days' => $balance ? $balance->used_days : 0,
        ]);
    }
}