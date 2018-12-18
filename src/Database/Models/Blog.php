<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Account\Database\Models\User;
use UserFrosting\Sprinkle\Core\Database\Models\Model;

class Blog extends Model
{
    protected $table = 'blogs';

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
        return $this->belongsToMany(User::class);
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public $timestamps = true;
}