@extends('layouts.app')

@section('content')
<style>
    /* Premium Production Design Overrides */
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

    .form-control, .form-select {
        height: 40px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    textarea.form-control {
        height: auto;
    }

    .form-control:focus, .form-select:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Informational Box Design */
    .system-notice {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 1rem;
        font-size: 0.875rem;
        color: #231F20;
    }

    /* Clean Form Actions Buttons */
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
            
            {{-- Header Layout Component --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Add New Employee</h1>
                </div>
                <div>
                    <a href="{{ route('hr.employees.index') }}" class="btn-list-back">Back to List</a>
                </div>
            </div>

            <div class="form-card p-4 col-md-11 mx-auto">
                {{-- Platform Error Pipelines --}}
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
                
                {{-- Main Data Post Target Frame --}}
                <form action="{{ route('hr.employees.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender" 
                                    required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('employee_id') is-invalid @enderror" 
                                   id="employee_id" 
                                   name="employee_id" 
                                   value="{{ old('employee_id') }}"
                                   placeholder="e.g., EMP001"
                                   required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">Must be unique across all corporate records.</div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('department') is-invalid @enderror" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department') }}"
                                   placeholder="e.g., Engineering, HR, Finance"
                                   required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="join_date" class="form-label">Join Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('join_date') is-invalid @enderror" 
                                   id="join_date" 
                                   name="join_date" 
                                   value="{{ old('join_date') }}"
                                   required>
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="e.g., +1 (555) 000-0000">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3"
                                  placeholder="Enter residential or corporate physical mailing address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Initial Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">Minimum 8 characters requirement.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Initial Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="system-notice mb-4">
                        <div class="d-flex gap-2">
                            <i class="fas fa-info-circle text-muted mt-1"></i>
                            <div>
                                <span style="font-weight: 600;">System Notice:</span> Leave balances for all active organizational allocation tracks will be automatically configured for this system account record based on the parameters set within the active calendar period.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2 border-top">
                        <button type="submit" class="btn btn-brand-primary">Create Employee</button>
                        <a href="{{ route('hr.employees.index') }}" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection