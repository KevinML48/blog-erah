<?php

namespace App\Observers;

use App\Models\CommentContent;
use App\Models\Like;
use App\Services\NotificationServiceInterface;

class LikeObserver
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
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        if ($like->likeable_type == CommentContent::class && $like->user_id !== $like->likeable->user_id) {
            $this->notificationService->handleCreation($like);
        }
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like): void
    {
        $this->notificationService->handleDeletion($like);
    }
}
