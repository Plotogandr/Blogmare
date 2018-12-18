<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class Following extends Model
{

    protected $table = 'following';

    protected $fillable = [
        'id',
        'blog_id',
    ];

    public $timestamps = true;
}