@extends('admin.layouts.admin-layout')

@section('title', 'مزايا وحدود الباقة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">إدارة مزايا وحدود الباقة: {{ $plan->name }}</h4>
                <a href="{{ route('admin.plans') }}" class="btn btn-light btn-sm">العودة للباقات</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.plans.features.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Numerical Limits -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-none">
                                <div class="card-header bg-light-subtle px-0">
                                    <h5 class="mb-0">الحدود الرقمية (Limits)</h5>
                                </div>
                                <div class="card-body px-0">
                                    <div class="row">
                                        @foreach($limits as $key => $label)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ $label }}</label>
                                            <input type="number" name="limits[{{ $key }}]" class="form-control" value="{{ $currentFeatures[$key] ?? 0 }}" required min="0">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Logical Features -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-none">
                                <div class="card-header bg-light-subtle px-0">
                                    <h5 class="mb-0">المزايا والخصائص (Features)</h5>
                                </div>
                                <div class="card-body px-0">
                                    <div class="row">
                                        @foreach($features as $key => $label)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch p-3 border rounded">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="features[{{ $key }}]" id="{{ $key }}" {{ (isset($currentFeatures[$key]) && $currentFeatures[$key] == '1') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end border-top pt-3">
                        <button type="submit" class="btn btn-primary px-5">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
