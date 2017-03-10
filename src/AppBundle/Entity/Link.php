<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}

