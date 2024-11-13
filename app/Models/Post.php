<?php

namespace App\Models;

use App\Contracts\NotificationStrategy;
use App\Contracts\SingleNotification;
use App\Strategies\PostNotificationStrategy;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model implements SingleNotification
{

    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'publication_time',
        'media',
        'user_id',
        'theme_id',
    ];

    protected $casts = [
        'publication_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->likes()->delete();
        });
    }

    public function getNotificationStrategy(): NotificationStrategy
    {
        return new PostNotificationStrategy($this);
    }
}
