<?php

namespace App\Strategies;

use App\Contracts\NotificationStrategy;
use App\Models\Comment;
use App\Notifications\CommentReplyNotification;

class CommentNotificationStrategy implements NotificationStrategy
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function handleCreation(): void
    {
        // Logic for handling comment creation (e.g., notify parent comment user)
        if ($this->comment->parent_id) {
            $parentComment = Comment::find($this->comment->parent_id);
            if ($parentComment) {
                $parentComment->content->user->notify(new CommentReplyNotification($this->comment));
            }
        } else {
            // Handle top-level comment notification
        }
    }
}
