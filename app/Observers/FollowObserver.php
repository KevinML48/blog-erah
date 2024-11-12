<?php

namespace App\Observers;

use App\Models\Follow;
use App\Notifications\FollowNotification;
use Illuminate\Support\Facades\Log;

class FollowObserver
{
    /**
     * Handle the Follow "created" event.
     */
    public function created(Follow $follow): void
    {
        Log::info('FOLLOW CREATED');
        $this->notifyUser($follow);
    }

    /**
     * Handle the Follow "updated" event.
     */
    public function updated(Follow $follow): void
    {
        // No action required
    }

    /**
     * Handle the Follow "deleted" event.
     */
    public function deleted(Follow $follow): void
    {
        Log::info('FOLLOW DELETED');
        $this->handleFollowDeletion($follow);
    }

    /**
     * Handle the Follow "restored" event.
     */
    public function restored(Follow $follow): void
    {
        // No action required
    }

    /**
     * Handle the Follow "force deleted" event.
     */
    public function forceDeleted(Follow $follow): void
    {
        // No action required
    }

    private function notifyUser(Follow $follow)
    {
        $user = $follow->followed; // The user who will receive the notification

        $typeName = 'follow';

        // Check if the user has disabled notifications globally for follows
        if (!$user->wantsNotification($typeName)) {
            return; // The user has disabled notifications for this type
        }

        // Check if there is an existing unread notification
        $notification = $this->getExistingNotification($user);

        if ($notification) {
            $this->updateFollowNotification($notification, $follow);
        } else {
            $this->createNewFollowNotification($user, $follow);
        }
    }

    private function handleFollowDeletion(Follow $follow)
    {
        $notification = $this->getExistingNotificationForFollow($follow);

        if ($notification) {
            $notificationData = $notification->data;
            $followIds = $notificationData['follow_ids'] ?? [];
            $followCount = $notificationData['follow_count'] ?? 0;

            // Remove the deleted follow's ID from the follow_ids array if it exists
            $followIds = array_filter($followIds, fn($id) => $id !== $follow->id);
            $notificationData['follow_ids'] = $followIds;

            // Decrement the follow count
            $notificationData['follow_count'] = max($followCount - 1, count($followIds));

            // If the follow count is 0, delete the notification
            if ($notificationData['follow_count'] === 0) {
                $notification->delete();
                return;
            }

            // Otherwise, update the notification with the modified follow_ids and follow_count
            $this->updateNotificationFollows($notification, $follow, $notificationData);
        }
    }

    private function getExistingNotification($user)
    {
        return $user->notifications()
            ->where('type', FollowNotification::class)
            ->where('read_at', null)
            ->first();
    }

    private function getExistingNotificationForFollow(Follow $follow)
    {
        return $follow->followed->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->follow_ids', 'like', '%' . $follow->id . '%')
            ->where('read_at', null)
            ->first();
    }

    private function updateFollowNotification($notification, Follow $follow)
    {
        $notificationData = $notification->data;
        $followIds = $notificationData['follow_ids'] ?? [];

        // Add the new follow ID only if there are fewer than 3 stored IDs
        if (count($followIds) < 3) {
            $followIds[] = $follow->id;
            $notificationData['follow_ids'] = $followIds;
        }

        // Increment the follow count
        $notificationData['follow_count'] = ($notificationData['follow_count'] ?? 0) + 1;

        // Update the existing notification with the new data
        $notification->update([
            'data' => $notificationData,
        ]);
    }

    private function createNewFollowNotification($user, Follow $follow)
    {
        $notificationData = [
            'follow_ids' => [$follow->id],
            'follow_count' => 1,
        ];

        // Create a new notification for the user
        $user->notify(new FollowNotification($notificationData));
    }

    private function updateNotificationFollows($notification, Follow $follow, array &$notificationData)
    {
        $followIds = $notificationData['follow_ids'];

        // If no follows remain after deletion, delete the notification
        if (empty($followIds)) {
            $notification->delete();
            return;
        }

        // Update the notification with the modified follow_ids and follow_count
        $notification->update([
            'data' => $notificationData,
        ]);
    }
}
