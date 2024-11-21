<?php

namespace App\Http\Controllers;

use App\Services\NotificationServiceInterface;

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

        if (request()->ajax()) {
            $this->notificationService->processNotifications($notifications);
            return response()->json([
                'notifications' => view('notifications.partials.notification-loop', compact('notifications'))->render(),
                'next_page_url' => $notifications->nextPageUrl(),
            ]);
        }
        $this->notificationService->processNotifications($notifications);
//        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
