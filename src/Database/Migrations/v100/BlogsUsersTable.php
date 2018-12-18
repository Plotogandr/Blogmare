<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\System\Bakery\Migration;

class BlogsUsersTable extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100\BlogsTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RoleUsersTable',
    ];

    public function up()
    {
        if ( ! $this->schema->hasTable('blog_user')) {
            $this->schema->create('blog_user', function (Blueprint $table) {
                $table->integer('blog_id', false, true);
                $table->integer('user_id', false, true);

                $table->engine    = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset   = 'utf8';

                $table->foreign('blog_id')->references('blog_id')->on('blogs');
                $table->foreign('user_id')->references('id')->on('users');
                $table->primary(['blog_id', 'user_id']);
                $table->index('blog_id');
                $table->index('user_id');
            });
        }
    }

    public function down()
    {
        $this->schema->drop('blog_user');
    }
}