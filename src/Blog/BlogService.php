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
        $blog = Blog::query()->where('id', '=', $id);
        if ($blog == null) {
            return null;
        }

        return $blog->first();
    }

    public function getBlogByName($blog_name)
    {
        return $blog = Blog::query()->where("blog_name", '=', $blog_name)->with(['posts', 'user'])->first();
    }

    public function followBlog($blog_name, $id)
    {
        $blog = Blog::query()->where('blog_name', '=', $blog_name)->first();
        var_dump($blog);
        var_dump($id);
        $blog->followed()->attach($id);
    }

    public function unfollowBlog($blog, $id)
    {
//        $blog = Blog::query()->where('blog_name', '=', $blog_name)->first();
        $blog->followed()->detach($id);
    }

}