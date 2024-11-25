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

    public function processNotification($notification)
    {
        $followIds = $notification->data['ids'] ?? [];
        $follows = Follow::whereIn('id', $followIds)->take(3)->get(); // Retrieve up to 3 follow records

        if ($follows->isEmpty()) {
            $notification->delete(); // If no follows, delete the notification
            return null;
        } else {
            $notification->view = 'components.notification-bundle';
            $notification->args = [
                'type' => 'follow',
                'list' => $follows,
                'count' => count($followIds),
            ];
        }
    }
}
