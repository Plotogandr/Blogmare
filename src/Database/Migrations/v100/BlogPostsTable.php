<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\System\Bakery\Migration;

class BlogPostsTable extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100\BlogsTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RoleUsersTable',
    ];

    public function up()
    {
        if ( ! $this->schema->hasTable('blog_posts')) {
            $this->schema->create('blog_posts', function (Blueprint $table) {
                $table->increments('post_id');
                $table->integer('blog_id', false, true);
                $table->string('post_title', 50);
                $table->text('post_text');
                $table->timestamps();

                $table->engine    = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset   = 'utf8';

                $table->foreign('blog_id')->references('blog_id')->on('blogs');
                $table->index('blog_id');
                $table->index('post_id');
                $table->index('post_title');
            });
        }
    }

    public function down()
    {
        $this->schema->drop('blog_posts');
    }
}