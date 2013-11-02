<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyFuckinJob\SiteBundle\Entity\Skill
 * @ORM\Table(name="experience")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\ExperienceRepository")
 */
class Hobbies {

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



    public function __toString(){
        return $this->getTitle();
    }
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
     * @return Skill
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
     * @return Skill
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
     * @return Experience
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