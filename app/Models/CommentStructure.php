<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentStructure extends Model
{
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
}
