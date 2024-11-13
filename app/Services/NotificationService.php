<?php

namespace App\Services;

use App\Contracts\BundledNotification;
use App\Contracts\NotifiableEntityInterface;
use App\Contracts\SingleNotification;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService implements NotificationServiceInterface
{

    /**
     * Handle the creation of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    public function handleCreation(NotifiableEntityInterface $entity): void
    {
        if ($entity instanceof BundledNotification) {
            $this->handleBundledCreation($entity);
        } elseif ($entity instanceof SingleNotification) {
            $this->handleSingleCreation($entity);
        }
    }

    /**
     * Handle the creation of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    protected function handleBundledCreation(NotifiableEntityInterface $entity): void
    {
        $user = $this->getUser($entity); // User who will receive the notification

        $typeName = $this->getNotificationType($entity);
        $contextType = $this->getContextType($entity);
        $contextId = $this->getContextId($entity);

        // Check if the user has enabled notifications for this event
        if (!$user->wantsNotification($typeName, $contextId, $contextType)) {
            return; // User disabled notifications for this event
        }

        // Check if an unread notification already exists for this context
        $notification = $user->notifications()
            ->where('type', $this->getNotificationClass($entity))
            ->where('data->context_id', $contextId)
            ->where('read_at', null) // Only consider unread notifications
            ->first();


        if ($notification) {
            $this->updateExistingNotification($notification, $entity);
        } else {
            $this->createNewNotification($user, $entity);
        }
    }


    /**
     * Handle the deletion of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    public function handleDeletion(NotifiableEntityInterface $entity): void
    {
        if ($entity instanceof BundledNotification) {
            $this->handleBundledDeletion($entity);
        } elseif ($entity instanceof SingleNotification) {
            $this->handleSingleDeletion($entity);
        }
    }
    /**
     * Handle the deletion of a notification.
     *
     * @param NotifiableEntityInterface $entity The entity that triggered the notification (like Follow or Like).
     */
    public function handleBundledDeletion(NotifiableEntityInterface $entity): void
    {
        $contextId = $this->getContextId($entity);
        $user = $this->getUser($entity);

        // Retrieve the notification for the user

        $notification = $user->notifications()
            ->where('type', $this->getNotificationClass($entity))
            ->where('data->context_id', $contextId)
            ->where('read_at', null)
            ->first();


        if ($notification) {
            $this->removeLikeFromNotification($notification, $entity);
        }
    }

    /**
     * Get the notification type based on the entity.
     *
     * @param NotifiableEntityInterface $entity
     * @return string
     */
    protected function getNotificationType(NotifiableEntityInterface $entity): string
    {
        return $entity->getNotificationType();
    }

    /**
     * Get the context type (like 'comment') based on the entity.
     *
     * @param NotifiableEntityInterface $entity
     * @return string
     */
    protected function getContextType(NotifiableEntityInterface $entity)
    {
        return $entity->getContextType();
    }

    /**
     * Get the context ID (like comment ID) based on the entity.
     *
     * @param NotifiableEntityInterface $entity
     * @return int
     */
    protected function getContextId(NotifiableEntityInterface $entity)
    {
        return $entity->getContextId();
    }

    /**
     * Get the notification class based on the entity.
     *
     * @param NotifiableEntityInterface $entity
     * @return string
     */
    protected function getNotificationClass(NotifiableEntityInterface $entity): string
    {
        return $entity->getNotificationClass();
    }

    /**
     * Update an existing notification with the new entity data.
     *
     * @param DatabaseNotification $notification
     * @param NotifiableEntityInterface $entity
     */
    protected function updateExistingNotification(DatabaseNotification $notification, NotifiableEntityInterface $entity): void
    {
        $notificationData = $notification->data;
        $entityIds = $notificationData['ids'] ?? [];

        if (count($entityIds) < 3) {
            $entityIds[] = $entity->id;
            $notificationData['ids'] = $entityIds;
        }

        $notificationData['count'] = ($notificationData['count'] ?? 0) + 1;

        // Update the notification
        $notification->update([
            'data' => $notificationData,
        ]);
    }

    /**
     * Create a new notification for the entity.
     *
     * @param User $user
     * @param NotifiableEntityInterface $entity
     */
    protected function createNewNotification($user, NotifiableEntityInterface $entity)
    {
        $notificationClass = $this->getNotificationClass($entity); // Get the class name dynamically

        // Prepare the notification data
        $notificationData = [
            'ids' => [$entity->id],
            'count' => 1,
            'context_id' => $this->getContextId($entity),
        ];

        // Ensure the notification class is instantiated with the required data
        $notification = new $notificationClass($notificationData);

        // Notify the user
        $user->notify($notification);
    }


    /**
     * Remove a like from the notification data.
     *
     * @param DatabaseNotification $notification
     * @param NotifiableEntityInterface $entity
     */
    protected function removeLikeFromNotification(DatabaseNotification $notification, NotifiableEntityInterface $entity): void
    {
        $notificationData = $notification->data;
        $entityIds = $notificationData['ids'] ?? [];

        // Remove the entity ID
        $entityIds = array_filter($entityIds, fn($id) => $id !== $entity->id);
        $notificationData['ids'] = $entityIds;

        // Decrement the count
        $notificationData['count'] = max(0, count($entityIds));

        // If no entities are left, delete the notification
        if ($notificationData['count'] === 0) {
            $notification->delete();
        } else {
            $notification->update([
                'data' => $notificationData,
            ]);
        }
    }

    private function getUser(NotifiableEntityInterface $entity)
    {
        return $entity->targetUser();
    }

    private function handleSingleCreation(SingleNotification $entity)
    {
        $strategy = $entity->getNotificationStrategy();

        // Delegate to the strategy to handle the creation logic
        $strategy->handleCreation();
    }

    private function handleSingleDeletion(SingleNotification $entity)
    {
    }
}
