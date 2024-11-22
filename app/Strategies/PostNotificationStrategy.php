<?php

namespace App\Strategies;

use App\Contracts\NotificationStrategy;
use App\Models\NotificationType;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PostNotificationStrategy implements NotificationStrategy
{
    protected $post;

    public function __construct(Post $post= null)
    {
        $this->post = $post ?? new Post();
    }

    public function handleCreation(): void
    {
        Log::info($this->post);
        // Logic for handling post creation
        $notificationType = NotificationType::where('name', 'post_published')->first();

        if (!$notificationType) {
            return;
        }

        $users = User::all()->filter(function ($user) use ($notificationType) {
            return $user->wantsNotification($notificationType->name, $this->post->theme_id, 'theme');
        });

        foreach ($users as $user) {
            $user->notify(new PostPublishedNotification(['post_id' => $this->post->id,]));
        }
    }

    public function handleDeletion(): void
    {
        // Delete notifications related to this post
        $notifications = Notification::where('data->post_id', $this->post->id)
            ->where('type', PostPublishedNotification::class)
            ->get();

        // Delete the notifications if found
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function processNotification(DatabaseNotification $notification)
    {
        $post = Post::find($notification->data['post_id']);

        if (!$post) {
            $notification->delete();
            return null;
        } else {
            $notification->body = view('notifications.partials.new_post', ['post' => $post])->render();
        }
    }
}
