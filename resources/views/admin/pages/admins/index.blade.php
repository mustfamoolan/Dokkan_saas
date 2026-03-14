@extends('admin.layouts.admin-layout')

@section('title', 'Admins Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Admins List</h4>
                <button class="btn btn-sm btn-primary">Add Admin</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    <span class="badge {{ $admin->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($admin->status) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($admin->getRoleNames() as $role)
                                        <span class="badge bg-secondary">{{ $role }}</span>
                                    @endforeach
                                </td>
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
