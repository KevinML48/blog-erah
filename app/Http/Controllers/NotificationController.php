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

        $notifications = $notifications->reject(function ($notification) {
            // Post Published Notifications
            if ($notification->type === 'App\Notifications\PostPublishedNotification') {
                $post = Post::find($notification->data['post_id']);
                if (!$post) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->post = $post;
                }

            } elseif ($notification->type === 'App\Notifications\CommentReplyNotification') {
                $comment = Comment::find($notification->data['comment_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->comment = $comment;
                }

            } elseif ($notification->type === 'App\Notifications\CommentLikeNotification') {
                $likeIds = $notification->data['ids'] ?? [];
                $likes = Like::whereIn('id', $likeIds)->get();

                if ($likes->isEmpty()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->likes = $likes;
                    $notification->like_count = $notification->data['count'] ?? count($likeIds);
                }

            } elseif ($notification->type === 'App\Notifications\FollowNotification') {
                $followIds = $notification->data['ids'] ?? [];
                $follows = Follow::whereIn('id', $followIds)->get();

                if ($follows->isEmpty()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->follows = $follows;
                    $notification->follow_count = $notification->data['count'] ?? count($followIds);
                }
            }

            return false; // Keep in collection
        });


        return view('notifications.index', compact('notifications'));
    }
}
