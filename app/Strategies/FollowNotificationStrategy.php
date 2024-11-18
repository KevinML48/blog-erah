<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;

class FollowNotificationStrategy implements NotificationStrategy
{
    protected BundledNotification $entity;
    protected BundledNotificationStrategy $strategy;

    public function __construct(BundledNotification $entity)
    {
        $this->entity = $entity;
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
}
