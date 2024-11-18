<?php

namespace App\Notifications;

use App\Contracts\NotificationStrategy;
use App\Models\Post;
use App\Strategies\PostNotificationStrategy;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublishedNotification extends Notification implements NotificationWithStrategyInterface
{
    use Queueable;

    public $post;
    protected $notificationData;

    /**
     * Create a new notification instance.
     */
    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'post_id' => $this->notificationData['post_id'],
        ];
    }

    public function post()
    {

        return Post::find($this->notificationData['post_id']);
    }

    public function getNotificationStrategy(): NotificationStrategy
    {
        return new PostNotificationStrategy();
    }
}
