<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_content';

    protected $fillable = ['user_id', 'body'];

    public function structure()
    {
        return $this->hasOne(CommentStructure::class, 'content_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

