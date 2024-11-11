<?php

namespace App\Http\Controllers;

use App\Models\NotificationType;
use App\Models\Theme;
use Illuminate\Http\Request;

class UserNotificationPreferenceController extends Controller
{
    public function index()
    {
        $themes = Theme::all();

        // Get the specific notification type for post publication
        $notificationType = NotificationType::where('name', 'post_published')->first();

        // Fetch user's preferences for this notification type, keyed by theme ID
        $preferences = auth()->user()
            ->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where('context_type', 'theme')
            ->get()
            ->keyBy('context_id'); // Key by theme ID for easy access in the view

        return view('notifications.preferences', compact('themes', 'preferences'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $notificationType = NotificationType::where('name', 'post_published')->first();

        // Clear existing preferences for this notification type and context
        $user->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where('context_type', 'theme')
            ->delete();

        // Save only enabled preferences
        foreach ($request->input('preferences', []) as $themeId => $isEnabled) {
            if ($isEnabled) {
                $user->notificationPreferences()->create([
                    'notification_type_id' => $notificationType->id,
                    'context_id' => $themeId,
                    'context_type' => 'theme',
                    'is_enabled' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Préférences mises à jour avec succès !');
    }
}
