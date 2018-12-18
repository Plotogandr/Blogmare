<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class BlogPost extends Model
{

    protected $table = 'blog_posts';
    protected $primaryKey = 'post_id';
    protected $fillable = [
        'blog_id',
        'post_title',
        'post_text',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'blog_id');
    }

    public $timestamps = true;

}