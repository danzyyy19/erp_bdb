<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $notifications = auth()->user()
            ->userNotifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return redirect()->back();
    }

    public function markAllRead()
    {
        auth()->user()
            ->userNotifications()
            ->unread()
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
