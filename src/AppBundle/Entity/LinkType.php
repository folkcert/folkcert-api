<?php

namespace AppBundle\Entity;

use RestBundle\Entity\RestEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * LinkType
 *
 * @ORM\Table(name="link_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkTypeRepository")
 */
class LinkType extends RestEntity
{
    const LINK_TYPE_YOUTUBE = 1;
    const LINK_TYPE_VIMEO = 2;
    const LINK_TYPE_DAILYMOTION = 3;
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
     * @ORM\Column(name="name", type="string", length=32, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=32)
     */
    protected $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="baseUrl", type="string", length=100)
     */
    protected $baseUrl;

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
     * Set name
     *
     * @param string $name
     *
     * @return LinkType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return LinkType
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set baseUrl
     *
     * @param string $baseUrl
     *
     * @return LinkType
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get baseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}

