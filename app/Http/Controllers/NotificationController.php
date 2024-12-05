<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\CommentServiceInterface;
use App\Services\NotificationServiceInterface;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    protected NotificationServiceInterface $notificationService;
    protected CommentServiceInterface $commentService;

    public function __construct(NotificationServiceInterface $notificationService, CommentServiceInterface $commentService)
    {
        $this->notificationService = $notificationService;
        $this->commentService = $commentService;
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


        $comments = collect(); // Initialize an empty collection

        foreach ($notifications as $notification) {
            $args = $notification->args;
            if (isset($args['comment'])) {
                $comments->push($args['comment']);
            }

            if (isset($args['likeable']) && $args['likeable'] instanceof Comment) {
                $comments->push($args['likeable']);
            }
        }

        // Remove duplicates from the collection
        $comments = $comments->unique();

        $this->commentService->addAuthUserTags([$comments], auth()->user());



//        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
