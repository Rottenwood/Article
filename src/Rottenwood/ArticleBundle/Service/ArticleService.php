<?php
/**
 * Author: Rottenwood
 * Date Created: 15.09.14 14:06
 */

namespace Rottenwood\ArticleBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\ArticleBundle\Entity\Article;
use Rottenwood\ArticleBundle\Entity\Author;

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

    public function createArticle($title, $text, $authors) {
        $authorArray = array();

        // Проверка и создание авторов
        foreach ($authors as $authorName) {
            $author = $this->authorRepository->findAuthorByName($authorName);

            if (!$author) {
                $author = new Author();
                $author->setName($authorName);
                $this->em->persist($author);
                $result['exist'] = false;
            }

            $authorArray[] = $author;

        }

        // Создание статьи
        $article = new Article();
        $article->setTitle($title);
        $article->setContent($text);
        $article->setDate(new \DateTime('now'));
        $article->setAuthors($authorArray);
        $this->em->persist($article);

        $this->em->flush();

        return $article;
    }

    public function deleteArticle($articleId) {
        $article = $this->articleRepository->find($articleId);

        if ($article) {
            $this->em->remove($article);
            $this->em->flush();
        }

        return true;
    }

    public function getArticleById($articleId) {
        $result = array();
        $article = $this->articleRepository->find($articleId);

        if ($article) {
            $result['title'] = $article->getTitle();
            $result['text'] = $article->getContent();
            $authors = $article->getAuthors();

            foreach ($authors as $author) {
                /** @var Author $author */
                $result['authors'][$author->getId()] = $author->getName();
            }
        }

        return $result;
    }

}