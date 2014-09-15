<?php

namespace Rottenwood\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RottenwoodArticleBundle:Default:index.html.twig');
    }
}
