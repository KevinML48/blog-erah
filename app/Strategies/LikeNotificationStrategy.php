<?php

namespace App\Strategies;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class LikeNotificationStrategy implements NotificationStrategy
{
    protected BundledNotification $entity;
    protected BundledNotificationStrategy $strategy;

    public function __construct(BundledNotification $entity =null)
    {
        $this->entity = $entity ?? new Like();
        $this->strategy = new BundledNotificationStrategy($entity);
    }

    public function handleCreation(): void
    {
        $this->strategy->handleCreation();
    }

    public function handleDeletion(): void
    {
        $this->strategy->handleDeletion();
    }

    public function processNotification($notification)
    {
        Log::info('in like strategy');
        $comment = Comment::find($notification->data['context_id']);

        if (!$comment || !$comment->contentExists()) {
            $notification->delete();
            return null;
        }

        $likeIds = $notification->data['ids'] ?? [];
        $likes = Like::whereIn('id', $likeIds)->take(3)->get();

        if ($likes->isEmpty()) {
            $notification->delete();
            return null;
        } else {
            $notification->body = Blade::render(
                '<x-notification-bundle :type="\'like\'" :list="$list" :count="$count"/>',
                [
                    'list' => $likes,
                    'count' => count($likeIds),
                ]
            );
        }
    }
}
