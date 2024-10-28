<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentContent extends Model
{
    protected $fillable = ['user_id', 'body'];

    public function comment()
    {
        return $this->hasOne(CommentStructure::class, 'content_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

