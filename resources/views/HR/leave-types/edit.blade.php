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

    .form-check-input {
        accent-color: #F15929;
    }
    
    .form-check-input:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Warning Block Component (No Gradients) */
    .system-warning-notice {
        background-color: #fff4e6;
        border-left: 3px solid #d9480f;
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
            
            {{-- Unified Structural Content Page Header Component Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Edit Leave Type: {{ $leaveType->name }}</h1>
                </div>
                <div>
                    <a href="{{ route('hr.leave-types.index') }}" class="btn-list-back">Back to List</a>
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
                
                {{-- Core Update Form Wrapper Target --}}
                <form action="{{ route('hr.leave-types.update', $leaveType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Leave Type Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Leave Type Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $leaveType->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $leaveType->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Days Per Year --}}
                    <div class="mb-3">
                        <label for="days_per_year" class="form-label">Days Per Year <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('days_per_year') is-invalid @enderror" 
                               id="days_per_year" 
                               name="days_per_year" 
                               value="{{ old('days_per_year', $leaveType->days_per_year) }}"
                               required
                               min="0"
                               max="365">
                        @error('days_per_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="system-warning-notice mt-2">
                            <div class="d-flex gap-2">
                                
                                <div>
                                     Changing this value will update existing leave balances for eligible employees.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Gender Restriction --}}
                    <div class="mb-3">
                        <label for="gender_restriction" class="form-label">Gender Restriction <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender_restriction') is-invalid @enderror" 
                                id="gender_restriction" 
                                name="gender_restriction" 
                                required>
                            <option value="both" {{ old('gender_restriction', $leaveType->gender_restriction) == 'both' ? 'selected' : '' }}>
                                 Both Genders
                            </option>
                            <option value="male" {{ old('gender_restriction', $leaveType->gender_restriction) == 'male' ? 'selected' : '' }}>
                                 Male Only
                            </option>
                            <option value="female" {{ old('gender_restriction', $leaveType->gender_restriction) == 'female' ? 'selected' : '' }}>
                                 Female Only
                            </option>
                        </select>
                        @error('gender_restriction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="system-warning-notice mt-2">
                            <div class="d-flex gap-2">
                                
                                <div>
                                     Changing gender restriction will add/remove this leave type from employees.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Active Status --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $leaveType->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active" style="color: #231F20; padding-top: 2px;">
                                Active
                            </label>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">If inactive, employees cannot apply for this leave type.</div>
                    </div>
                    
                    {{-- Warning Box --}}
                    <div class="system-warning-notice mb-4">
                        <div class="d-flex gap-2">
                            
                            <div>
                                 Changes to leave type may affect existing leave balances for all eligible employees.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2 border-top">
                        <button type="submit" class="btn btn-brand-primary">Update Leave Type</button>
                        <a href="{{ route('hr.leave-types.index') }}" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection