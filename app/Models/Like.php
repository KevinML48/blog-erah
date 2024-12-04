<?php

namespace App\Models;

use App\Contracts\BundledNotification;
use App\Contracts\NotificationStrategy;
use App\Notifications\CommentLikeNotification;
use App\Strategies\LikeNotificationStrategy;
use Database\Factories\LikeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model implements BundledNotification
{
    /** @use HasFactory<LikeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the parent likeable model (post or comment content).
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->hasOneThrough(
            Comment::class,          // Final model (Comment)
            CommentContent::class,  // Intermediate model (CommentContent)
            'comment_id',           // Foreign key on CommentContent (links to Like)
            'id',                // Foreign key on Comment (links to CommentContent)
            'likeable_id',         // Local key on Like (links to CommentContent)
            'comment_id'     // Local key on CommentContent (links to Comment)
        );
    }

    public function targetUser()
    {
        return $this->likeable->user;
    }

    public function getNotificationClass(): string
    {
        return CommentLikeNotification::class;
    }

    public function getNotificationType(): string
    {
        return 'comment_like';
    }

    public function getContextId()
    {
        return $this->likeable->id;
    }

    public function getContextType(): ?string
    {
        return 'single';
    }

    public function getNotificationStrategy(): NotificationStrategy
    {
        return new LikeNotificationStrategy($this);
    }
}
