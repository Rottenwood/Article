<?php

namespace Rottenwood\ArticleBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий DQL-запросов
 */
class ArticleRepository extends EntityRepository {

    public function findArticlesByAuthor($author) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM RottenwoodArticleBundle:Article a JOIN a.authors u WHERE u.id = :author');
        $query->setParameter('author', $author);
        $result = $query->getResult();

        return $result;
    }
}
