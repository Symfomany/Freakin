<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyFuckinJob\SiteBundle\Entity\Skill
 *
 * @ORM\Table(name="skill")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\SkillRepository")
 */
class Skill {

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
     * @ORM\OneToMany(targetEntity="DemandeurSkill", mappedBy="skill", cascade={"all"}, orphanRemoval=true)
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
     * Constructor
     */
    public function __construct()
    {
        $this->demandeur = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\DemandeurSkill $demandeur
     * @return Skill
     */
    public function addDemandeur(\MyFuckinJob\SiteBundle\Entity\DemandeurSkill $demandeur)
    {
        $this->demandeur[] = $demandeur;
    
        return $this;
    }

    /**
     * Remove demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\DemandeurSkill $demandeur
     */
    public function removeDemandeur(\MyFuckinJob\SiteBundle\Entity\DemandeurSkill $demandeur)
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