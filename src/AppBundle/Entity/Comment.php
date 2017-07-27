<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @ORM\Column(name="created_at", type="datetime", options={"default" = "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_text", type="text")
     */
    private $text;

    /**
     * @var Bookmark
     *
     * @ORM\ManyToOne(targetEntity="Bookmark", inversedBy="comments")
     * @ORM\JoinColumn(name="bookmark_id", referencedColumnName="id")
     */
    private $bookmark;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress) : void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text) : void
    {
        $this->text = $text;
    }

    /**
     * @return Bookmark
     */
    public function getBookmark(): Bookmark
    {
        return $this->bookmark;
    }

    /**
     * @param Bookmark $bookmark
     */
    public function setBookmark(Bookmark $bookmark)
    {
        $this->bookmark = $bookmark;
    }

}