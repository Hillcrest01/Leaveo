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
        display: block;
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

    .form-check-input {
        accent-color: #F15929;
    }
    
    .form-check-input:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
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
        border: 1px solid transparent;
        transition: background-color 0.15s ease;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
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
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Add Public Holiday</h1>
                </div>
                <div>
                    <a href="{{ route('hr.public-holidays.index') }}" class="btn-list-back">Back</a>
                </div>
            </div>

            <div class="form-card p-4 col-md-11 mx-auto">
                {{-- Core Store Form Wrapper Target --}}
                <form action="{{ route('hr.public-holidays.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Holiday Date <span class="text-danger">*</span></label>
                        <input type="date" name="holiday_date" class="form-control @error('holiday_date') is-invalid @enderror" required>
                        @error('holiday_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_recurring" value="1" class="form-check-input" id="is_recurring">
                            <label class="form-check-label small fw-semibold" for="is_recurring" style="color: #231F20; padding-top: 2px;">
                                Recurring (Occurs every year on this date)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label small fw-semibold" for="is_active" style="color: #231F20; padding-top: 2px;">Active</label>
                        </div>
                    </div>
                    
                    <div class="pt-3 border-top">
                        <button type="submit" class="btn btn-brand-primary">Save Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection