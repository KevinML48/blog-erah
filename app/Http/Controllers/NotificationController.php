<?php

namespace App\Http\Controllers;

use App\Services\NotificationServiceInterface;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    protected NotificationServiceInterface $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);

        $this->notificationService->processNotifications($notifications, Auth::user());
        if (request()->ajax()) {
            return response()->json([
                'notifications' => view('notifications.partials.notification-loop', compact('notifications'))->render(),
                'next_page_url' => $notifications->nextPageUrl(),
            ]);
        }
//        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
