<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MyFuckinJob\SiteBundle\Entity\Demandeur;

/**
 * @ORM\Table(name="demandeur_newsletter")
 * @ORM\Entity
 */
class DemandeurNewsletter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     *
     * @ORM\OneToOne(targetEntity="Demandeur",inversedBy="newsletter")
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     */
    protected $demandeur;


    /**
     * @var \Datestring
     * @ORM\Column(name="badge", type="boolean", nullable=false)
     */
    protected $badge;

    /**
     * @var \Datestring
     * @ORM\Column(name="question", type="boolean", nullable=false)
     */
    protected $question;

    /**
     * @var \Datestring
     * @ORM\Column(name="proposition", type="boolean", nullable=false)
     */
    protected $proposition;

    /**
     * @var \Datestring
     * @ORM\Column(name="demandes", type="boolean", nullable=false)
     */
    protected $demandes;

    /**
     * @var \Datestring
     * @ORM\Column(name="questions", type="boolean", nullable=false)
     */
    protected $questions;

    /**
     * @var \Datestring
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $dateCreated;


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
     * Set badge
     *
     * @param boolean $badge
     * @return DemandeurNewsletter
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
    
        return $this;
    }

    /**
     * Get badge
     *
     * @return boolean 
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set question
     *
     * @param boolean $question
     * @return DemandeurNewsletter
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return boolean 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set proposition
     *
     * @param boolean $proposition
     * @return DemandeurNewsletter
     */
    public function setProposition($proposition)
    {
        $this->proposition = $proposition;
    
        return $this;
    }

    /**
     * Get proposition
     *
     * @return boolean 
     */
    public function getProposition()
    {
        return $this->proposition;
    }

    /**
     * Set demandes
     *
     * @param boolean $demandes
     * @return DemandeurNewsletter
     */
    public function setDemandes($demandes)
    {
        $this->demandes = $demandes;
    
        return $this;
    }

    /**
     * Get demandes
     *
     * @return boolean 
     */
    public function getDemandes()
    {
        return $this->demandes;
    }

    /**
     * Set questions
     *
     * @param boolean $questions
     * @return DemandeurNewsletter
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    
        return $this;
    }

    /**
     * Get questions
     *
     * @return boolean 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return DemandeurNewsletter
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
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return DemandeurNewsletter
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