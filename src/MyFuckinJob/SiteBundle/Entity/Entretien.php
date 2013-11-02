<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyFuckinJob\SiteBundle\Entity\Skill
 * @ORM\Table(name="entretien")
 * @ORM\Entity
 */
class Entretien
{
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
     * @ORM\Column(name="date_begin", type="datetime")
     */
    protected $dateBegin;

    /**
     * @var string $name
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $dateCreated;


    /**
     * @var string $name
     * @ORM\Column(name="commentaire", type="text", nullable=false)
     */
    protected $commentaire;


    /**
     * @var string $name
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    protected $state;


    /**
     *
     * @ORM\ManyToOne(targetEntity="Demandeur",  inversedBy="entretiens")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
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
     * Set dateBegin
     *
     * @param \DateTime $dateBegin
     * @return Entretien
     */
    public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
    
        return $this;
    }

    /**
     * Get dateBegin
     *
     * @return \DateTime 
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Entretien
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return Entretien
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return Entretien
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

    /**
     * Set state
     *
     * @param boolean $state
     * @return Entretien
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }
}