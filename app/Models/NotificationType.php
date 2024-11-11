<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = ['name', 'description', 'category'];

    public function preferences()
    {
        return $this->hasMany(UserNotificationPreference::class);
    }
}
