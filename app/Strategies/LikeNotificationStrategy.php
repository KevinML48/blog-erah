<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Models\CommentContent;
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

    public function processNotification($notification, $authUser = null)
    {
        $content = CommentContent::find($notification->data['context_id']);

        if (!$content) {
            $notification->delete();
            return null;
        }

        $content->is_liked_by_auth_user = $authUser->isLiking($content);

        $likeIds = $notification->data['ids'] ?? [];
        $likes = Like::whereIn('id', $likeIds)->take(3)->get();
        $users = $likes->pluck('user');

        if ($likes->isEmpty()) {
            $notification->delete();
            return null;
        } else {
            $notification->view = 'notifications.partials.new_bundle';
            $notification->args = [
                'type' => 'like',
                'users' => $users,
                'count' => count($likeIds),
                'likeable' => $content,
            ];
        }
    }
}
