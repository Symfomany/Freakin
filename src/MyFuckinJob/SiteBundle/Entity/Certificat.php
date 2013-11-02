<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MyFuckinJob\SiteBundle\Entity\Recruteur;

/**
 * MyFuckinJob\SiteBundle\Entity\Client
 * @ORM\Table(name="certificat")
 * @ORM\Entity()
 */
class Certificat {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    protected $title;

    /**
     * @var string $name
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    protected $description;

    /**
     * @var string $name
     * @ORM\Column(name="date_created", type="datetime", length=200, nullable=false)
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Demandeur", inversedBy="certificates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     * })
     */
    protected $demandeur;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Certificat
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Certificat
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Certificat
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}