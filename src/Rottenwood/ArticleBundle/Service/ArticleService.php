<?php
/**
 * Author: Rottenwood
 * Date Created: 15.09.14 14:06
 */

namespace Rottenwood\ArticleBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\ArticleBundle\Entity\Article;

class ArticleService {
    private $em;
    private $articleRepository;
    private $authorRepository;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->articleRepository = $em->getRepository('RottenwoodArticleBundle:Article');
        $this->authorRepository = $em->getRepository('RottenwoodArticleBundle:Author');
    }

    public function getArticles() {
        $articles = $this->articleRepository->findAll();

        return $articles;
    }

    public function getOneArticle($articleId) {
        $article = $this->articleRepository->find($articleId);

        return $article;
    }

    public function getAuthors() {
        $authors = $this->authorRepository->findAll();

        return $authors;
    }

    public function getArticlesByAuthor($author) {
        $articles = $this->articleRepository->findArticlesByAuthor($author);

        return $articles;
    }

}