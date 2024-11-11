<?php

namespace App\Observers;

use App\Models\NotificationType;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        if ($post->publication_time && $post->publication_time->isPast()) {
            $this->notifyUsers($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    protected function notifyUsers(Post $post)
    {
        // Retrieve the notification type ID for post publication
        $notificationType = NotificationType::where('name', 'post_published')->first();

        if (!$notificationType) return;

        // Get users who have notifications enabled for this theme
        $users = User::whereHas('notificationPreferences', function ($query) use ($post) {
            $query->where('notification_type_id', NotificationType::where('name', 'post_published')->first()->id)
                ->where('context_id', $post->theme_id)
                ->where('context_type', 'theme')
                ->where('is_enabled', true);
        })->get();

        // Send notification to each user
        foreach ($users as $user) {
            $user->notify(new PostPublishedNotification($post));
        }
    }
}
