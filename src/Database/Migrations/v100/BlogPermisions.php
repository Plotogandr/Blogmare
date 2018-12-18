<?php
/**
 * Created by PhpStorm.
 * User: czarnecki
 * Date: 17.12.18
 * Time: 15:25
 */

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use UserFrosting\Sprinkle\Account\Database\Models\Permission;
use UserFrosting\System\Bakery\Migration;

class BlogPermisions extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\PermissionsTable',
    ];

    public function up()
    {
        foreach ($this->blogPermissions() as $blogPermission) {
            $permission = new Permission($blogPermission);
            $permission->save();
        }
    }

    protected function blogPermissions()
    {
        return [
            [
                'slug'        => 'create_blog',
                'name'        => 'Create a blog',
                'conditions'  => 'no_blog(user.id)',
                'description' => 'Allows the user to create a blog if he has none yet.',
            ],
        ];
    }

    public function down()
    {
        foreach ($this->blogPermissions() as $blogPermission) {
            $permission = Permission::query()->where($blogPermission)->first();
            $permission->delete();
        }
    }
}