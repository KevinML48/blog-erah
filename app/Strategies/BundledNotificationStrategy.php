<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class BundledNotificationStrategy implements NotificationStrategy
{
    protected $entity;

    public function __construct(BundledNotification $entity)
    {
        $this->entity = $entity;
    }

    public function handleCreation(): void
    {
        $user = $this->getUser($this->entity); // User who will receive the notification

        $typeName = $this->getNotificationType($this->entity);
        $contextType = $this->getContextType($this->entity);
        $contextId = $this->getContextId($this->entity);

        // Check if the user has enabled notifications for this event
        if (!$user->wantsNotification($typeName, $contextId, $contextType)) {
            return; // User disabled notifications for this event
        }

        // Check if an unread notification already exists for this context
        $notification = $user->notifications()
            ->where('type', $this->getNotificationClass($this->entity))
            ->where('data->context_id', $contextId)
            ->where('read_at', null) // Only consider unread notifications
            ->first();


        if ($notification) {
            $this->updateExistingNotification($notification, $this->entity);
        } else {
            $this->createNewNotification($user, $this->entity);
        }
    }

    public function handleDeletion(): void
    {
        $contextId = $this->getContextId($this->entity);
        $user = $this->getUser($this->entity);

        // Retrieve the notification for the user

        $notification = $user->notifications()
            ->where('type', $this->getNotificationClass($this->entity))
            ->where('data->context_id', $contextId)
            ->where('read_at', null)
            ->first();


        if ($notification) {
            $this->removeLikeFromNotification($notification, $this->entity);
        }
    }

    /**
     * Get the notification type based on the entity.
     *
     * @param BundledNotification $entity
     * @return string
     */
    protected function getNotificationType(BundledNotification $entity): string
    {
        return $entity->getNotificationType();
    }

    /**
     * Get the context type (like 'comment') based on the entity.
     *
     * @param BundledNotification $entity
     * @return string
     */
    protected function getContextType(BundledNotification $entity)
    {
        return $entity->getContextType();
    }


    /**
     * Get the context ID (like comment ID) based on the entity.
     *
     * @param BundledNotification $entity
     * @return int
     */
    protected function getContextId(BundledNotification $entity)
    {
        return $entity->getContextId();
    }

    /**
     * Get the notification class based on the entity.
     *
     * @param BundledNotification $entity
     * @return string
     */
    protected function getNotificationClass(BundledNotification $entity): string
    {
        return $entity->getNotificationClass();
    }

    /**
     * Update an existing notification with the new entity data.
     *
     * @param DatabaseNotification $notification
     * @param BundledNotification $entity
     */
    protected function updateExistingNotification(DatabaseNotification $notification, BundledNotification $entity): void
    {
        $notificationData = $notification->data;
        $entityIds = $notificationData['ids'] ?? [];

        $entityIds[] = $entity->id;
        $notificationData['ids'] = $entityIds;

        // Update the notification
        $notification->update([
            'data' => $notificationData,
        ]);
    }

    /**
     * Create a new notification for the entity.
     *
     * @param User $user
     * @param BundledNotification $entity
     */
    protected function createNewNotification($user, BundledNotification $entity)
    {
        $notificationClass = $this->getNotificationClass($entity); // Get the class name dynamically

        // Prepare the notification data
        $notificationData = [
            'ids' => [$entity->id],
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
     * @param BundledNotification $entity
     */
    protected function removeLikeFromNotification(DatabaseNotification $notification, BundledNotification $entity): void
    {
        $notificationData = $notification->data;
        $entityIds = $notificationData['ids'] ?? [];

        // Remove the entity ID
        $entityIds = array_filter($entityIds, fn($id) => $id !== $entity->id);
        $notificationData['ids'] = $entityIds;

        // If no entities are left, delete the notification
        if (count($entityIds) === 0) {
            $notification->delete();
        } else {
            $notification->update([
                'data' => $notificationData,
            ]);
        }
    }

    private function getUser(BundledNotification $entity)
    {
        return $entity->targetUser();
    }


}
