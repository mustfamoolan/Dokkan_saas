<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\NotificationResource;
use App\Models\Notification;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $notifications = Notification::latest()
            ->paginate($request->get('limit', 15));

        return $this->success(NotificationResource::collection($notifications)->response()->getData(true));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return $this->success(new NotificationResource($notification), 'تم تحديد التنبيه كمقروء.');
    }
}
