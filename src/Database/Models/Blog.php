<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Account\Database\Models\User;
use UserFrosting\Sprinkle\Core\Database\Models\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'blog_id';
    protected $fillable = [
        'id',
        'blog_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function followed()
    {
        return $this->belongsToMany(User::class, 'blog_user', 'blog_id', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'blog_id', 'blog_id');
    }

    public $timestamps = true;
}