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
            // Post Published
            if ($notification->type === 'App\Notifications\PostPublishedNotification') {
                $post = Post::find($notification->data['post_id']);
                if (!$post) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->post = $post;
                }
                // Comment Reply
            } elseif ($notification->type === 'App\Notifications\CommentReplyNotification') {
                $comment = Comment::find($notification->data['comment_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->comment = $comment;
                }
                // Comment Like
            } elseif ($notification->type === 'App\Notifications\CommentLikeNotification') {
                $comment = Comment::find($notification->data['context_id']);
                if (!$comment || !$comment->contentExists()) {
                    $notification->delete();
                    return true;
                }

                $likeIds = $notification->data['ids'] ?? [];
                $likes = Like::whereIn('id', $likeIds)
                    ->take(3)
                    ->get();

                if ($likes->isEmpty()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->likes = $likes;
                    $notification->like_count = count($likeIds);
                }
                // Follow
            } elseif ($notification->type === 'App\Notifications\FollowNotification') {
                $followIds = $notification->data['ids'] ?? [];
                $follows = Follow::whereIn('id', $followIds)
                    ->take(3)
                    ->get();

                if ($follows->isEmpty()) {
                    $notification->delete();
                    return true;
                } else {
                    $notification->follows = $follows;
                    $notification->follow_count = count($followIds);
                }
            }

            return false; // Keep in collection
        });


        return view('notifications.index', compact('notifications'));
    }
}
