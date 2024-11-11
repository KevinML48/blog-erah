<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();

        $notifications->each(function ($notification) {
            // Check the type of the notification
            if ($notification->type === 'App\Notifications\PostPublishedNotification') {
                // Get the post related to the notification
                $post = Post::find($notification->data['post_id']);
                // Attach the post to the notification object
                $notification->post = $post;
            } elseif ($notification->type === 'App\Notifications\CommentLikeNotification' || $notification->type === 'App\Notifications\CommentReplyNotification') {
                $comment = Comment::find($notification->data['comment_id']);
                $notification->comment = $comment;
            }
        });

        return view('notifications.index', compact('notifications'));
    }
}
