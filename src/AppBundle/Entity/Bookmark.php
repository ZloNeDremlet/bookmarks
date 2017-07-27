<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\BookmarkRepository")
 * @ORM\Table(name="bookmark")
 */
class Bookmark
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1024, unique=true)
     */
    private $url;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="bookmark")
     */
    private $comments;

    /**
     * Bookmark constructor.
     */
    public function __construct(){
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) : void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt) : void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) : void
    {
        $this->url = $url;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments() : ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments($comments) : void
    {
        $this->comments = $comments;
    }
}