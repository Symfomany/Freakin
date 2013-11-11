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
 *  MyFuckinJob\SiteBundle\Entity\Remises
 *
 * @ORM\Table(name="remise")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\RemiseRepository")
 */
class Remises   {

    public function __construct() {
        $this->dateCreated = new \DateTime('now');
        $this->status = 1;
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
     * @var string $status
     * @ORM\Column(name="status", type="integer",  nullable=false)
     */
    protected $status;


    /**
     * @var string $nature
     * @ORM\Column(name="nature", type="integer")
     */
    protected $nature;

    /**
     * @var string $montant
     * @ORM\Column(name="montant", type="float")
     */
    protected $montant;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Reservations",inversedBy="remise")
     * @ORM\JoinColumn(name="reservation_id", referencedColumnName="id")
     */
    protected $reservation;


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
     * Set nature
     *
     * @param integer $nature
     * @return Remises
     */
    public function setNature($nature)
    {
        $this->nature = $nature;
    
        return $this;
    }

    /**
     * Get nature
     *
     * @return integer 
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set montant
     *
     * @param float $montant
     * @return Remises
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return float 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set reservation
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Reservations $reservation
     * @return Remises
     */
    public function setReservation(\MyFuckinJob\SiteBundle\Entity\Reservations $reservation = null)
    {
        $this->reservation = $reservation;
    
        return $this;
    }

    /**
     * Get reservation
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Reservations 
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}