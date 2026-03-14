@extends('admin.layouts.admin-layout')

@section('title', 'Roles & Permissions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Roles List</h4>
                <button class="btn btn-sm btn-primary">Add Role</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Role Name</th>
                                <th>Guard</th>
                                <th>Permissions Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td><span class="badge bg-info">{{ $role->guard_name }}</span></td>
                                <td>{{ $role->permissions->count() }}</td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary">Edit</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
