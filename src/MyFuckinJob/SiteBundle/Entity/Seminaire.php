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
 * @ORM\Table(name="seminaires")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\SeminaireRepository")
 */
class Seminaire   {

    public function __construct() {
        $this->dateCreated = new \DateTime('now');
        $this->nature = 1;
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
     * @var string $firstname
     *
     * @ORM\Column(name="title", type="string", length=32)
     * @Assert\NotBlank(message = "Votre prénom ne peut être vite")
     * @Assert\Length(
     *      min = "2",
     *      max = "32",
     *      minMessage = "Votre prénom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Votre prénom doit faire au maximum {{ limit }} caractères",
     *      groups={"suscribe1", "suscribe2"}
     *      )
     */
    protected $title;


    /**
     * @var string $lastname
     * @ORM\Column(name="description", type="string", length=32, nullable=false)
     * @Assert\NotBlank(message = "Votre nom ne peut être vite")
     * @Assert\Length(
     *      min = "2",
     *      max = "32",
     *      minMessage = "Votre nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Votre nom doit faire au maximum {{ limit }} caractères",
     *      groups={"suscribe1", "suscribe2"}
     *      )
     */
    protected $description;


    /**
     * @var string $dateBegin
     * @ORM\Column(name="date_begin", type="datetime",  nullable=false)
     */
    protected $dateBegin;

    /**
     * @var string $dateEnd
     * @ORM\Column(name="date_end", type="datetime",  nullable=false)
     */
    protected $dateEnd;

    /**
     * @var string $dateEnd
     * @ORM\Column(name="date_created", type="datetime",  nullable=false)
     */
    protected $dateCreated;


    /**
     * @var string $dateEnd
     * @ORM\Column(name="nature", type="integer")
     */
    protected $nature;

    /**
     *
     * @ORM\OneToMany(targetEntity="Reservations",mappedBy="seminaire")
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
     * @return Seminaire
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
     * @return Seminaire
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
     * Set dateBegin
     *
     * @param \DateTime $dateBegin
     * @return Seminaire
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
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return Seminaire
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Seminaire
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
     * Set nature
     *
     * @param integer $nature
     * @return Seminaire
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
     * Add demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Reservations $demandeur
     * @return Seminaire
     */
    public function addDemandeur(\MyFuckinJob\SiteBundle\Entity\Reservations $demandeur)
    {
        $this->demandeur[] = $demandeur;
    
        return $this;
    }

    /**
     * Remove demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Reservations $demandeur
     */
    public function removeDemandeur(\MyFuckinJob\SiteBundle\Entity\Reservations $demandeur)
    {
        $this->demandeur->removeElement($demandeur);
    }

    /**
     * Get demandeur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }
}