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

    /* Solid Informational Notice Box (No Gradients) */
    .system-notice {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 1rem;
        font-size: 0.875rem;
        color: #231F20;
    }

    /* Platform Button Architecture Modules */
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
        border: 1px solid transparent;
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

    .btn-danger-action {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #c92a2a;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 40px;
        padding: 0 1.5rem;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .btn-danger-action:hover {
        background-color: #fff5f5;
        border-color: #ffc9c9;
        color: #c92a2a;
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
            
            {{-- Unified Structural Section Layout Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Edit Public Holiday</h1>
                </div>
                <div>
                    <a href="{{ route('hr.public-holidays.index') }}" class="btn-list-back">Back to List</a>
                </div>
            </div>

            <div class="form-card p-4 col-md-11 mx-auto">
                {{-- Platform Error Pipelines Render Area --}}
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

                {{-- Core Action Processing Layout Box --}}
                <form action="{{ route('hr.public-holidays.update', $publicHoliday) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $publicHoliday->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">e.g., Christmas Day, New Year's Day, Jamhuri Day</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="holiday_date" class="form-label">Holiday Date <span class="text-danger">*</span></label>
                        <input type="date" 
                               name="holiday_date" 
                               id="holiday_date" 
                               class="form-control @error('holiday_date') is-invalid @enderror" 
                               value="{{ old('holiday_date', $publicHoliday->holiday_date ? $publicHoliday->holiday_date->format('Y-m-d') : '') }}"
                               required>
                        @error('holiday_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Select the date when this holiday occurs.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Brief description of the holiday...">{{ old('description', $publicHoliday->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Add any additional information about this holiday.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_recurring" 
                                   id="is_recurring" 
                                   class="form-check-input" 
                                   value="1"
                                   {{ old('is_recurring', $publicHoliday->is_recurring) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_recurring" style="color: #231F20; padding-top: 2px;">
                                Recurring Holiday
                            </label>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Check if this holiday occurs every year on the same date (e.g., Christmas, New Year).</div>
                    </div>
                    
                    <div class="mb-3" id="year_field" style="{{ old('is_recurring', $publicHoliday->is_recurring) ? 'display: none;' : 'display: block;' }}">
                        <label for="year" class="form-label">Specific Year</label>
                        <select name="year" id="year" class="form-select @error('year') is-invalid @enderror">
                            <option value="">Select Year</option>
                            @for($i = 2020; $i <= date('Y') + 5; $i++)
                                <option value="{{ $i }}" {{ old('year', $publicHoliday->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">For non-recurring holidays, specify which year this holiday applies to.</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   class="form-check-input" 
                                   value="1"
                                   {{ old('is_active', $publicHoliday->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active" style="color: #231F20; padding-top: 2px;">
                                Active
                            </label>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Inactive holidays will not be excluded from leave calculations.</div>
                    </div>
                    
                    {{-- Platform Explanatory Details Box Component --}}
                    <div class="system-notice mb-4">
                        <div class="d-flex gap-2">
                            <i class="fas fa-info-circle text-muted mt-1"></i>
                            <div>
                                <strong>Note:</strong> 
                                <ul class="mb-0 mt-1 ps-3" style="font-size: 0.85rem; line-height: 1.4;">
                                    <li>Recurring holidays repeat every year on the same date</li>
                                    <li>Non-recurring holidays only apply to the specified year</li>
                                    <li>Inactive holidays are ignored in leave calculations</li>
                                    <li>Holidays are automatically excluded from leave day calculations when employees apply for leave</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-brand-primary">Update Holiday</button>
                            <a href="{{ route('hr.public-holidays.index') }}" class="btn btn-cancel">Cancel</a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-danger-action" onclick="confirmDelete()">Delete Holiday</button>
                        </div>
                    </div>
                </form>
                
                <form action="{{ route('hr.public-holidays.destroy', $publicHoliday) }}" 
                      method="POST" 
                      id="delete-form" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle year field based on recurring checkbox
    document.getElementById('is_recurring').addEventListener('change', function() {
        const yearField = document.getElementById('year_field');
        if (this.checked) {
            yearField.style.display = 'none';
            document.getElementById('year').value = '';
        } else {
            yearField.style.display = 'block';
        }
    });
    
    // Confirm delete
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this holiday? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection