<?php

namespace UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\System\Bakery\Migration;

class CommentRepliesTable extends Migration
{
    public $dependencies = [
        '\UserFrosting\Sprinkle\Blogmare\Database\Migrations\v100\CommentsTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RoleUsersTable',
    ];

    public function up()
    {
        if ( ! $this->schema->hasTable('comment_replies')) {
            $this->schema->create('comment_replies', function (Blueprint $table) {
                $table->integer('reply_id')->unsigned()->comment('Refers to the comment that is the comment to a comment');
                $table->integer('comment_id')->unsigned()->comment('Refers to the comment that is commented');
                $table->timestamps();

                $table->engine    = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset   = 'utf8';

                $table->primary(['reply_id']);
                $table->foreign('reply_id')->references('comment_id')->on('comments');
                $table->foreign('comment_id')->references('comment_id')->on('comments');
                $table->index('comment_id');
                $table->index('reply_id');
            });
        }
    }

    public function down()
    {
        $this->schema->drop('comment_replies');
    }
}