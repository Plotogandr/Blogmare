<?php

namespace UserFrosting\Sprinkle\Blogmare\Blog;

use UserFrosting\Sprinkle\Blogmare\Database\Models\Blog;
use UserFrosting\Sprinkle\Blogmare\Database\Models\BlogPost;
use UserFrosting\Sprinkle\Blogmare\Database\Models\Comment;

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

    public function getFollowedBlogs($id)
    {
        return Blog::query()
                   ->join('blog_user', 'blog_user.blog_id', '=', 'blogs.blog_id')
                   ->join('blog_posts', 'blogs.blog_id', '=', 'blog_posts.blog_id')
                   ->select('blogs.*',
                            'blog_posts.post_title as bp_title',
                            'blog_posts.post_text as bp_text',
                            'blog_posts.created_at as bp_created_at',
                            'blog_posts.updated_at as bp_updated_at',
                            'blog_posts.post_id as bp_id')
                   ->where('blog_user.user_id', '=', $id)
                   ->get()->sortByDesc('bp_created_at')->all();
    }

    public function getBlogByName($blog_name)
    {
        return Blog::query()->where("blog_name", '=', $blog_name)->with(['posts', 'user'])->first();
    }

    public function followBlog($blog, $id)
    {
        $blog->followed()->attach($id);
    }

    public function unfollowBlog($blog, $id)
    {
        $blog->followed()->detach($id);
    }

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

    public function createComment($post_id, $id, $comment_text)
    {
        $comment = new Comment([
            'post_id'      => $post_id,
            'id'           => $id,
            'comment_text' => $comment_text,
        ]);
        $comment->blogPost()->associate($post_id);
        $comment->writer()->associate($id);
        $comment->save();
    }

    public function getCommentsByPostId($post_id)
    {
        return Comment::query()->where([
            ['post_id', '=', $post_id],
            ['parent_comment_id', '=', 0],
        ])->with('repliesRecursive')->get()->all();
    }

    public function createCommentReply($post_id, $id, $comment_text, $comment_id)
    {
        $comment = new Comment([
            'post_id'      => $post_id,
            'id'           => $id,
            'comment_text' => $comment_text,
            'comment_id'   => $comment_id,
        ]);

        $originalComment = Comment::query()->find($comment_id)->first();
        $comment->writer()->associate($id);
        $comment->blogPost()->associate($post_id);
        $comment->comment()->associate($comment_id);
        $comment->save();
        $originalComment->replies();
    }

    public function editPost($post, $new_post_title, $new_post_text)
    {
        $post->post_title = $new_post_title;
        $post->post_text = $new_post_text;
        $post->update();
    }
}
