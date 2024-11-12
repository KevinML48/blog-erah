<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Post;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();

        $notifications->each(function ($notification) {

            // Post Published Notifications
            if ($notification->type === 'App\Notifications\PostPublishedNotification') {
                // Get the post related to the notification
                $post = Post::find($notification->data['post_id']);
                if (!$post) {
                    $notification->delete();
                } else {
                    // Attach the post to the notification object
                    $notification->post = $post;
                }

            } // Comment Reply Notifications
            elseif ($notification->type === 'App\Notifications\CommentReplyNotification') {
                $comment = Comment::find($notification->data['comment_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                } else {
                    $notification->comment = $comment;
                }
            } // Comment Like Notifications
            elseif ($notification->type === 'App\Notifications\CommentLikeNotification') {
                $likeIds = $notification->data['ids'] ?? [];
                $likes = Like::whereIn('id', $likeIds)->get();

                if ($likes->isEmpty()) {
                    $notification->delete();
                } else {
                    $notification->likes = $likes;
                    $notification->like_count = $notification->data['count'] ?? count($likeIds);
                }
            } // Follow Notifications
            elseif ($notification->type === 'App\Notifications\FollowNotification') {
                $followIds = $notification->data['ids'] ?? [];
                $follows = Follow::whereIn('id', $followIds)->get();

                if ($follows->isEmpty()) {
                    $notification->delete();
                } else {
                    $notification->follows = $follows;
                    $notification->follow_count = $notification->data['count'] ?? count($followIds);
                }
            }
        });

        return view('notifications.index', compact('notifications'));
    }
}
