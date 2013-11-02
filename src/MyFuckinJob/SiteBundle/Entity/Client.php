<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MyFuckinJob\SiteBundle\Entity\Recruteur;

/**
 * MyFuckinJob\SiteBundle\Entity\Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity()
 */
class Client {

    public function __construct()
    {
        $this->recruteur = new ArrayCollection();
    }
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
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Recruteur", mappedBy="client")
     */
    protected $recruteur;
    
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
     * Set name
     *
     * @param string $name
     * @return Client
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
     * Add recruteur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur
     * @return Client
     */
    public function addRecruteur(\MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur)
    {
        $this->recruteur[] = $recruteur;
    }

    /**
     * Remove recruteur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur
     */
    public function removeRecruteur(\MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur)
    {
        $this->recruteur->removeElement($recruteur);
    }

    /**
     * Get recruteur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecruteur()
    {
        return $this->recruteur;
    }
}