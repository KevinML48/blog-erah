<?php

namespace App\Strategies;

use App\Contracts\NotificationStrategy;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;

class PostNotificationStrategy implements NotificationStrategy
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handleCreation(): void
    {
        // Logic for handling post creation
        $notificationType = PostPublishedNotification::where('name', 'post_published')->first();

        if (!$notificationType) {
            return;
        }

        $users = User::all()->filter(function ($user) use ($notificationType) {
            return $user->wantsNotification($notificationType, $this->post->theme_id, 'theme');
        });

        foreach ($users as $user) {
            $user->notify(new PostPublishedNotification($this->post));
        }
    }
}
