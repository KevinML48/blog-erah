<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface NotificationServiceInterface
{
    /**
     * Handle the creation of a notification for the given entity.
     *
     * @param Model $entity The entity (like a Like or Follow) that triggered the notification.
     * @return void
     */
    public function handleCreation(Model $entity): void;

    /**
     * Handle the deletion of a notification for the given entity.
     *
     * @param Model $entity The entity (like a Like or Follow) that triggered the notification.
     * @return void
     */
    public function handleDeletion(Model $entity): void;
}
