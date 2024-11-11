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
}
