<?php
/**
 * Author: Rottenwood
 * Date Created: 15.09.14 14:06
 */

namespace Rottenwood\ArticleBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\ArticleBundle\Entity\Article;
use Rottenwood\ArticleBundle\Entity\Author;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Основной сервис приложения
 * @package Rottenwood\ArticleBundle\Service
 */
class ArticleService {

    private $em;
    private $articleRepository;
    private $authorRepository;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->articleRepository = $em->getRepository('RottenwoodArticleBundle:Article');
        $this->authorRepository = $em->getRepository('RottenwoodArticleBundle:Author');
    }

    /**
     * Запрос списка статей
     * @return array|\Rottenwood\ArticleBundle\Entity\Article[]|\Rottenwood\ArticleBundle\Entity\Author[]
     */
    public function getArticles() {
        $articles = $this->articleRepository->findAll();

        return $articles;
    }

    /**
     * Запрос статьи по идентификатору. Возвращает объект
     * @param $articleId
     * @return Article
     */
    public function getOneArticle($articleId) {
        $article = $this->articleRepository->find($articleId);

        return $article;
    }

    /**
     * Запрос списках авторов
     * @return array|\Rottenwood\ArticleBundle\Entity\Article[]|\Rottenwood\ArticleBundle\Entity\Author[]
     */
    public function getAuthors() {
        $authors = $this->authorRepository->findAll();

        return $authors;
    }

    /**
     * Запрос статей по автору
     * @param $author
     * @return array
     */
    public function getArticlesByAuthor($author) {
        $articles = $this->articleRepository->findArticlesByAuthor($author);

        return $articles;
    }

    /**
     * Создание статьи
     * @param $title
     * @param $text
     * @param $authors
     * @return Article
     */
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

    /**
     * Редактирование статьи
     * @param $articleId
     * @param $title
     * @param $text
     * @param $authors
     * @return Article
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function editArticle($articleId, $title, $text, $authors) {
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

        // Проверка статьи
        $article = $this->articleRepository->find($articleId);

        if (!$article) {
            throw new Exception("Статья $articleId не найдена.");
        }

        $article->setTitle($title);
        $article->setContent($text);
        $article->setDate(new \DateTime('now'));
        $article->setAuthors($authorArray);
        $this->em->persist($article);

        $this->em->flush();

        return $article;
    }

    /**
     * Удаление статьи
     * @param $articleId
     * @return bool
     */
    public function deleteArticle($articleId) {
        $article = $this->articleRepository->find($articleId);

        if ($article) {
            $this->em->remove($article);
            $this->em->flush();
        }

        return true;
    }

    /**
     * Запрос статьи по идентификатору, возвращает массив
     * @param $articleId
     * @return array
     */
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