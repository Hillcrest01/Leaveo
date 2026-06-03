@extends('layouts.app')

@section('content')
<style>
    /* Premium Architecture Grid Tokens */
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
    
    .badge-restriction {
        background-color: #f8f9fa;
        color: #231F20;
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
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Unified Structural Content Page Header Component Layout --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Leave Types Management</h1>
                </div>
                <div>
                    <a href="{{ route('hr.leave-types.create') }}" class="btn btn-brand-primary">
                        <i class="fas fa-plus me-2" style="font-size: 0.8rem;"></i> Add New Leave Type
                    </a>
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
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Central Parameters Inventory Grid Frame --}}
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Days/Year</th>
                                <th>Gender Restriction</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveTypes as $type)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td><span style="font-weight: 600; color: #6c757d;">#{{ $type->id }}</span></td>
                                    <td>
                                        <span style="font-weight: 600; color: #231F20;">{{ $type->name }}</span>
                                        @if(!$type->is_active)
                                            <span class="badge-status status-inactive ms-1" style="padding: 0.15rem 0.35rem; font-size: 0.65rem;">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="max-width: 250px;">{{ Str::limit($type->description, 50) ?? '—' }}</td>
                                    <td style="font-weight: 500; color: #231F20;">{{ $type->days_per_year }} days</td>
                                    <td>
                                        @if($type->gender_restriction == 'male')
                                            <span class="badge-restriction">Male Only</span>
                                        @elseif($type->gender_restriction == 'female')
                                            <span class="badge-restriction">Female Only</span>
                                        @else
                                            <span class="badge-restriction">Both Genders</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->is_active)
                                            <span class="badge-status status-active">Active</span>
                                        @else
                                            <span class="badge-status status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-link-group justify-content-end pe-2">
                                            <a href="{{ route('hr.leave-types.edit', $type) }}" class="action-row-btn" title="Edit">Edit</a>
                                            
                                            <button type="button" 
                                                    class="action-row-btn" 
                                                    onclick="toggleStatus({{ $type->id }})"
                                                    title="{{ $type->is_active ? 'Deactivate' : 'Activate' }}">
                                                {{ $type->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                            
                                            <form action="{{ route('hr.leave-types.destroy', $type) }}" 
                                                  method="POST" 
                                                  id="delete-form-{{ $type->id }}"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="action-row-btn btn-delete" 
                                                        onclick="confirmDelete({{ $type->id }}, '{{ $type->name }}')"
                                                        title="Delete">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted small">
                                        No leave types found. Click "Add New Leave Type" to create one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    function toggleStatus(id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/hr/leave-types/${id}/toggle-status`;
        form.style.display = 'none';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function confirmDelete(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
</script>
@endsection