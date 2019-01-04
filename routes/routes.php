<?php

global $app;
$config = $app->getContainer()->get('config');

$app->get('/', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getIndex')
    ->add('checkEnvironment')
    ->setName('index');

$app->get('/blogs', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:pageList');

$app->get('/blogs/my_blog', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getMyBlog')->add('authGuard');

$app->get('/blogs/create',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getCreateBlog')->setName('create_blog')->add('authGuard');
$app->post('/blogs/create',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postCreateBlog')->add('authGuard');

$app->get('/blogs/b/{blog_name}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getBlog');

$app->get('/posts/write', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getWritePost')->add('authGuard');
$app->post('/posts/write', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postWritePost')->add('authGuard');

$app->get('/posts/p/{post_id}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getPost');
$app->get('/posts/p/{post_id}/{post_name}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getPost');

$app->get('/home',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getHome')->add('authGuard')->setName('home');

$app->post('/blogs/b/{blog_name}/follow',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postFollow')->add('authGuard');
$app->post('/blogs/b/{blog_name}/unfollow',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postUnfollow')->add('authGuard');

$app->post('/posts/p/{post_id}/comment', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postComment');
$app->post('/posts/p/{post_id}/comment/{comment_id}',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postCommentReply');

$app->get('/posts/edit/{post_id}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getEditPost');
$app->post('/posts/edit/{post_id}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postEditPost');

$app->post('/posts/p/{post_id}/comment/edit/{comment_id}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postEditComment');
