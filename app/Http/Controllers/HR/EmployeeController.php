<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
        public function index(Request $request){
            $query = User::where('role', 'employee');
           
            if($request->filled('search')){
                $search = $request->search;
                $query->where(function($q) use ($search){
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('email','like', "%$search%")
                    ->orWhere('employee_id', 'like', "%$search%")
                    ->orWhere('department','like', "%$search%");
                });
            }
            if($request->filled('department')){
                $query->where('department', $request->department);
            }

             $employees = $query->orderBy('name')->paginate(10);
            // dd($employees);

            $departments = User::where('role', 'employee')->whereNotNull('department')->distinct()->pluck('department');

            return view('hr.employees.index', compact('employees','departments'));
        }

        public function create(){
            $leaveTypes = LeaveType::where('is_active', true);
            return view('hr.employees.create', compact('leaveTypes'));
        }

public function store(Request $request){
    // dd($request->all());
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',  // Added email validation and unique
        'employee_id' => 'required|unique:users,employee_id',  // Added unique
        'department' => 'required',
        'join_date' => 'required|date',
        'phone' => 'required',
        'gender' => 'required|in:male,female,other',  // ADDED gender validation
        'password' => 'required|min:8|confirmed',
    ]);

    DB::beginTransaction();
    try {
        $employee = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'department' => $validated['department'],
            'join_date' => $validated['join_date'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],  // ADDED gender
            'password' => Hash::make($validated['password']),  // FIXED: Hash the password
            'role' => 'employee',  // ADDED role
        ]);

        $this->createLeaveBalances($employee);
        DB::commit();

        return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully with applicable leave balances!');
    } catch(Exception $e){
        DB::rollBack();
        return redirect()->back()->with('error', 'Employee creation failed: ' . $e->getMessage())->withInput();
    }
}

private function createLeaveBalances(User $employee){
    // Get active leave types filtered by employee's gender
    $leaveTypes = LeaveType::where('is_active', true)
        ->where(function($query) use ($employee) {
            $query->where('gender_restriction', 'both')
                ->orWhere('gender_restriction', $employee->gender);
        })
        ->get();
    
    foreach($leaveTypes as $leaveType){
        LeaveBalance::create([
            'user_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'year' => date('Y'),
            'total_days' => $leaveType->days_per_year,
            'used_days' => 0,
            'remaining_days' => $leaveType->days_per_year,
        ]);
    }
}

    public function show(User $employee){
        if($employee->role !== 'employee'){
            return redirect()->route('hr.employee.index')->with('error', 'Invalid employee record');
        }
        $leaveBalances = $employee->leaveBalances()->with('leaveType')->where('year', date('Y'))->get();
        $recentRequests = $employee->leaveRequests()->with('leaveType')->orderBy('created_at', 'desc')->limit(10)->get();
        return view('hr.employees.show', compact('employee', 'leaveBalances', 'recentRequests'));
    }

    public function edit(User $employee){
        if($employee->role !== 'employee'){
            return redirect()->back()->with('error', 'Invalid employee record');
        }
        return view('hr.employees.edit', compact('employee'));
    }

    public function update(User $employee, Request $request){
         $validated = $request->validate([
               'name' => 'required|string|max:255',
               'email' =>'required', 
               'employee_id' =>'required', 
               'department' =>'required', 
               'join_date' =>'required|date|', 
               'phone' =>'required', 
            ]);

        $employee->update($validated);
        return redirect()->route('hr.employees.index')->with('success', 'Updated successfully');
    }

    public function destroy(User $employee){
        $pendingRequests = $employee->leaveRequests()->where('status', 'pending')->exists();
        if($pendingRequests){
            return redirect()->route('hr.employees.index')->with('error', 'Cannot delete employees with leave requests, please act on them first before deleting this employee');
        }
        $employee->delete();
        return redirect()->route('hr.employees.index')->with('success', 'deleted successfully');
    }

    public function resetPassword(REQUEST $request, User $employee){
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $employee->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('hr.employees.index')->with('success', 'password reset successful');

    }
}
