<?php

namespace App\Services;

use App\Contracts\NotifiableEntityInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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


    public function processNotifications(LengthAwarePaginator $notifications): void
    {
        $notifications->getCollection()->transform(function ($notification) {
            $notification->unread = $notification->read_at ? '' : true;
            $notificationClass = $notification->type;

            if (class_exists($notificationClass)) {
                $notificationInstance = new $notificationClass($notification);
                $strategy = $notificationInstance->getNotificationStrategy();
                $strategy->processNotification($notification);
            } else {
                $notification->delete();
                return null;
            }

            return $notification; // Returning the modified notification
        });

        $notifications->getCollection()->reject(function ($notification) {
            return $notification === null;
        });
    }

}
