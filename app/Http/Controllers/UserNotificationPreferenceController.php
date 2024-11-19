<?php

namespace App\Http\Controllers;

use App\Models\CommentContent;
use App\Models\NotificationType;
use App\Models\Theme;
use Illuminate\Http\Request;

class UserNotificationPreferenceController extends Controller
{

    public function update(Request $request)
    {
        $user = auth()->user();

        // Update post published notification preferences by theme
        $postPublishedType = NotificationType::where('name', 'post_published')->first();
        $user->notificationPreferences()
            ->where('notification_type_id', $postPublishedType->id)
            ->where('context_type', 'theme')
            ->delete();

        foreach (Theme::all() as $theme) {
            // Check if the theme ID is not present in the request (unchecked checkbox)
            if (!array_key_exists($theme->id, $request->input('post_preferences', []))) {
                // Create an entry with `is_enabled` set to false
                $user->notificationPreferences()->create([
                    'notification_type_id' => $postPublishedType->id,
                    'context_id' => $theme->id,
                    'context_type' => 'theme',
                    'is_enabled' => false,
                ]);
            }
        }

        // Update global reply notification preferences
        $replyNotificationType = NotificationType::where('name', 'comment_reply')->first();
        $user->notificationPreferences()
            ->where('notification_type_id', $replyNotificationType->id)
            ->delete();

        if (!$request->has('reply_notifications_enabled') || !$request->input('reply_notifications_enabled')) {
            $user->notificationPreferences()->create([
                'notification_type_id' => $replyNotificationType->id,
                'context_id' => null,
                'context_type' => 'global',
                'is_enabled' => false,
            ]);
        }

        // Update global like notification preferences
        $likeNotificationType = NotificationType::where('name', 'comment_like')->first();
        $user->notificationPreferences()
            ->where('notification_type_id', $likeNotificationType->id)
            ->delete();

        if (!$request->has('like_notifications_enabled') || !$request->input('like_notifications_enabled')) {
            $user->notificationPreferences()->create([
                'notification_type_id' => $likeNotificationType->id,
                'context_id' => null,
                'context_type' => 'global',
                'is_enabled' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Préférences mises à jour');
    }

    public function muteComment(CommentContent $commentContent)
    {
        $user = auth()->user();

        // You don't need to validate comment_content_id anymore, as the model is automatically injected
        $commentContentId = $commentContent->id;

        // Create mute notifications for comment like and comment reply for this comment
        $replyNotificationType = NotificationType::where('name', 'comment_reply')->first();
        $likeNotificationType = NotificationType::where('name', 'comment_like')->first();

        // Check if the user has already muted these notifications for the comment
        $existingReplyNotification = $user->notificationPreferences()
            ->where('notification_type_id', $replyNotificationType->id)
            ->where('context_id', $commentContentId)
            ->where('context_type', 'single')
            ->first();

        if (!$existingReplyNotification) {
            // Mute the reply notifications if not already muted
            $user->notificationPreferences()->create([
                'notification_type_id' => $replyNotificationType->id,
                'context_id' => $commentContentId,
                'context_type' => 'single',
                'is_enabled' => false,
            ]);
        }

        // Check if the user has already muted like notifications for the comment
        $existingLikeNotification = $user->notificationPreferences()
            ->where('notification_type_id', $likeNotificationType->id)
            ->where('context_id', $commentContentId)
            ->where('context_type', 'single')
            ->first();

        if (!$existingLikeNotification) {
            // Mute the like notifications if not already muted
            $user->notificationPreferences()->create([
                'notification_type_id' => $likeNotificationType->id,
                'context_id' => $commentContentId,
                'context_type' => 'single',
                'is_enabled' => false,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Comment muted successfully',
        ]);
    }

    public function unmuteComment(CommentContent $commentContent)
    {
        $user = auth()->user();

        // We no longer need to validate the comment_content_id, since the model is injected
        $commentContentId = $commentContent->id;

        // Check if the reply notification entry exists
        $replyNotificationType = NotificationType::where('name', 'comment_reply')->first();
        $replyNotification = $user->notificationPreferences()
            ->where('notification_type_id', $replyNotificationType->id)
            ->where('context_id', $commentContentId)
            ->where('context_type', 'single')
            ->first();

        if ($replyNotification) {
            // Delete the reply notification entry if it exists
            $replyNotification->delete();
        }

        // Check if the like notification entry exists
        $likeNotificationType = NotificationType::where('name', 'comment_like')->first();
        $likeNotification = $user->notificationPreferences()
            ->where('notification_type_id', $likeNotificationType->id)
            ->where('context_id', $commentContentId)
            ->where('context_type', 'single')
            ->first();

        if ($likeNotification) {
            // Delete the like notification entry if it exists
            $likeNotification->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Comment unmuted successfully',
        ]);
    }
}
