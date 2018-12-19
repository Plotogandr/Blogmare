<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class Comment extends Model
{

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = [
        'post_id',
        'id',
        'comment_text',
        'parent_comment_id',
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'post_id', 'post_id');
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }

    public function repliesRecursive()
    {
        return $this->replies()->with('repliesRecursive');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    public function commentRecursive()
    {
        return $this->comment()->with('commentRecursive');
    }

    public $timestamps = true;

}