@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }}!</h4>
                    <p>Role: Administrator</p>
                    <hr>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">Manage Employees</div>
                                <div class="card-body">
                                    <h5 class="card-title">Employee Management</h5>
                                    <p class="card-text">Add, edit, and manage all employees</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Leave Types</div>
                                <div class="card-body">
                                    <h5 class="card-title">Configure Leave Types</h5>
                                    <p class="card-text">Define and manage leave types</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">Reports</div>
                                <div class="card-body">
                                    <h5 class="card-title">View Reports</h5>
                                    <p class="card-text">Generate leave reports and analytics</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection