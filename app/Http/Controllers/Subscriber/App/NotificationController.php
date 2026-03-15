<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;
        
        // Trigger checks on index load for demo/simplicity (in production this might be a job)
        $this->notificationService->checkStockAlerts($storeId);
        $this->notificationService->checkSubscriptionAlerts($storeId);

        $notifications = Notification::where('store_id', $storeId)
            ->latest()
            ->paginate(20);

        return view('subscriber.app.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return redirect()->back()->with('success', 'تم تحديد التنبيه كمقروء.');
    }

    public function markAllRead()
    {
        Notification::where('store_id', Auth::guard('subscriber')->user()->store->id)
            ->unread()
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'تم تحديد جميع التنبيهات كمقروءة.');
    }
}
