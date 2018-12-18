<?php
/**
 * Created by PhpStorm.
 * User: czarnecki
 * Date: 12.12.18
 * Time: 09:48
 */

namespace UserFrosting\Sprinkle\Blogmare\Database\Models;


class User extends \UserFrosting\Sprinkle\Account\Database\Models\User
{
    public function follows()
    {
        return $this->belongsToMany(Blog::class);
    }
}