<?php

namespace UserFrosting\Sprinkle\Blogmare\Blog;

use UserFrosting\Sprinkle\Blogmare\Database\Models\BlogPost;

class BlogPostService
{

    public function createBlogPost($blog_id, $post_title, $post_text)
    {
        $post = new BlogPost([
            'blog_id'    => $blog_id,
            'post_title' => $post_title,
            'post_text'  => $post_text,
        ]);
        $post->blog()->associate($blog_id)->save();
    }

    public function getPostById($post_id)
    {
        return BlogPost::query()->where('post_id', '=', $post_id)->with('blog.user')->first();
    }

}