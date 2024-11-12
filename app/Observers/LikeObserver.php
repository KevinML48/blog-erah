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
        //
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like): void
    {
        // Find the related notification for the comment
        $commentId = $like->comment_id;
        $notification = $like->likeable->user->notifications()
            ->where('type', CommentLikeNotification::class)
            ->where('data->comment_id', $commentId)
            ->where('read_at', null) // Only consider unread notifications
            ->first();

        if ($notification) {
            // Retrieve notification data and remove the deleted like's ID
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
                return; // Exit the method since the notification is deleted
            }

            // Otherwise, retrieve the most recent likes (up to the number of likes left, but no more than 3)
            $mostRecentLikes = Like::where('comment_id', $commentId)
                ->latest()
                ->take(min(3, $notificationData['like_count']))  // Limit to the remaining like count, up to 3
                ->pluck('id'); // Get the IDs of the latest likes

            // If no likes remain after deletion, delete the notification
            if ($mostRecentLikes->isEmpty()) {
                $notification->delete();
                return; // Exit the method since no likes remain
            }

            // Update the like_ids with the most recent ones
            $notificationData['like_ids'] = $mostRecentLikes->toArray();

            // Update the notification with the modified like_ids and like_count
            $notification->update([
                'data' => $notificationData,
            ]);
        }
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

    private function notifyUser(Like $like)
    {
        $user = $like->likeable->user; // The user who will receive the notification
        $commentId = $like->likeable->id; // The ID of the comment being liked

        // Check if the user has enabled notifications globally for comment likes
        $typeName = 'comment_like';
        $contextType = 'comment';
        $contextId = $commentId;

        // Check specific preference for this comment
        if (!$user->wantsNotification($typeName, $contextId, $contextType)) {
            return; // The user has disabled notifications for likes on this specific comment
        }

        // Check if there is an existing unread notification for this comment
        $notification = $user->notifications()
            ->where('type', CommentLikeNotification::class)
            ->where('data->comment_id', $commentId)
            ->where('read_at', null) // Only consider unread notifications
            ->first();

        if ($notification) {
            // If notification exists, retrieve its data
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

        } else {
            // No existing notification found, create a new one with initial data
            $notificationData = [
                'like_ids' => [$like->id],
                'comment_id' => $commentId,
                'like_count' => 1,
            ];

            // Create a new notification for the user
            $user->notify(new CommentLikeNotification($notificationData));
        }
    }


}
