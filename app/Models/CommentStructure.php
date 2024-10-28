<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentStructure extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_structure';

    protected $fillable = ['post_id', 'parent_id', 'content_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(CommentStructure::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(CommentStructure::class, 'parent_id');
    }

    public function content()
    {
        return $this->belongsTo(CommentContent::class, 'content_id');
    }

    public function contentExists()
    {
        return $this->content()->exists();
    }

    public function getBodyAttribute()
    {
        return $this->contentExists() ? $this->content->body : null;
    }
}
