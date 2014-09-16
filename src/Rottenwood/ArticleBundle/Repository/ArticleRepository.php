<?php

namespace Rottenwood\ArticleBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий DQL-запросов
 */
class ArticleRepository extends EntityRepository {

    /**
     * Поиск всех статей по автору
     * @param $author
     * @return array
     */
    public function findArticlesByAuthor($author) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM RottenwoodArticleBundle:Article a JOIN a.authors u WHERE u.id = :author');
        $query->setParameter('author', $author);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Поиск автора по имени
     * @param $name
     * @return mixed
     */
    public function findAuthorByName($name) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM RottenwoodArticleBundle:Author a WHERE a.name = :name');
        $query->setParameter('name', $name);
        $query->setMaxResults(1);
        $result = $query->getOneOrNullResult();

        return $result;
    }

    /**
     * Поиск статей по ключевому слову
     * @param $keyword
     * @return array
     */
    public function search($keyword) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM RottenwoodArticleBundle:Article a WHERE a.title LIKE :keyword OR a.content LIKE :keyword');
        $query->setParameter('keyword', "%$keyword%");
        $result = $query->getResult();

        return $result;
    }
}
