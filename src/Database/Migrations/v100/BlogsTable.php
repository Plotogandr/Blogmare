<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\System\Bakery\Migration;

class BlogsTable extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RoleUsersTable',
    ];

    public function up()
    {
        if ( ! $this->schema->hasTable('blogs')) {
            $this->schema->create('blogs', function (Blueprint $table) {
                $table->increments('blog_id');
                $table->integer('id', false, true);
                $table->string('blog_name', 30);
                $table->timestamps();

                $table->engine    = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset   = 'utf8';

                $table->unique('id');
                $table->unique('blog_name');
                $table->foreign('id')->references('id')->on('users');
                $table->index('blog_id');
                $table->index('blog_name');
            });
        }
    }

    public function down()
    {
        $this->schema->drop('blogs');
    }
}