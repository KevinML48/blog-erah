<?php

namespace App\Observers;

use App\Models\Like;
use App\Notifications\CommentLikeNotification;

class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        $this->notifyUser($like);
    }

    /**
     * Handle the Like "updated" event.
     */
    public function updated(Like $like): void
    {
        // No action required
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like): void
    {
        $this->handleLikeDeletion($like);
    }

    /**
     * Handle the Like "restored" event.
     */
    public function restored(Like $like): void
    {
        // No action required
    }

    /**
     * Handle the Like "force deleted" event.
     */
    public function forceDeleted(Like $like): void
    {
        // No action required
    }

    private function notifyUser(Like $like)
    {
        $user = $like->likeable->user; // The user who will receive the notification
        $commentId = $like->likeable->id; // The ID of the comment being liked

        // Check if notifications are enabled for the comment
        if (!$this->userWantsNotification($user, $commentId)) {
            return; // The user has disabled notifications for this specific comment
        }

        $notification = $this->getExistingNotification($user, $commentId);

        if ($notification) {
            $this->updateNotification($notification, $like);
        } else {
            $this->createNewNotification($user, $like, $commentId);
        }
    }

    private function handleLikeDeletion(Like $like)
    {
        $notification = $this->getExistingNotificationForLike($like);

        if ($notification) {
            $notificationData = $notification->data;
            $likeIds = $notificationData['like_ids'] ?? [];
            $likeCount = $notificationData['like_count'] ?? 0;

            // Remove the deleted like's ID from the like_ids array if it exists
            $likeIds = array_filter($likeIds, fn($id) => $id !== $like->id);
            $notificationData['like_ids'] = $likeIds;

            // Decrement the like count
            $notificationData['like_count'] = max($likeCount - 1, count($likeIds));

            // If the like count is 0, delete the notification
            if ($notificationData['like_count'] === 0) {
                $notification->delete();
                return;
            }

            // Otherwise, update the notification with the most recent likes
            $this->updateNotificationLikes($notification, $like, $notificationData);
        }
    }

    private function userWantsNotification($user, $commentId): bool
    {
        $typeName = 'comment_like';
        $contextType = 'comment';
        return $user->wantsNotification($typeName, $commentId, $contextType);
    }

    private function getExistingNotification($user, $commentId)
    {
        return $user->notifications()
            ->where('type', CommentLikeNotification::class)
            ->where('data->comment_id', $commentId)
            ->where('read_at', null)
            ->first();
    }

    private function getExistingNotificationForLike(Like $like)
    {
        $commentId = $like->comment_id;
        return $like->likeable->user->notifications()
            ->where('type', CommentLikeNotification::class)
            ->where('data->comment_id', $commentId)
            ->where('read_at', null)
            ->first();
    }

    private function updateNotification($notification, Like $like)
    {
        $notificationData = $notification->data;
        $likeIds = $notificationData['like_ids'] ?? [];

        // Add the new like ID only if there are fewer than 3 stored IDs
        if (count($likeIds) < 3) {
            $likeIds[] = $like->id;
            $notificationData['like_ids'] = $likeIds;
        }

        $notificationData['like_count'] = ($notificationData['like_count'] ?? 0) + 1;

        // Update the existing notification with the new data
        $notification->update([
            'data' => $notificationData,
        ]);
    }

    private function createNewNotification($user, Like $like, $commentId)
    {
        $notificationData = [
            'like_ids' => [$like->id],
            'comment_id' => $commentId,
            'like_count' => 1,
        ];

        // Create a new notification for the user
        $user->notify(new CommentLikeNotification($notificationData));
    }

    private function updateNotificationLikes($notification, Like $like, array &$notificationData)
    {
        $commentId = $like->comment_id;

        // Retrieve the most recent likes (up to the number of likes left, but no more than 3)
        $mostRecentLikes = Like::where('comment_id', $commentId)
            ->latest()
            ->take(min(3, $notificationData['like_count']))
            ->pluck('id');

        // If no likes remain after deletion, delete the notification
        if ($mostRecentLikes->isEmpty()) {
            $notification->delete();
            return;
        }

        // Update the like_ids with the most recent ones
        $notificationData['like_ids'] = $mostRecentLikes->toArray();

        // Update the notification with the modified like_ids and like_count
        $notification->update([
            'data' => $notificationData,
        ]);
    }
}
