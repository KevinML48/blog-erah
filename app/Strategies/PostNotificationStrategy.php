<?php

namespace App\Strategies;

use App\Contracts\NotificationStrategy;
use App\Models\NotificationType;
use App\Models\Post;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Notifications\PostPublishedNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class PostNotificationStrategy implements NotificationStrategy
{
    protected $post;

    public function __construct(Post $post= null)
    {
        $this->post = $post ?? new Post();
    }

    public function handleCreation(): void
    {
        // Logic for handling post creation
        $notificationType = NotificationType::where('name', 'post_published')->first();

        if (!$notificationType) {
            return;
        }

        // Get user IDs who don't want the notification
        $excludedUserIds = UserNotificationPreference::getUserIdsWhoDontWantNotification(
            $notificationType->id,
            $this->post->theme_id,
            'theme'
        );

        User::whereNotIn('id', $excludedUserIds)
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    $user->notify(new PostPublishedNotification(['post_id' => $this->post->id]));
                }
            });
    }

    public function handleDeletion(): void
    {
        // Delete notifications related to this post
        $notifications = DB::table('notifications')
            ->where('data', 'like', '%"post_id":' . $this->post->id . '%')
            ->where('type', PostPublishedNotification::class)
            ->get();

        // Delete the notifications if found
        foreach ($notifications as $notification) {
            DB::table('notifications')->where('id', $notification->id)->delete();
        }
    }

    public function processNotification(DatabaseNotification $notification, $authUser = null)
    {
        $post = Post::find($notification->data['post_id'])->with('user:id,name,username', 'theme:id,name')->firstOrFail();

        if (!$post) {
            $notification->delete();
            return null;
        } else {
            $notification->view = 'notifications.partials.new_post';
            $notification->args = [
                'post' => $post,
            ];
        }
    }
}
