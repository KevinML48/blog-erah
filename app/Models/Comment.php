<?php

namespace App\Models;

use App\Contracts\NotificationStrategy;
use App\Contracts\SingleNotification;
use App\Strategies\CommentNotificationStrategy;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model implements SingleNotification
{

    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    protected $fillable = ['post_id', 'parent_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function content(): HasOne
    {
        return $this->hasOne(CommentContent::class);
    }

    public function contentExists(): bool
    {
        return $this->content()->exists();
    }

    public function likes()
    {
        return $this->hasManyThrough(
            Like::class,
            CommentContent::class,
            'comment_id',
            'likeable_id',
            'id',
            'id'
        )
            ->where('likeable_type', CommentContent::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($comment) {
            $comment->content()->delete();
        });
    }

    public function user()
    {
        return $this->content()->user();
    }

    public function getNotificationStrategy(): NotificationStrategy
    {
        return new CommentNotificationStrategy($this);
    }
}
