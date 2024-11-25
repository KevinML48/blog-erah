<?php

namespace App\Strategies;

use App\Contracts\NotificationStrategy;
use App\Models\Comment;
use App\Notifications\CommentLikeNotification;
use App\Notifications\CommentReplyNotification;
use Illuminate\Notifications\Notification;

class CommentNotificationStrategy implements NotificationStrategy
{
    protected $comment;

    public function __construct(Comment $comment=null)
    {
        $this->comment = $comment ?? new Comment();
    }

    public function handleCreation(): void
    {
        // Logic for handling comment creation (e.g., notify parent comment user)
        if ($this->comment->parent_id) {
            $parentComment = Comment::find($this->comment->parent_id);
            if ($parentComment) {
                $parentUser = $parentComment->content->user;
                if ($parentUser->wantsNotification('comment-reply', $parentComment->content->id, 'single')) {
                    $parentUser->notify(new CommentReplyNotification(['comment_id' => $this->comment->id]));
                }
            }
        } else {
            // Handle top-level comment notification
        }
    }

    public function handleDeletion(): void
    {
        // Delete CommentReplyNotification where the comment_id matches the deleted comment's ID
        $this->deleteNotifications(CommentReplyNotification::class, 'data->comment_id', $this->comment->id);

        // Delete CommentLikeNotification where the context_id matches the deleted comment's ID
        $this->deleteNotifications(CommentLikeNotification::class, 'data->context_id', $this->comment->id);
    }

    /**
     * A helper method to delete notifications based on the type and context.
     *
     * @param string $notificationClass The notification class to search for.
     * @param string $field The field in the notification data to match.
     * @param mixed $value The value to match for deletion.
     */
    protected function deleteNotifications(string $notificationClass, string $field, $value): void
    {
        // Retrieve notifications by class type and matching field
        $notifications = Notification::where('type', $notificationClass)
            ->whereRaw("JSON_EXTRACT(data, '$.$field') = ?", [$value])
            ->get();

        // Delete the notifications if found
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function processNotification($notification)
    {
        $comment = Comment::find($notification->data['comment_id']);

        if (!$comment || !$comment->contentExists()) {
            $notification->delete();
            return null;
        } else {
            $notification->view = 'notifications.partials.new_reply';
            $notification->args = [
                'comment' => $comment,
            ];

        }
    }
}
