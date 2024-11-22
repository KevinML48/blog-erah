<?php

namespace App\Services;

use App\Contracts\NotifiableEntityInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationServiceInterface
{
    /**
     * Handle the creation of a notification for the given entity.
     *
     * @param NotifiableEntityInterface $entity The entity (like a Like or Follow) that triggered the notification.
     * @return void
     */
    public function handleCreation(NotifiableEntityInterface $entity): void;

    /**
     * Handle the deletion of a notification for the given entity.
     *
     * @param NotifiableEntityInterface $entity The entity (like a Like or Follow) that triggered the notification.
     * @return void
     */
    public function handleDeletion(NotifiableEntityInterface $entity): void;

    /**
     * Process the notification to remove any obsolete ones and render them for the front-end.
     *
     * @param LengthAwarePaginator $notifications A paginated list of notifications
     * @return void
     */
    public function processNotifications(LengthAwarePaginator $notifications): void;

    /**
     * Update a single notification preference.
     *
     * @param Authenticatable $user The user for whom the notification preference is being updated.
     * @param string $notificationTypeName Name of the notification type (e.g., 'post_published').
     * @param string $contextType Type of context (e.g., 'theme', 'global').
     * @param int|null $contextId The ID of the context (e.g., theme ID) or null for global settings.
     * @param bool $isEnabled Whether the notification is enabled.
     */
    public function updateNotificationPreferences(Authenticatable $user, string $notificationTypeName, string $contextType, ?int $contextId, bool $isEnabled): void;
}
