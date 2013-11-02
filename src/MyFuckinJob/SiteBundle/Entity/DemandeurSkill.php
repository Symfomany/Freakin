<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MyFuckinJob\SiteBundle\Entity\Demandeur;
use MyFuckinJob\SiteBundle\Entity\Skill;

/**
 * MyFuckinJob\SiteBundle\Entity\DemandeurSkill
 *
 * @ORM\Table(name="demandeur_skill")
 * @ORM\Entity
 */
class DemandeurSkill {

    public function __construct() {
        $this->hasPrimary = 0;
    }

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Demandeur", inversedBy="skill")
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id", nullable=true)
     */
    protected $demandeur;

    /**
     * @ORM\ManyToOne(targetEntity="Skill", inversedBy="demandeur")
     * @ORM\JoinColumn(name="skill_id", referencedColumnName="id", nullable=true)
     */
    protected $skill;

    /**
     * @var $hasPrimary
     * @ORM\Column(name="has_primary", type="boolean", nullable=true)
     */
    protected $hasPrimary;

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
     * Set hasPrimary
     *
     * @param boolean $hasPrimary
     * @return DemandeurSkill
     */
    public function setHasPrimary($hasPrimary)
    {
        $this->hasPrimary = $hasPrimary;
    }

    /**
     * Get hasPrimary
     *
     * @return boolean 
     */
    public function getHasPrimary()
    {
        return $this->hasPrimary;
    }

    /**
     * Set demandeur
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeur
     * @return DemandeurSkill
     */
    public function setDemandeur(Demandeur $demandeur = null)
    {
        $this->demandeur = $demandeur;
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
     * Set skill
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Skill $skill
     * @return DemandeurSkill
     */
    public function setSkill(Skill $skill = null)
    {
        $this->skill = $skill;
    }

    /**
     * Get skill
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }
}