<?php
/**
 * Created by PhpStorm.
 * User: czarnecki
 * Date: 06.12.18
 * Time: 16:46
 */

namespace UserFrosting\Sprinkle\Blogmare\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Blogmare\Database\Models\Blog;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\ForbiddenException;

class BlogController extends SimpleController
{

    public function getIndex(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        if ($currentUser == null) {
            return $this->ci->view->render($response, 'pages/index.html.twig');
        }

        return $response->withRedirect('/home');
    }

    public function pageList(Request $request, Response $response, $args)
    {
        $blogs = Blog::all();

        return $this->ci->view->render($response, 'pages/blogs.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    public function getCreateBlog(Request $request, Response $response, $args)
    {
        $authorizer  = $this->ci->authorizer;
        $currentUser = $this->ci->currentUser;

        if ( ! $authorizer->checkAccess($currentUser, 'create_blog', [
            'user' => $currentUser,
        ])) {
            throw new ForbiddenException();
        }
        $schema    = new RequestSchema('schema://create_blog.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'pages/createblog.html.twig', [
            'page' => [
                'validators' => [
                    'createblog' => $validator->rules('JSON', false),
                ],
            ],
        ]);
    }

    public function postCreateBlog(Request $request, Response $response, $args)
    {
        $ms     = $this->ci->alerts;
        $params = $request->getParsedBody();
        $id     = $this->ci->session['account.current_user_id'];

        $authorizer  = $this->ci->authorizer;
        $currentUser = $this->ci->currentUser;

        if ( ! $authorizer->checkAccess($currentUser, 'create_blog', [
            'user' => $currentUser,
        ])) {
            throw new ForbiddenException();
        }

        $schema      = new RequestSchema('schema://create_blog.yaml');
        $transformer = new RequestDataTransformer($schema);
        $form        = $transformer->transform($params);
        $validator   = new ServerSideValidator($schema, $this->ci->translator);

        if ( ! $validator->validate($form)) {
            $ms->addValidationErrors($validator);

            return $response->withStatus(400);
        }

        $this->ci->blog->createBlog($form["blog_name"], $id);

        return $response->withRedirect("/blogs/my_blog");
    }

    public function getBlog(Request $request, Response $response, $args)
    {
        $blog = $this->ci->blog->getBlogByName($args["blog_name"]);
        return $this->ci->view->render($response, 'pages/blog.html.twig', [
            "blog" => $blog,
        ]);
    }

    public function getWritePost(Request $request, Response $response, $args)
    {
        $schema      = new RequestSchema('schema://create_post.yaml');
        $validator   = new JqueryValidationAdapter($schema, $this->ci->translator);
        return $this->ci->view->render($response, 'pages/createpost.html.twig', [
            "blog_name" => $args["blog_name"],
            "page"      => [
                'validators' => [
                    'createpost' => $validator->rules('JSON', false),
                ],
            ],
        ]);
    }

    public function postWritePost(Request $request, Response $response, $args)
    {
        $ms          = $this->ci->alerts;
        $params      = $request->getParsedBody();

        $schema      = new RequestSchema('schema://create_post.yaml');
        $transformer = new RequestDataTransformer($schema);
        $form        = $transformer->transform($params);
        $validator   = new ServerSideValidator($schema, $this->ci->translator);

        if ( ! $validator->validate($form)) {
            $ms->addValidationErrors($validator);

            return $response->withStatus(400);
        }

        $id      = $this->ci->session['account.current_user_id'];
        $blog_id = $this->ci->blog->getBlogById($id)->getAttribute('blog_id');
        $this->ci->blog->createBlogPost($blog_id, $form['post_title'], $form['post_text']);
        $ms->addMessage('success', 'Blog post has been created');
    }

    public function getPost(Request $request, Response $response, $args)
    {
        $post     = $this->ci->blog->getPostById($args['post_id']);
        $comments = $this->ci->blog->getCommentsByPostId($args['post_id']);
        $this->ci->view->render($response, 'pages/post.html.twig', [
            'post'     => $post,
            'comments' => $comments,
        ]);
    }

    public function getMyBlog(Request $request, Response $response, $args)
    {
        $authorizer  = $this->ci->authorizer;
        $currentUser = $this->ci->currentUser;

        if ($authorizer->checkAccess($currentUser, 'create_blog', ['user' => $currentUser])) {
            return $response->withRedirect('/blogs/create');
        }

        $blog = $this->ci->blog->getBlogById($currentUser->id);
        return $this->ci->view->render($response, 'pages/blog.html.twig', [
            'blog' => $blog,
        ]);
    }

    public function getHome(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        $blog_posts  = $this->ci->blog->getFollowedBlogs($currentUser->id);

        return $this->ci->view->render($response, 'pages/home.html.twig', [
            'posts' => $blog_posts,
        ]);
    }

    public function postFollow(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        $blog_name   = $args['blog_name'];
        $blog        = $this->ci->blog->getBlogByName($blog_name);
        $authorizer  = $this->ci->authorizer;

        if ( ! $authorizer->checkAccess($currentUser, 'follow_blog', [
            'blog' => $blog,
            'user' => $currentUser,
        ])) {
            throw new ForbiddenException();
        }

        $this->ci->blog->followBlog($blog, $currentUser->id);

        return $response->withRedirect("/blogs/b/$blog_name");
    }

    public function postUnfollow(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        $blog_name   = $args['blog_name'];
        $blog        = $this->ci->blog->getBlogByName($blog_name);
        $authorizer  = $this->ci->authorizer;

        if ( ! $authorizer->checkAccess($currentUser, 'unfollow_blog', [
            'blog' => $blog,
            'user' => $currentUser,
        ])) {
            throw new ForbiddenException();
        }

        $this->ci->blog->unfollowBlog($blog, $currentUser->id);

        return $response->withRedirect("/blogs/b/$blog_name");
    }

    public function postComment(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        $post_id     = $args['post_id'];
        $form        = $request->getParsedBody();
        $this->ci->blog->createComment($post_id, $currentUser->id, $form['comment_text']);

        return $response->withRedirect("/posts/p/$post_id");
    }

    public function postCommentReply(Request $request, Response $response, $args)
    {
        $currentUser = $this->ci->currentUser;
        $post_id     = $args['post_id'];
        $comment_id  = $args['comment_id'];
        $form        = $request->getParsedBody();
        $this->ci->blog->createCommentReply($post_id, $currentUser->id, $form['comment_text'], $comment_id);

        return $response->withRedirect("/posts/p/$post_id");
    }

    public function getEditPost(Request $request, Response $response, $args)
    {
        //TODO: Check if request is send by writer of the blog post
        $schema      = new RequestSchema('schema://create_post.yaml');
        $validator   = new JqueryValidationAdapter($schema, $this->ci->translator);
        $post = $this->ci->blog->getPostById($args['post_id']);
        return $this->ci->view->render($response, 'pages/editpost.html.twig', [
            'post' => $post,
            'page' => [
                'validators' => [
                    'createpost' => $validator->rules('JSON', false)
                ]
            ]
        ]);
    }

    public function postEditPost(Request $request, Response $response, $args)
    {
        //TODO: Validate User is post owner
        $ms          = $this->ci->alerts;
        $params      = $request->getParsedBody();

        $schema      = new RequestSchema('schema://create_post.yaml');
        $transformer = new RequestDataTransformer($schema);
        $form        = $transformer->transform($params);
        $validator   = new ServerSideValidator($schema, $this->ci->translator);

        if ( ! $validator->validate($form)) {
            $ms->addValidationErrors($validator);

            return $response->withStatus(400);
        }

        $currentUser = $this->ci->currentUser;
        $post_id = $args['post_id'];
        $post = $this->ci->blog->getPostById($post_id);
        $this->ci->blog->editPost($post, $form['post_title'], $form['post_text']);
        return $response->withRedirect("/posts/p/$post_id");
    }
}
