<?php

namespace AppBundle\Entity;

use RestBundle\Entity\RestEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artist
 *
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
 */
class Artist extends RestEntity
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
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="related_names", type="string", length=255, nullable=true)
     */
    protected $relatedNames;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=100)
     */
    protected $picture;

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
     * @return Artist
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Artist
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set relatedNames
     *
     * @param string $relatedNames
     *
     * @return Artist
     */
    public function setRelatedNames($relatedNames)
    {
        $this->relatedNames = $relatedNames;

        return $this;
    }

    /**
     * Get relatedNames
     *
     * @return string
     */
    public function getRelatedNames()
    {
        return $this->relatedNames;
    }
}
