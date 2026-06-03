@extends('layouts.app')

@section('content')
<style>
    /* Corporate Design Token Extensions */
    .form-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #231F20;
        margin-bottom: 0.5rem;
    }

    .form-control {
        height: 40px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    textarea.form-control {
        height: auto;
    }

    .form-control:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Structural Clean Info Box */
    .system-notice {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 1rem;
        font-size: 0.875rem;
        color: #231F20;
    }

    /* Platform Button Architecture */
    .btn-brand-primary {
        background-color: #F15929;
        border-color: #F15929;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 40px;
        padding: 0 1.5rem;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-cancel {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 4px;
        height: 40px;
        padding: 0 1.5rem;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .btn-cancel:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }

    .btn-list-back {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.815rem;
        font-weight: 600;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-list-back:hover {
        background-color: #f8f9fa;
        color: #231F20;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        font-weight: 500;
        color: #F15929;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            
            {{-- Section Layout Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Edit Employee: {{ $employee->name }}</h1>
                </div>
                <div>
                    <a href="{{ route('hr.employees.index') }}" class="btn-list-back">Back to List</a>
                </div>
            </div>

            <div class="form-card p-4 col-md-11 mx-auto">
                {{-- Platform Error Alerts Pipelines --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Core Put Form Wrapper Target --}}
                <form action="{{ route('hr.employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold" style="color: #231F20;">Full Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $employee->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold" style="color: #231F20;">Email Address <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $employee->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-semibold" style="color: #231F20;">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('employee_id') is-invalid @enderror" 
                                   id="employee_id" 
                                   name="employee_id" 
                                   value="{{ old('employee_id', $employee->employee_id) }}"
                                   required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="department" class="form-label fw-semibold" style="color: #231F20;">Department <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('department') is-invalid @enderror" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department', $employee->department) }}"
                                   required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="join_date" class="form-label fw-semibold" style="color: #231F20;">Join Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('join_date') is-invalid @enderror" 
                                   id="join_date" 
                                   name="join_date" 
                                   value="{{ old('join_date', $employee->join_date ? $employee->join_date : '') }}"
                                   required>
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold" style="color: #231F20;">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $employee->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold" style="color: #231F20;">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="system-notice mb-4">
                        <div class="d-flex gap-2">
                            <i class="fas fa-info-circle text-muted mt-1"></i>
                            <div>
                                <strong>Note:</strong> To reset password, please use the password reset section on the employee details page.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2 border-top">
                        <button type="submit" class="btn btn-brand-primary">Update Employee</button>
                        <a href="{{ route('hr.employees.index') }}" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection