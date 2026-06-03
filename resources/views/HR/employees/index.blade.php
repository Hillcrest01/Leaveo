@extends('layouts.app')

@section('content')
<style>
    /* Content System Design overrides */
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

    /* Clean, professional input layout alignments */
    .form-control, .form-select {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
        border-color: #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: #F7AB93;
        box-shadow: 0 0 0 3px rgba(241, 89, 41, 0.15);
    }

    /* Professional Status Badges */
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-active {
        background-color: #ebfbee;
        color: #2b8a3e;
    }
    
    .status-inactive {
        background-color: #f1f3f5;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .dept-badge {
        background-color: #f8f9fa;
        color: #231F20;
        border: 1px solid #e9ecef;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Action Links instead of bulky multi-colored buttons */
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
    }

    .btn-brand-primary:hover {
        background-color: #d4481d;
        border-color: #d4481d;
        color: #ffffff;
    }

    .btn-reset {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 4px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.15s ease;
    }

    .btn-reset:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Upper Structural Section Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Employee Management</h1>
                </div>
                <div>
                    <a href="{{ route('hr.employees.create') }}" class="btn btn-brand-primary">
                        <i class="fas fa-plus me-2" style="font-size: 0.8rem;"></i> Add New Employee
                    </a>
                </div>
            </div>

            <div class="page-card p-4">
                {{-- Global Alerts Notification Pipeline --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Registry Filters Form Component --}}
                <form method="GET" action="{{ route('hr.employees.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search by name, email, employee ID, or department..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-brand-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('hr.employees.index') }}" class="btn btn-reset w-100">Reset</a>
                        </div>
                    </div>
                </form>
                
                {{-- Core Employee Data Table Frame --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td>
                                        <span style="font-weight: 600; color: #231F20;">{{ $employee->employee_id }}</span>
                                    </td>
                                    <td style="font-weight: 500; color: #231F20;">{{ $employee->name }}</td>
                                    <td class="text-muted">{{ $employee->email }}</td>
                                    <td>
                                        <span class="dept-badge">{{ $employee->department ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-muted">{{ $employee->join_date ? $employee->join_date : 'N/A' }}</td>
                                    <td>
                                        @if($employee->is_active ?? true)
                                            <span class="status-badge status-active">Active</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-link-group justify-content-end">
                                            <a href="{{ route('hr.employees.show', $employee) }}" class="action-row-btn" title="View Details">View</a>
                                            <a href="{{ route('hr.employees.edit', $employee) }}" class="action-row-btn" title="Edit Profile">Edit</a>
                                            <button type="button" 
                                                    class="action-row-btn btn-delete" 
                                                    onclick="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')"
                                                    title="Remove Entry">
                                                Delete
                                            </button>
                                        </div>
                                        
                                        <form action="{{ route('hr.employees.destroy', $employee) }}" 
                                              method="POST" 
                                              id="delete-form-{{ $employee->id }}"
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted small">
                                        No employees found matching the criteria. Add an entry to update the registry framework.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Platform Compliant Pagination Segment --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $employees->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
</script>
@endsection