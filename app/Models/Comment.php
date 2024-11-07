<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    protected $fillable = ['post_id', 'parent_id', 'content_id'];

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

    public function content(): BelongsTo
    {
        return $this->belongsTo(CommentContent::class, 'content_id');
    }

    public function contentExists(): bool
    {
        return $this->content()->exists();
    }

    protected static function booted(): void
    {
        static::deleting(function ($comment) {
            $comment->content()->delete();
        });
    }
}
