<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Security\Core\User\UserInterface as UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use MyFuckinJob\SiteBundle\Entity\DemandeurHoraires;
use MyFuckinJob\SiteBundle\Entity\Skill;


/**
 *  MyFuckinJob\SiteBundle\Entity\Demandeur
 * @ORM\Table(name="recruteur_favoris")
 * @ORM\Entity
 */
class Favoris  {

    public function __construct() {

    }

    /* ***********************************************  OTHERS  *************************************************** */


    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Demandeur",  inversedBy="favoris")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     * })
     */
    private $demandeur;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Recruteur",  inversedBy="favoris")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recruteur_id", referencedColumnName="id")
     * })
     */
    private $recruteur;


    /**
     * @var datetime
     * @ORM\Column(name="date_created", type="text", nullable=true)
     */
    public $dateCreated;


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
     * Set dateCreated
     *
     * @param string $dateCreated
     * @return Favoris
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return string 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return Favoris
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
     * Set recruteur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur
     * @return Favoris
     */
    public function setRecruteur(\MyFuckinJob\SiteBundle\Entity\Recruteur $recruteur = null)
    {
        $this->recruteur = $recruteur;
    
        return $this;
    }

    /**
     * Get recruteur
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Recruteur 
     */
    public function getRecruteur()
    {
        return $this->recruteur;
    }
}