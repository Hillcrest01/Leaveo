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

    /* Informational Left Border Notice Box */
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
            
            {{-- Unified Structural Content Page Header Component Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Create New Leave Type</h1>
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
                
                {{-- Core Store Form Wrapper Target --}}
                <form action="{{ route('hr.leave-types.store') }}" method="POST">
                    @csrf
                    
                    {{-- Leave Type Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Leave Type Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               placeholder="e.g., Maternity Leave, Paternity Leave, Annual Leave">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Enter a unique name for this leave type.</div>
                    </div>
                    
                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Describe this leave type...">{{ old('description') }}</textarea>
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
                               value="{{ old('days_per_year', 0) }}"
                               required
                               min="0"
                               max="365"
                               step="1">
                        @error('days_per_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">How many days per year are allocated for this leave type?</div>
                    </div>
                    
                    {{-- Gender Restriction --}}
                    <div class="mb-3">
                        <label for="gender_restriction" class="form-label">Gender Restriction <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender_restriction') is-invalid @enderror" 
                                id="gender_restriction" 
                                name="gender_restriction" 
                                required>
                            <option value="both" {{ old('gender_restriction', 'both') == 'both' ? 'selected' : '' }}>
                                Both Genders
                            </option>
                            <option value="male" {{ old('gender_restriction') == 'male' ? 'selected' : '' }}>
                                Male Only
                            </option>
                            <option value="female" {{ old('gender_restriction') == 'female' ? 'selected' : '' }}>
                                 Female Only
                            </option>
                        </select>
                        @error('gender_restriction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-2" style="font-size: 0.75rem; line-height: 1.4;">
                            <strong>Note:</strong> <br>
                            - "Both Genders" applies to all employees<br>
                            - "Male Only" only applies to male employees (e.g., Paternity Leave)<br>
                            - "Female Only" only applies to female employees (e.g., Maternity Leave)
                        </div>
                    </div>
                    
                    {{-- Active Status --}}
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input @error('is_active') is-invalid @enderror" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active" style="color: #231F20; padding-top: 2px;">
                                Active
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">If inactive, employees cannot apply for this leave type.</div>
                    </div>
                    
                    {{-- Warning Box Component --}}
                    <div class="system-notice mb-4">
                        <div class="d-flex gap-2">
                            <i class="fas fa-info-circle text-muted mt-1"></i>
                            <div>
                                <strong>Note:</strong> When you create this leave type, it will automatically be applied to all eligible employees based on the gender restriction selected.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2 border-top">
                        <button type="submit" class="btn btn-brand-primary">Create Leave Type</button>
                        <a href="{{ route('hr.leave-types.index') }}" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection