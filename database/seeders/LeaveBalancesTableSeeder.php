<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;

class LeaveBalancesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get all employees (users with role 'employee')
        $employees = User::where('role', 'employee')->get();
        
        // Get all active leave types
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        $currentYear = date('Y');
        
        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {
                // Check if balance already exists for this employee, leave type, and year
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
        
        $this->command->info('Leave balances created for all employees!');
    }
}