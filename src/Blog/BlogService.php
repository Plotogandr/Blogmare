<?php

namespace UserFrosting\Sprinkle\Blogmare\Blog;

use UserFrosting\Sprinkle\Blogmare\Database\Models\Blog;

class BlogService
{

    public function createBlog($blog_name, $id)
    {
        $blog = new Blog([
            'id'        => $id,
            'blog_name' => $blog_name,
        ]);
        $blog->user()->associate($id)->save();
    }

    public function getBlogNameById($id)
    {

        return $blog = Blog::query()->find($id)->getAttribute("blog_name");
    }

    public function getBlogById($id)
    {
        return $blog = Blog::query()->findOrFail($id);
    }

    public function getBlogByName($blog_name)
    {
        return $blog = Blog::query()->where("blog_name", '=', $blog_name)->with(['posts', 'user'])->first();
    }

}