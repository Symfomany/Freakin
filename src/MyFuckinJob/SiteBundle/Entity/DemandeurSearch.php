<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="demandeur_search")
 * @ORM\Entity
 */
class DemandeurSearch {

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
     * @ORM\Column(name="word", type="string", length=200, nullable=false)
     */
    protected $word;

    /**
     * @var string $name
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $dateCreated;

    /**
     * @var string $name
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Demandeur",  inversedBy="experiences")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     * })
     */
    private $demandeur;



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
     * Set word
     *
     * @param string $word
     * @return DemandeurSearch
     */
    public function setWord($word)
    {
        $this->word = $word;
    
        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return DemandeurSearch
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

    /**
     * Set description
     *
     * @param string $description
     * @return DemandeurSearch
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
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return DemandeurSearch
     */
    public function setDemandeur(\MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur = null)
    {
        $this->demandeur = $demandeur;
    
        return $this;
    }

    /**
     * Get demandeur
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Demandeur 
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }
}