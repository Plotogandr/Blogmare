<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class CommentReply extends Model
{

    protected $table = 'comment_replies';

    protected $fillable = [
        'reply_id',
        'comment_id',
    ];

    public function commentReply()
    {
        return $this->belongsTo(Comment::class, 'reply_id', 'comment_id');
    }

    public function commentComment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }

    public $timestamps = true;

}