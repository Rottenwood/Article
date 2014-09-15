<?php

namespace Rottenwood\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Основной контроллер
 * @package Rottenwood\ArticleBundle\Controller
 */
class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('RottenwoodArticleBundle:Default:index.html.twig');
    }

    public function articleAction($articleId) {
        $data = array();
        $data['article']['id'] = $articleId;

        return $this->render('RottenwoodArticleBundle:Default:article.html.twig', $data);
    }
}
