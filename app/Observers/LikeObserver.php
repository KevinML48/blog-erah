<?php

namespace App\Observers;

use App\Models\CommentContent;
use App\Models\Like;
use App\Notifications\CommentLikeNotification;

class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        if ($like->likeable_type === CommentContent::class) {
            $content = $like->likeable;
            $content->user->notify(new CommentLikeNotification($content->comment));
        }
    }

    /**
     * Handle the Like "updated" event.
     */
    public function updated(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "restored" event.
     */
    public function restored(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "force deleted" event.
     */
    public function forceDeleted(Like $like): void
    {
        //
    }
}
