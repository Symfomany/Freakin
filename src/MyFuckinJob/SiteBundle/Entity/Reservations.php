<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Security\Core\User\UserInterface as UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Imagine\Gd\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;

use MyFuckinJob\SiteBundle\Entity\DemandeurHoraires;
use MyFuckinJob\SiteBundle\Entity\Skill;


/**
 *  MyFuckinJob\SiteBundle\Entity\Demandeur
 *
 * @ORM\Table(name="demandeur_seminaires")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\ReservationsRepository")
 */
class Reservations   {

    public function __construct() {
        $this->dateCreated = new \DateTime('now');
        $this->status = 0;
    }

    /* ***********************************************  OTHERS  *************************************************** */

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string $dateEnd
     * @ORM\Column(name="date_created", type="datetime",  nullable=false)
     */
    protected $dateCreated;


    /**
     * @var string $module
     * @ORM\Column(name="module", type="integer")
     */
    protected $module;

    /**
     * @var string $dateEnd
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;


    /**
     *
     * @ORM\ManyToOne(targetEntity="Demandeur",inversedBy="seminaire")
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     */
    protected $demandeur;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Seminaire",inversedBy="demandeur")
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     */
    protected $seminaire;


    /**
     * @ORM\OneToMany(targetEntity="Remises",mappedBy="reservation")
     */
    protected $remise;



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
     * @param \DateTime $dateCreated
     * @return Reservations
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
     * Set status
     *
     * @param integer $status
     * @return Reservations
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return Reservations
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
     * Set seminaire
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Seminaire $seminaire
     * @return Reservations
     */
    public function setSeminaire(\MyFuckinJob\SiteBundle\Entity\Seminaire $seminaire = null)
    {
        $this->seminaire = $seminaire;
    
        return $this;
    }

    /**
     * Get seminaire
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Seminaire 
     */
    public function getSeminaire()
    {
        return $this->seminaire;
    }

    /**
     * Add remise
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Remises $remise
     * @return Reservations
     */
    public function addRemise(\MyFuckinJob\SiteBundle\Entity\Remises $remise)
    {
        $this->remise[] = $remise;
    
        return $this;
    }

    /**
     * Remove remise
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Remises $remise
     */
    public function removeRemise(\MyFuckinJob\SiteBundle\Entity\Remises $remise)
    {
        $this->remise->removeElement($remise);
    }

    /**
     * Get remise
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set module
     *
     * @param integer $module
     * @return Reservations
     */
    public function setModule($module)
    {
        $this->module = $module;
    
        return $this;
    }

    /**
     * Get module
     *
     * @return integer 
     */
    public function getModule()
    {
        return $this->module;
    }
}