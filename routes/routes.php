<?php

global $app;
$config = $app->getContainer()->get('config');

$app->get('/members', 'UserFrosting\Sprinkle\Blogmare\Controller\PageController:pageMembers')
    ->add('authGuard');

$app->get('/about', 'UserFrosting\Sprinkle\Blogmare\Controller\PageController:pageAbout');

$app->get('/', 'UserFrosting\Sprinkle\Blogmare\Controller\PageController:pageIndex')
    ->add('checkEnvironment')
    ->setName('index');

$app->get('/blogs', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:pageList');

$app->get('/blogs/my_blog', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getMyBlog');

$app->get('/blogs/create',
    'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getCreateBlog')->setName('create_blog');
$app->post('/blogs/create', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postCreateBlog');

$app->get('/blogs/b/{blog_name}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getBlog');

$app->get('/posts/write', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getWritePost');
$app->post('/posts/write', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:postWritePost');

$app->get('/posts/p/{post_id}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getPost');
$app->get('/posts/p/{post_id}/{post_name}', 'UserFrosting\Sprinkle\Blogmare\Controller\BlogController:getPost');