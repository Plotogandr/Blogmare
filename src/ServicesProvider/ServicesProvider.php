<?php

namespace UserFrosting\Sprinkle\Blogmare\ServicesProvider;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use UserFrosting\Sprinkle\Blogmare\Blog\BlogPostService;
use UserFrosting\Sprinkle\Blogmare\Blog\BlogService;
use UserFrosting\Sprinkle\Core\Facades\Debug;

class ServicesProvider
{
    public function register($container)
    {
        $container['blog'] = function ($c) {
            return new BlogService();
        };

        $container['redirect.onLogin'] = function ($c) {

            return function (Request $request, Response $response, array $args) use ($c) {
                // Backwards compatibility for the deprecated determineRedirectOnLogin service
                if ($c->has('determineRedirectOnLogin')) {
                    $determineRedirectOnLogin = $c->determineRedirectOnLogin;

                    return $determineRedirectOnLogin($response)->withStatus(200);
                }

                $authorizer = $c->authorizer;

                $currentUser = $c->authenticator->user();

                if ($authorizer->checkAccess($currentUser, 'uri_dashboard')) {
                    return $response->withHeader('UF-Redirect', $c->router->pathFor('dashboard'));
                } elseif ($authorizer->checkAccess($currentUser, 'create_blog', ['user' => $currentUser])) {
                    return $response->withHeader('UF-Redirect', $c->router->pathFor('create_blog'));
                } elseif ($authorizer->checkAccess($currentUser, 'uri_account_settings')) {
                    return $response->withHeader('UF-Redirect', $c->router->pathFor('home'));
                } else {
                    return $response->withHeader('UF-Redirect', $c->router->pathFor('index'));
                }
            };
        };

        $container->extend('authorizer', function ($authorizer, $c) {
            $authorizer->addCallback('no_blog', function ($id) use ($c) {
                $blog = $c->blog->getBlogById($id);
                return $blog == null;
            });
            $authorizer->addCallback('not_following', function ($blog, $id) use ($c) {
                if ($blog->id == $id) {
                    return false;
                }
                foreach ($blog->followed as $follower) {
                    if ($follower->id == $id) {
                        return false;
                    }
                }

                return true;
            });
            $authorizer->addCallback('following', function ($blog, $id) use ($c) {
                foreach ($blog->followed as $follower) {
                    if ($follower->id == $id) {
                        return true;
                    }
                }

                return false;
            });
            return $authorizer;
        });
    }
}
