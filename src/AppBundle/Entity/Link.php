<?php

namespace AppBundle\Entity;

use RestBundle\Entity\RestEntity;
use Doctrine\ORM\Mapping as ORM;
use RestBundle\Annotations\RestAnnotation;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkRepository")
 */
class Link extends RestEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="linkCode", type="string", length=100)
     */
    protected $linkCode;

    /**
     * @var string
     *
      * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    protected $thumbnail;

    /**
     * @var Concert
     *
     * @ORM\ManyToOne(targetEntity="Concert")
     * @ORM\JoinColumn(name="concert_id", referencedColumnName="id")
     */
    protected $concert;

    /**
     * @var LinkType
     *
     * @ORM\ManyToOne(targetEntity="LinkType")
     * @ORM\JoinColumn(name="link_type_id", referencedColumnName="id")
     */
    protected $linkType;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set linkCode
     *
     * @param string $linkCode
     *
     * @return Link
     */
    public function setLinkCode($linkCode)
    {
        $this->linkCode = $linkCode;

        return $this;
    }

    /**
     * Get linkCode
     *
     * @return string
     */
    public function getLinkCode()
    {
        return $this->linkCode;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Link
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set concert
     *
     * @param Concert $concert
     *
     * @return Link
     */
    public function setConcert($concert)
    {
        $this->concert = $concert;

        return $this;
    }

    /**
     * Get concert
     *
     * @RestAnnotation("ignoreSerializer")
     *
     * @return Concert
     */
    public function getConcert()
    {
        return $this->concert;
    }

    /**
     * Set linkType
     *
     * @param LinkType $linkType
     *
     * @return Link
     */
    public function setLinkType($linkType)
    {
        $this->linkType = $linkType;

        return $this;
    }

    /**
     * Get linkType
     *
     * @return LinkType
     */
    public function getLinkType()
    {
        return $this->linkType;
    }
}

