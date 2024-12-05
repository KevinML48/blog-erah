<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotificationPreference extends Model
{
    protected $fillable = ['user_id', 'notification_type_id', 'is_enabled', 'context_id', 'context_type'];

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserIdsWhoDontWantNotification($notificationType, $themeId, $context)
    {
        return self::where('notification_type_id', $notificationType)
            ->where('context_id', $themeId)
            ->where('context_type', $context)
            ->where('is_enabled', false)
            ->pluck('user_id');
    }
}
