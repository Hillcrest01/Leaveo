<?php

use App\Http\Controllers\Admin\GeneralController as AdminGeneralController;
use App\Http\Controllers\HR\LeaveTypeController;
use App\Http\Controllers\Employee\GeneralController as EmployeeGeneralController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\GeneralController as HRGeneralController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\hr\PublicHolidayController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(auth()->check()){
        $role = auth()->user()->role;
        // dd($role);
        if($role === 'admin'){
            return redirect()->route('admin.index');
        }
        elseif($role === 'hr'){
            return redirect()->route('hr.index');
        }
        else{
            return redirect()->route('employee.index');
        }
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('dashboard', [AdminGeneralController::class, 'index'])->name('index');
    Route::get('leave-types', [AdminGeneralController::class, 'leaveTypes'])->name('leave-types.index');
    });

   Route::middleware(['auth', 'role:hr'])->prefix('hr')->name('hr.')->group(function(){
    Route::get('dashboard', [HRGeneralController::class, 'index'])->name('index');
     Route::resource('leave-types', LeaveTypeController::class);
    Route::post('leave-types/{leaveType}/toggle-status', [LeaveTypeController::class, 'toggleStatus'])
        ->name('leave-types.toggle-status');

// Admin Public Holiday Routes
Route::resource('public-holidays', PublicHolidayController::class);
Route::post('public-holidays/{publicHoliday}/toggle-status', [PublicHolidayController::class, 'toggleStatus'])
    ->name('public-holidays.toggle-status');
Route::post('public-holidays/bulk-import', [PublicHolidayController::class, 'bulkImport'])
    ->name('public-holidays.bulk-import');

    // Employee Management Routes (use resource only once)
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    Route::put('employees/{employee}/reset-password', [\App\Http\Controllers\HR\EmployeeController::class, 'resetPassword'])
        ->name('employees.reset-password');
    Route::get('employees/export/csv', [\App\Http\Controllers\HR\EmployeeController::class, 'export'])
        ->name('employees.export');
    
    // Leave Request Management Routes 
    Route::get('leave-requests', [\App\Http\Controllers\HR\LeaveRequestController::class, 'index'])
        ->name('leave-requests.index');
    Route::get('leave-requests/{leaveRequest}', [\App\Http\Controllers\HR\LeaveRequestController::class, 'show'])
        ->name('leave-requests.show');
    Route::put('leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\HR\LeaveRequestController::class, 'approve'])
        ->name('leave-requests.approve');
    Route::put('leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\HR\LeaveRequestController::class, 'reject'])
        ->name('leave-requests.reject');
    Route::post('leave-requests/bulk-approve', [\App\Http\Controllers\HR\LeaveRequestController::class, 'bulkApprove'])
        ->name('leave-requests.bulk-approve');
    Route::get('leave-requests/calendar/view', [\App\Http\Controllers\HR\LeaveRequestController::class, 'calendar'])
        ->name('leave-requests.calendar');
    Route::get('leave-requests/export/report', [\App\Http\Controllers\HR\LeaveRequestController::class, 'export'])
        ->name('leave-requests.export');

        // Report Routes
// Report Routes (inside HR middleware group)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [\App\Http\Controllers\HR\ReportController::class, 'index'])->name('index');
    Route::get('employee-summary', [\App\Http\Controllers\HR\ReportController::class, 'employeeSummary'])->name('employee-summary');
    Route::get('department-report', [\App\Http\Controllers\HR\ReportController::class, 'departmentReport'])->name('department-report');
    
    // PDF Export Routes
    Route::get('export-employee-summary-pdf', [\App\Http\Controllers\HR\ReportController::class, 'exportEmployeeSummaryPDF'])
        ->name('export-employee-summary-pdf');
    Route::get('export-department-report-pdf', [\App\Http\Controllers\HR\ReportController::class, 'exportDepartmentReportPDF'])
        ->name('export-department-report-pdf');
    Route::get('export-leave-requests-pdf', [\App\Http\Controllers\HR\ReportController::class, 'exportLeaveRequestsPDF'])
        ->name('export-leave-requests-pdf');
});
});

// Employee Leave Request Routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Employee\LeaveRequestController::class, 'index'])->name('index');
    
    // Leave Request Routes
    Route::resource('leave-requests', \App\Http\Controllers\Employee\LeaveRequestController::class);
    Route::put('leave-requests/{leaveRequest}/cancel', [\App\Http\Controllers\Employee\LeaveRequestController::class, 'cancel'])
        ->name('leave-requests.cancel');
    Route::get('leave-balance/{leaveTypeId}', [\App\Http\Controllers\Employee\LeaveRequestController::class, 'getBalance'])
        ->name('leave-balance');
});


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
