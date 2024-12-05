<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function commentContents(): HasMany
    {
        return $this->hasMany(CommentContent::class);
    }

    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Comment::class,
            CommentContent::class,
            'user_id',
            'id',
            'id',
            'comment_id'
        );
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->morphedByMany(Post::class, 'likeable', 'likes');
    }

    public function likedComments()
    {
        return $this->hasManyThrough(
            Comment::class,
            Like::class,
            'user_id',
            'id',
            'id',
            'likeable_id'
        )->where('likeable_type', CommentContent::class);
    }

    public function isLiking($likeable)
    {
        return $this->likes()
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->exists();
    }

    // The Users that the current User follows
    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function isFollowing(User $user)
    {
        return $this->follows()->where('followed_id', $user->id)->exists();
    }

    public function notificationPreferences()
    {
        return $this->hasMany(UserNotificationPreference::class);
    }

    public function wantsNotification($typeName, $contextId = null, $contextType = 'global')
    {
        // Fetch the notification type by name
        $notificationType = NotificationType::where('name', $typeName)->first();
        if (!$notificationType) {
            return false; // Notification type does not exist
        }

        // Fetch the preference, considering both specific and global preferences in one query
        $preference = $this->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where(function($query) use ($contextId, $contextType) {
                $query->where('context_id', $contextId)
                    ->where('context_type', $contextType)
                    ->orWhere(function($query) {
                        $query->whereNull('context_id')
                            ->where('context_type', 'global');
                    });
            })
            ->first();

        // If a preference is found, return its 'is_enabled' status, otherwise default to true
        return $preference ? $preference->is_enabled : true;
    }


    public function hasMuted(CommentContent $content): bool
    {
        $wantsReplyNotification = $this->wantsNotification('comment_reply', $content->id, 'single');

//        $wantsLikeNotification = $this->wantsNotification('comment_like', $content->id, 'single');

        return !$wantsReplyNotification;
    }

    public function mutedContents(): array
    {
        // Get the notification types for 'comment_reply' and 'comment_like'
        $notificationTypes = NotificationType::whereIn('name', ['comment_reply', 'comment_like'])
            ->pluck('id')
            ->toArray();

        // Get the muted content IDs from user_notification_preferences for the 'single' context_type
        $mutedContentIds = UserNotificationPreference::whereIn('notification_type_id', $notificationTypes)
            ->where('context_type', 'single')
            ->pluck('context_id')
            ->unique() // Ensures unique content IDs
            ->toArray();

        return $mutedContentIds;
    }

    /**
     * Get the number of unread notifications for the user.
     *
     * @return int
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->count();
    }
}
