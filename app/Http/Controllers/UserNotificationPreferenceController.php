<?php

namespace App\Http\Controllers;

use App\Models\CommentContent;
use App\Models\Theme;
use App\Services\NotificationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationPreferenceController extends Controller
{

    protected NotificationServiceInterface $notificationService;
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        // Update post published notification preferences by theme
        foreach (Theme::all() as $theme) {
            $isEnabled = array_key_exists($theme->id, $request->input('post_preferences', []));
            $this->notificationService->updateNotificationPreferences(
                $user,
                'post_published',
                'theme',
                $theme->id,
                $isEnabled
            );
        }

        // Update global reply notification preferences
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_reply',
            'global',
            null,
            $request->boolean('reply_notifications_enabled')
        );

        // Update global like notification preferences
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_like',
            'global',
            null,
            $request->boolean('like_notifications_enabled')
        );

        return redirect()->back()->with('success', __('message.user-notification-preference.success.update'));
    }

    public function muteComment(CommentContent $commentContent)
    {
        $user = Auth::user();
        $commentContentId = $commentContent->id;

        // Mute reply notifications for the comment
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_reply',
            'single',
            $commentContentId,
            false
        );

        // Mute like notifications for the comment
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_like',
            'single',
            $commentContentId,
            false
        );

        return response()->json([
            'status' => 'success',
            'message' => __('message.user-notification-preference.success.mute-comment'),
        ]);
    }

    public function unmuteComment(CommentContent $commentContent)
    {
        $user = Auth::user();
        $commentContentId = $commentContent->id;

        // Unmute reply notifications for the comment
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_reply',
            'single',
            $commentContentId,
            true
        );

        // Unmute like notifications for the comment
        $this->notificationService->updateNotificationPreferences(
            $user,
            'comment_like',
            'single',
            $commentContentId,
            true
        );

        return response()->json([
            'status' => 'success',
            'message' => __('message.user-notification-preference.success.unmute-comment'),
        ]);
    }
}
