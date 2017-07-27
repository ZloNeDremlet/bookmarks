<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

class CommentManager
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
     * @param Comment $comment
     * @return Comment
     *
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    public function create(Comment $comment) : Comment
    {
        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

}