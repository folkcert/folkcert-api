<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RestBundle\Annotations\RestAnnotation;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkRepository")
 */
class Link
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="linkCode", type="string", length=100)
     */
    private $linkCode;

    /**
     * @var Concert
     *
     * @ORM\ManyToOne(targetEntity="Concert")
     * @ORM\JoinColumn(name="concert_id", referencedColumnName="id")
     */
    private $concert;

    /**
     * @var LinkType
     *
     * @ORM\ManyToOne(targetEntity="LinkType")
     * @ORM\JoinColumn(name="link_type_id", referencedColumnName="id")
     */
    private $linkType;

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

