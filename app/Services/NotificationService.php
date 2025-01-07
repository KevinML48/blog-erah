<?php

namespace App\Services;

use App\Contracts\NotifiableEntityInterface;
use App\Models\NotificationType;
use Illuminate\Contracts\Auth\Authenticatable;
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


    public function processNotifications(LengthAwarePaginator $notifications, Authenticatable $authUser)
    {
        $notifications->getCollection()->transform(function ($notification) use ($authUser) {
            $unread = $notification->read_at ? '' : true;
            $notification->markAsRead();
            $notification->unread = $unread;
            $notificationClass = $notification->type;

            if (class_exists($notificationClass)) {
                $notificationInstance = new $notificationClass($notification);
                $strategy = $notificationInstance->getNotificationStrategy();
                $strategy->processNotification($notification, $authUser);
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

    public function updateNotificationPreferences(
        Authenticatable $user,
        string $notificationTypeName,
        string $contextType,
        ?int $contextId,
        bool $isEnabled
    ): void {
        $notificationType = NotificationType::where('name', $notificationTypeName)->first();

        $existingPreference = $user->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where('context_type', $contextType)
            ->where('context_id', $contextId)
            ->first();

        if ($existingPreference && !$existingPreference->is_enabled && !$isEnabled) {
            return;
        }

        if ($existingPreference) {
            $existingPreference->delete();
        }
        
        if (!$isEnabled) {
            $user->notificationPreferences()->create([
                'notification_type_id' => $notificationType->id,
                'context_id' => $contextId,
                'context_type' => $contextType,
                'is_enabled' => $isEnabled,
            ]);
        }
    }

}
