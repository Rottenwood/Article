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

        $articleService = $this->get('article');
        $article = $articleService->getOneArticle($articleId);
        $data['article'] = $article;
//        $data['article']['id'] = $articleId;

//        var_dump($article);

        return $this->render('RottenwoodArticleBundle:Default:article.html.twig', $data);
    }
}
