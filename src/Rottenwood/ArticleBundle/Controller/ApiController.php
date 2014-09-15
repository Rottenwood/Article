<?php

namespace Rottenwood\ArticleBundle\Controller;

use Rottenwood\ArticleBundle\Entity\Article;
use Rottenwood\ArticleBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API контроллер
 * @package Rottenwood\ArticleBundle\Controller
 */
class ApiController extends Controller {

    public function getArticlesAction(Request $request) {
        $articleArray = array();
        $author = $request->request->get('author');

        if ($author == 0) {
            $articles = $this->get('article')->getArticles();
        } else {
            $articles = $this->get('article')->getArticlesByAuthor($author);
        }

        foreach ($articles as $article) {
            /** @var Article $article */
            $articleArrayId = $article->getId();
            $articleArray[$articleArrayId]['title'] = $article->getTitle();
            $articleArray[$articleArrayId]['date'] = $article->getDate()->format('d.m.Y');
            $articleArray[$articleArrayId]['rating'] = $article->getRating();

            $articleAuthors = $article->getAuthors();
            foreach ($articleAuthors as $articleAuthor) {
                /** @var Author $articleAuthor */
                $articleArray[$articleArrayId]['authors'][] = $articleAuthor->getName();
            }
            $articleArray[$articleArrayId]['authors'] = implode(',<br>', $articleArray[$articleArrayId]['authors']);
        }

        return new JsonResponse($articleArray);
    }

    public function addArticleAction(Request $request) {
        $title = $request->request->get('title');
        $text = $request->request->get('text');
        $authors = $request->request->get('author');

        $article = $this->get('article')->createArticle($title, $text, $authors);

        return new JsonResponse($article);
    }

    public function deleteArticleAction(Request $request) {
        $articleId = $request->request->get('articleId');

        $article = $this->get('article')->deleteArticle($articleId);

        return new JsonResponse($article);
    }

    public function getOneArticleAction(Request $request) {
        $articleId = $request->request->get('articleId');

        $article = $this->get('article')->getArticleById($articleId);

        return new JsonResponse($article);
    }
}
