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

        if ($currentUser == null) {
            throw new ForbiddenException();
        }

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

        if ($currentUser == null) {
            throw new ForbiddenException();
        }

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
        $currentUser = $this->ci->currentUser;
        if ($currentUser == null) {
            throw new ForbiddenException();
        }

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
        $currentUser = $this->ci->currentUser;
        if ($currentUser == null) {
            throw new ForbiddenException();
        }
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
        $this->ci->post->createBlogPost($blog_id, $form['post_title'], $form['post_text']);
    }

    public function getPost(Request $request, Response $response, $args)
    {
        $post = $this->ci->post->getPostById($args['post_id']);
        var_dump($post);
        $this->ci->view->render($response, 'pages/post.html.twig', [
            'post' => $post,
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

}