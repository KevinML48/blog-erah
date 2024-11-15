<?php

namespace App\Models;

use Database\Factories\CommentContentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CommentContent extends Model
{
    /** @use HasFactory<CommentContentFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_content';

    protected $fillable = [
        'user_id',
        'body',
        'media',
        'comment_id'
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                // Remove leading and trailing new lines
                $sanitizedContent = preg_replace("/^\s*\n|\n\s*$/", '', $value);

                // Remove empty lines between content (more than 1 consecutive new lines)
                $sanitizedContent = preg_replace("/\n\s*\n/", "\n", $sanitizedContent);

                return $sanitizedContent;
            }
        );
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}

