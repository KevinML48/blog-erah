<?php

namespace App\Observers;

use App\Models\CommentContent;
use App\Services\NotificationServiceInterface;

class CommentContentObserver
{
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
     * Handle the CommentContent "created" event.
     */
    public function created(CommentContent $commentContent): void
    {
        //
    }

    /**
     * Handle the CommentContent "updated" event.
     */
    public function updated(CommentContent $commentContent): void
    {
        //
    }

    /**
     * Handle the CommentContent "deleted" event.
     */
    public function deleted(CommentContent $commentContent): void
    {
        //
    }

    /**
     * Handle the CommentContent "restored" event.
     */
    public function restored(CommentContent $commentContent): void
    {
        $this->notificationService->handleDeletion($commentContent->comment);
    }

    /**
     * Handle the CommentContent "force deleted" event.
     */
    public function forceDeleted(CommentContent $commentContent): void
    {
        //
    }
}
