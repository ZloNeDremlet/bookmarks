<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Bookmark;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BookmarkRepository.
 */
class BookmarkRepository extends EntityRepository
{
    /**
     * @param string $url
     * @throws NonUniqueResultException
     * @return mixed
     */
    public function findByUrl(string $url) : ?Bookmark
    {
        return $this->createQueryBuilder('b')
            ->where('b.url = :url')
            ->setParameter('url', $url)
            ->getQuery()
            ->getOneOrNullResult();
    }
}