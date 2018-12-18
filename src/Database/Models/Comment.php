<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class Comment extends Model
{

    protected $table = 'comments';

    protected $fillable = [
        'post_id',
        'id',
        'date',
        'editDate',
        'comment_text',
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'post_id', 'post_id');
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }

    public $timestamps = true;

}