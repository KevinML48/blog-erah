<?php

namespace App\Services;

use App\Contracts\NotifiableEntityInterface;

class NotificationService implements NotificationServiceInterface
{

    /**
     * Handle the creation of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    public function handleCreation(NotifiableEntityInterface $entity): void
    {
        $strategy = $entity->getNotificationStrategy();

        // Delegate to the strategy to handle the creation logic
        $strategy->handleCreation();
    }


    /**
     * Handle the deletion of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    public function handleDeletion(NotifiableEntityInterface $entity): void
    {
        $strategy = $entity->getNotificationStrategy();

        // Delegate to the strategy to handle the creation logic
        $strategy->handleDeletion();
    }
}
