<?php

namespace Rottenwood\ArticleBundle\Controller;

use Rottenwood\ArticleBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Основной контроллер
 * @package Rottenwood\ArticleBundle\Controller
 */
class DefaultController extends Controller {

    /**
     * Главная страница приложения
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $data = array();
        $articleService = $this->get('article');

        $articles = $articleService->getArticles();
        $authors = $articleService->getAuthors();

        $data['articles'] = $articles;
        $data['authors'] = $authors;

        return $this->render('RottenwoodArticleBundle:Default:index.html.twig', $data);
    }

    /**
     * Страница со статьей
     * @param $articleId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleAction($articleId) {
        $data = array();
        $articleService = $this->get('article');

        $article = $articleService->getOneArticle($articleId);
        $data['article'] = $article;

        return $this->render('RottenwoodArticleBundle:Default:article.html.twig', $data);
    }

    /**
     * Техническая страница со статьей для асинхронного запроса
     * @param $articleId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleTextAction($articleId) {
        $data = array();
        $articleService = $this->get('article');

        $article = $articleService->getOneArticle($articleId);
        $data['title'] = $article->getTitle();
        $data['context'] = $article->getContent();
        $data['date'] = $article->getDate()->format('d.m.Y');
        $data['rating'] = $article->getRating();

        $articleAuthors = $article->getAuthors();
        foreach ($articleAuthors as $articleAuthor) {
            /** @var Author $articleAuthor */
            $data['authors'][] = $articleAuthor->getName();
        }

        return $this->render('RottenwoodArticleBundle:Default:text.html.twig', $data);
    }
}
