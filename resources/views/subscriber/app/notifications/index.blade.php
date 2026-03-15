@extends('subscriber.layouts.app')

@section('title', 'مركز التنبيهات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">التنبيهات والإشعارات</h5>
                @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('subscriber.app.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-soft-primary">تحديد الكل كمقروء</button>
                </form>
                @endif
            </div>
            <div class="card-body p-0">
                @if($notifications->isEmpty())
                    <div class="text-center py-5">
                        <iconify-icon icon="solar:bell-bing-bold" class="fs-1 text-muted mb-3"></iconify-icon>
                        <p class="text-muted">لا يوجد تنبيهات حالياً.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action p-3 {{ $notification->is_read ? 'opacity-75' : 'bg-light-subtle' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @php
                                            $icon = 'solar:info-circle-bold';
                                            $color = 'text-info';
                                            if($notification->severity === 'warning') { $icon = 'solar:danger-triangle-bold'; $color = 'text-warning'; }
                                            if($notification->severity === 'danger') { $icon = 'solar:danger-circle-bold'; $color = 'text-danger'; }
                                            if($notification->severity === 'success') { $icon = 'solar:check-circle-bold'; $color = 'text-success'; }
                                        @endphp
                                        <iconify-icon icon="{{ $icon }}" class="fs-24 {{ $color }}"></iconify-icon>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 {{ $notification->is_read ? '' : 'fw-bold' }}">{{ $notification->title }}</h6>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="text-muted small mb-2">{{ $notification->message }}</p>
                                        
                                        <div class="d-flex gap-2">
                                            @if($notification->action_url)
                                                <a href="{{ route('subscriber.app.notifications.mark-read', $notification->id) }}" class="btn btn-sm btn-soft-secondary py-1">
                                                    عرض التفاصيل
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->is_read)
                                                <form action="{{ route('subscriber.app.notifications.mark-read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">تحديد كمقروء</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="p-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
