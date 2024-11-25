<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Models\Comment;
use App\Models\Like;

class LikeNotificationStrategy implements NotificationStrategy
{
    protected BundledNotification $entity;
    protected BundledNotificationStrategy $strategy;

    public function __construct(BundledNotification $entity =null)
    {
        $this->entity = $entity ?? new Like();
        $this->strategy = new BundledNotificationStrategy($entity);
    }

    public function handleCreation(): void
    {
        $this->strategy->handleCreation();
    }

    public function handleDeletion(): void
    {
        $this->strategy->handleDeletion();
    }

    public function processNotification($notification)
    {
        $comment = Comment::find($notification->data['context_id']);

        if (!$comment || !$comment->contentExists()) {
            $notification->delete();
            return null;
        }

        $likeIds = $notification->data['ids'] ?? [];
        $likes = Like::whereIn('id', $likeIds)->take(3)->get();

        if ($likes->isEmpty()) {
            $notification->delete();
            return null;
        } else {
            $notification->view = 'components.notification-bundle';
            $notification->args = [
                'type' => 'like',
                'list' => $likes,
                'count' => count($likeIds),
            ];
        }
    }
}
