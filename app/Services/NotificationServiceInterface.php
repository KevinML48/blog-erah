<?php

namespace App\Services;

use App\Contracts\NotifiableEntityInterface;
use Illuminate\Database\Eloquent\Model;
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
     * @param Model $entity The entity (like a Like or Follow) that triggered the notification.
     * @return void
     */
    public function handleDeletion(NotifiableEntityInterface $entity): void;

    /**
     * Process the notification to remove any obsolete ones.
     *
     * @param LengthAwarePaginator $notifications A paginated list of notifications
     * @return void
     */
    public function processNotifications(LengthAwarePaginator $notifications): void;
}
