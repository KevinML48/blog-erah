<?php

namespace App\Models;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Notifications\FollowNotification;
use App\Strategies\BundleNotificationStrategy;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model implements BundledNotification
{
    protected $fillable = ['follower_id', 'followed_id'];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }

    public function targetUser()
    {
        return $this->followed;
    }

    public function getNotificationClass(): string
    {
        return FollowNotification::class;
    }

    public function getNotificationType(): ?string
    {
        return 'follow';
    }

    public function getContextId(): ?int
    {
        return null;
    }

    public function getContextType(): ?string
    {
        return null;
    }

    public function getNotificationStrategy(): NotificationStrategy
    {
        return new BundleNotificationStrategy($this);
    }
}
