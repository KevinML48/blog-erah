<?php

namespace App\Observers;

use App\Models\Follow;
use App\Services\NotificationServiceInterface;

class FollowObserver
{
    protected NotificationServiceInterface $notificationService;

    /**
     * Create a new observer instance.
     *
     * @param \App\Services\NotificationServiceInterface $notificationService
     * @return void
     */
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Follow "created" event.
     */
    public function created(Follow $follow): void
    {
        $this->notificationService->handleCreation($follow);
    }

    /**
     * Handle the Follow "deleted" event.
     */
    public function deleted(Follow $follow): void
    {
        $this->notificationService->handleDeletion($follow);
    }
}
