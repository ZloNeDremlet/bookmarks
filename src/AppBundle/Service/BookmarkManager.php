<?php

namespace AppBundle\Service;


use AppBundle\Entity\Bookmark;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

/**
 * Class BookmarkManager
 * @package AppBundle\Service
 */
class BookmarkManager
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getLastBookmarks(?int $limit = 10): array
    {
        return $this->em->getRepository(Bookmark::class)->findBy([],['createdAt' => 'DESC'], $limit);
    }

    /**
     * @param Bookmark $bookmark
     * @return Bookmark
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function create(Bookmark $bookmark)
    {
        $bookmarkOld = $this->em->getRepository(Bookmark::class)->findOneBy(['url' => $bookmark->getUrl()]);
        if(!$bookmarkOld){
            $this->em->persist($bookmark);
            $this->em->flush();

            return $bookmark;
        }
        return $bookmarkOld->getId();
    }

}