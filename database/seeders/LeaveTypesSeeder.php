<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create([
            'name' => 'Annual Leave',
            'description' => 'Regular paid leave days',
            'days_per_year' => 21,
            'is_active' => true,
        ]);

        LeaveType::create([
            'name' => 'Sick Leave',
            'description' => 'Sick Leave per year',
            'days_per_year' => 12,
            'is_active' => true,
        ]);

        LeaveType::create([
            'name' => 'Maternity Leave',
            'description' => 'Maternity',
            'days_per_year' => 120,
            'is_active' => true,
        ]);
        LeaveType::create([
            'name' => 'Paternity Leave',
            'description' => 'Paternity',
            'days_per_year' => 14,
            'is_active' => true,
        ]);
    }
}
