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

    public function getArticlesByAuthorAction(Request $request) {
        $articleArray = array();
        $author = $request->request->get('author');

        $articles = $this->get('article')->getArticlesByAuthor($author);

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
        }

        return new JsonResponse($articleArray);
    }
}
