<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'name',
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
        return $this->morphedByMany(CommentContent::class, 'likeable', 'likes');
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

        // Check for a specific preference with context
        $specificPreference = $this->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where('context_id', $contextId)
            ->where('context_type', $contextType)
            ->first();

        if ($specificPreference) {
            return $specificPreference->is_enabled;
        }
        // Check for a general preference if no specific context preference exists
        $generalPreference = $this->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->whereNull('context_id')
            ->where('context_type', 'global')
            ->first();
        return $generalPreference ? $generalPreference->is_enabled : true; // Default to true if no preference is found
    }

    public function wantsCategoryNotifications($category)
    {
        $notificationTypes = NotificationType::where('category', $category)->pluck('id');

        return $this->notificationPreferences()
            ->whereIn('notification_type_id', $notificationTypes)
            ->where('is_enabled', true)
            ->exists();
    }

    public function wantsThemeNotification($themeId)
    {
        // 'theme_posts' is the notification type for theme-related updates
        $notificationType = NotificationType::where('name', 'theme_posts')->first();

        return $this->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->where('context_id', $themeId)
            ->where('context_type', 'theme')
            ->where('is_enabled', true)
            ->exists();
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
