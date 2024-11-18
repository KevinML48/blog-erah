<?php

namespace App\Http\Controllers;

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
}
