<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Models\Follow;

class FollowNotificationStrategy implements NotificationStrategy
{
    protected BundledNotification $entity;
    protected BundledNotificationStrategy $strategy;

    public function __construct(BundledNotification $entity = null)
    {
        $this->entity = $entity ?? new Follow();
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
        $followIds = $notification->data['ids'] ?? [];
        $follows = Follow::whereIn('id', $followIds)->take(3)->get(); // Retrieve up to 3 follow records
        $users = $follows->pluck('follower');

        if ($follows->isEmpty()) {
            $notification->delete(); // If no follows, delete the notification
            return null;
        } else {
            $notification->view = 'notifications.partials.new_bundle';
            $notification->args = [
                'type' => 'follow',
                'users' => $users,
                'count' => count($followIds),
            ];
        }
    }
}
