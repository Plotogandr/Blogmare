<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\System\Bakery\Migration;

class CommentsTable extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100\BlogPostsTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RoleUsersTable',
    ];

    public function up()
    {
        if ( ! $this->schema->hasTable('comments')) {
            $this->schema->create('comments', function (Blueprint $table) {
                $table->increments('comment_id');
                $table->integer('post_id')->unsigned();
                $table->integer('id')->unsigned();
                $table->date('date');
                $table->date('editDate');
                $table->text('comment_text');
                $table->timestamps();

                $table->engine    = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset   = 'utf8';

                $table->foreign('post_id')->references('post_id')->on('blog_posts');
                $table->foreign('id')->references('id')->on('users');
                $table->index('comment_id');
                $table->index('post_id');
                $table->index('id');
            });
        }
    }

    public function down()
    {
        $this->schema->drop('comments');
    }
}