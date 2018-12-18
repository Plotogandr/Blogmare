<?php

namespace UserFrosting\Sprinkle\Blogmare\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;

class PageController extends SimpleController
{
    public function pageMembers($request, $response, $args)
    {
        return $this->ci->view->render($response, 'pages/members.html.twig');
    }

    public function pageAbout($request, $response, $args)
    {
        return $this->ci->view->render($response, 'pages/about.html.twig');
    }

    public function pageIndex($request, $response, $args)
    {
        return $this->ci->view->render($response, 'pages/index.html.twig');
    }
}