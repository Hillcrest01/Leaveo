@extends('layouts.app')

@section('content')
<style>
    /* Premium Architecture Tokens Override */
    .page-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }
    
    .table-container {
        border: 1px solid #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    /* Professional Structural Status Badges */
    .badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-active { background-color: #ebfbee; color: #2b8a3e; }
    .status-inactive { background-color: #f1f3f5; color: #495057; border: 1px solid #dee2e6; }
    
    .badge-recurring {
        background-color: #e8f5e9;
        color: #2e7d32;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .badge-onetime {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Minimal Link Row Actions Group */
    .action-link-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .action-row-btn {
        font-size: 0.825rem;
        font-weight: 600;
        text-decoration: none;
        color: #6c757d;
        transition: color 0.15s ease;
        background: none;
        border: none;
        padding: 0;
    }

    .action-row-btn:hover {
        color: #F15929;
    }

    .action-row-btn.btn-delete:hover {
        color: #c92a2a;
    }

    /* Global Structural Buttons Framework */
    .btn-brand-primary {
        background-color: #F15929;
        border-color: #F15929;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 38px;
        padding: 0 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-secondary-action {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 4px;
        height: 38px;
        padding: 0 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-secondary-action:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }

    .form-select, .form-control {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-select:focus, .form-control:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
    .system-notice {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 1rem;
        font-size: 0.875rem;
        color: #231F20;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Unified Structural Content Page Header Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Public Holidays Management</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('hr.public-holidays.create') }}" class="btn btn-brand-primary">
                        <i class="fas fa-plus me-2" style="font-size: 0.8rem;"></i> Add New Holiday
                    </a>
                    <button type="button" class="btn btn-secondary-action" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                        Bulk Import
                    </button>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Platform Response Framework Message Pipes --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Filter Module Component --}}
                <form method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="year" class="form-select">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-brand-primary">Filter</button>
                            <a href="{{ route('hr.public-holidays.index') }}" class="btn btn-secondary-action">Reset</a>
                        </div>
                    </div>
                </form>
                
                {{-- Central Grid Framework Frame --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Year</th>
                                <th>Recurring</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="font-weight: 600; color: #231F20;">{{ $holiday->name }}</td>
                                    <td class="text-muted">{{ Carbon\Carbon::parse($holiday->holiday_date)->format('F d, Y') }}</td>
                                    <td>
                                        @if($holiday->is_recurring)
                                            <span class="badge-onetime">Every Year</span>
                                        @else
                                            {{ $holiday->year }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($holiday->is_recurring)
                                            <span class="badge-recurring">Recurring</span>
                                        @else
                                            <span class="badge-onetime">One-time</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($holiday->is_active)
                                            <span class="badge-status status-active">Active</span>
                                        @else
                                            <span class="badge-status status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-link-group justify-content-end pe-2">
                                            <a href="{{ route('hr.public-holidays.edit', $holiday) }}" class="action-row-btn">Edit</a>
                                            
                                            <button onclick="toggleStatus({{ $holiday->id }})" class="action-row-btn">
                                                {{ $holiday->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                            
                                            <form action="{{ route('hr.public-holidays.destroy', $holiday) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-row-btn btn-delete" onclick="return confirm('Delete this holiday?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted small">No public holidays found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Import System Component Overlay Modal --}}
<div class="modal fade" id="bulkImportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: #231F20;">Bulk Import Holidays</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hr.public-holidays.bulk-import') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="system-notice mb-3">
                        <strong>Format:</strong> Use the format below. One holiday per line.<br>
                        <code>Name|YYYY-MM-DD</code><br>
                        Example: <code>Christmas Day|2024-12-25</code>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600;">Year</label>
                        <select name="year" class="form-select" required>
                            @for($i = 2020; $i <= date('Y')+5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600;">Holidays List</label>
                        <textarea name="holidays_list" class="form-control" rows="8" required placeholder="New Year|2024-01-01&#10;Good Friday|2024-04-18&#10;Easter Monday|2024-04-21"></textarea>
                        <small class="text-muted mt-1 d-block">Format: Holiday Name|YYYY-MM-DD (one per line)</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary-action" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Import Holidays</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleStatus(id) {
        if (confirm('Change holiday status?')) {
            document.getElementById(`toggle-form-${id}`).submit();
        }
    }
</script>

@foreach($holidays as $holiday)
    <form action="{{ route('hr.public-holidays.toggle-status', $holiday) }}" method="POST" id="toggle-form-{{ $holiday->id }}" style="display: none;">
        @csrf
    </form>
@endforeach
@endsection