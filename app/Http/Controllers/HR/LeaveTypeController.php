<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types
     */
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        return view('hr.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show form to create a new leave type
     */
    public function create()
    {
        return view('hr.leave-types.create');
    }

    /**
     * Store a newly created leave type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name',
            'description' => 'nullable|string',
            'days_per_year' => 'required|integer|min:0|max:365',
            'gender_restriction' => 'required|in:both,male,female',
            'is_active' => 'boolean',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create leave type
            $leaveType = LeaveType::create($validated);
            
            // Apply to eligible employees
            $this->applyLeaveTypeToEligibleEmployees($leaveType);
            
            DB::commit();
            
            return redirect()->route('hr.leave-types.index')
                ->with('success', 'Leave type created successfully and applied to eligible employees!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create leave type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit a leave type
     */
    public function edit(LeaveType $leaveType)
    {
        return view('hr.leave-types.edit', compact('leaveType'));
    }

    /**
     * Update the specified leave type
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
            'days_per_year' => 'required|integer|min:0|max:365',
            'gender_restriction' => 'required|in:both,male,female',
            'is_active' => 'boolean',
        ]);
        
        DB::beginTransaction();
        
        try {
            $leaveType->update($validated);
            
            // Sync with eligible employees
            $this->syncLeaveTypeToEligibleEmployees($leaveType);
            
            DB::commit();
            
            return redirect()->route('hr.leave-types.index')
                ->with('success', 'Leave type updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update leave type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified leave type
     */
    public function destroy(LeaveType $leaveType)
    {
        // Check if this leave type has any leave requests
        if ($leaveType->leaveRequests()->exists()) {
            return redirect()->route('hr.leave-types.index')
                ->with('error', 'Cannot delete this leave type because it has associated leave requests.');
        }
        
        // Check if this leave type has any leave balances
        if ($leaveType->leaveBalances()->exists()) {
            return redirect()->route('hr.leave-types.index')
                ->with('error', 'Cannot delete this leave type because it has associated leave balances.');
        }
        
        $leaveType->delete();
        
        return redirect()->route('hr.leave-types.index')
            ->with('success', 'Leave type deleted successfully!');
    }
    
    /**
     * Toggle active status of leave type
     */
    public function toggleStatus(LeaveType $leaveType)
    {
        $leaveType->is_active = !$leaveType->is_active;
        $leaveType->save();
        
        $status = $leaveType->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('hr.leave-types.index')
            ->with('success', "Leave type {$status} successfully!");
    }
    
    /**
     * Apply leave type to eligible employees
     */
    private function applyLeaveTypeToEligibleEmployees(LeaveType $leaveType)
    {
        // Get all eligible employees based on gender restriction
        $query = User::where('role', 'employee');
        
        if ($leaveType->gender_restriction == 'male') {
            $query->where('gender', 'male');
        } elseif ($leaveType->gender_restriction == 'female') {
            $query->where('gender', 'female');
        }
        
        $employees = $query->get();
        $currentYear = date('Y');
        
        foreach ($employees as $employee) {
            // Check if balance already exists
            $exists = LeaveBalance::where('user_id', $employee->id)
                ->where('leave_type_id', $leaveType->id)
                ->where('year', $currentYear)
                ->exists();
            
            if (!$exists) {
                LeaveBalance::create([
                    'user_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $currentYear,
                    'total_days' => $leaveType->days_per_year,
                    'used_days' => 0,
                    'remaining_days' => $leaveType->days_per_year,
                ]);
            }
        }
    }
    
    /**
     * Sync leave type with eligible employees (add/remove as needed)
     */
    private function syncLeaveTypeToEligibleEmployees(LeaveType $leaveType)
    {
        $currentYear = date('Y');
        
        // Get eligible employees based on gender restriction
        $query = User::where('role', 'employee');
        
        if ($leaveType->gender_restriction == 'male') {
            $query->where('gender', 'male');
        } elseif ($leaveType->gender_restriction == 'female') {
            $query->where('gender', 'female');
        }
        
        $eligibleEmployees = $query->get();
        $eligibleIds = $eligibleEmployees->pluck('id')->toArray();
        
        // Remove balances for ineligible employees
        LeaveBalance::where('leave_type_id', $leaveType->id)
            ->where('year', $currentYear)
            ->whereNotIn('user_id', $eligibleIds)
            ->delete();
        
        // Add balances for new eligible employees
        foreach ($eligibleEmployees as $employee) {
            $exists = LeaveBalance::where('user_id', $employee->id)
                ->where('leave_type_id', $leaveType->id)
                ->where('year', $currentYear)
                ->exists();
            
            if (!$exists) {
                LeaveBalance::create([
                    'user_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $currentYear,
                    'total_days' => $leaveType->days_per_year,
                    'used_days' => 0,
                    'remaining_days' => $leaveType->days_per_year,
                ]);
            } else {
                // Update days if changed
                $balance = LeaveBalance::where('user_id', $employee->id)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('year', $currentYear)
                    ->first();
                
                if ($balance && $balance->total_days != $leaveType->days_per_year) {
                    $balance->total_days = $leaveType->days_per_year;
                    $balance->remaining_days = $leaveType->days_per_year - $balance->used_days;
                    $balance->save();
                }
            }
        }
    }
}