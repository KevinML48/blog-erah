<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {

        $notifications = auth()->user()->notifications()->latest()->paginate(20);

        if (request()->ajax()) {
            $this->processNotifications($notifications);
            Log::info($notifications->nextPageUrl());
            return response()->json([
                'notifications' => view('notifications.partials.notification-loop', compact('notifications'))->render(),
                'next_page_url' => $notifications->nextPageUrl(),
            ]);
        }
        $this->processNotifications($notifications);
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    private function processNotifications($notifications)
    {
        // Process each notification type (your original logic)
        $notifications->getCollection()->transform(function ($notification) {
            $notification->unread = $notification->read_at ? '' : true;
            // Post Published
            if ($notification->type === 'App\Notifications\PostPublishedNotification') {
                $post = Post::find($notification->data['post_id']);
                if (!$post) {
                    $notification->delete();
                    return null; // If no post, skip this notification
                } else {
                    $notification->post = $post;
                }
            } // Comment Reply
            elseif ($notification->type === 'App\Notifications\CommentReplyNotification') {
                $comment = Comment::find($notification->data['comment_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                    return null; // If no comment or content does not exist, skip this notification
                } else {
                    $notification->comment = $comment;
                }
            } // Comment Like
            elseif ($notification->type === 'App\Notifications\CommentLikeNotification') {
                $comment = Comment::find($notification->data['context_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                    return null; // If no comment or content does not exist, skip this notification
                }

                $likeIds = $notification->data['ids'] ?? [];
                $likes = Like::whereIn('id', $likeIds)->take(3)->get();

                if ($likes->isEmpty()) {
                    $notification->delete();
                    return null; // If no likes, skip this notification
                } else {
                    $notification->likes = $likes;
                    $notification->like_count = count($likeIds);
                }
            } // Follow
            elseif ($notification->type === 'App\Notifications\FollowNotification') {
                $followIds = $notification->data['ids'] ?? [];
                $follows = Follow::whereIn('id', $followIds)->take(3)->get();

                if ($follows->isEmpty()) {
                    $notification->delete();
                    return null; // If no follows, skip this notification
                } else {
                    $notification->follows = $follows;
                    $notification->follow_count = count($followIds);
                }
            }

            return $notification; // Return the notification with added data
        });

        // Filter out any notifications that were deleted
        $notifications->getCollection()->filter(function ($notification) {
            return $notification !== null;
        });
    }

}
