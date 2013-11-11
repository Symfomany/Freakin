<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyFuckinJob\SiteBundle\Entity\Skill
 *
 * @ORM\Table(name="i18n_language_codes")
 * @ORM\Entity
 */
class Languages {

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
     * @ORM\Column(name="english_name", type="string", length=200, nullable=false)
     */
    protected $englishName;

    
    /**
     * @var string $name
     * @ORM\Column(name="french_name", type="string", length=200, nullable=false)
     */
    protected $frenchName;


    /**
     * @ORM\ManyToMany(targetEntity="Demandeur", inversedBy="langues")
     */
    private $demandeurs;

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
     * Set englishName
     *
     * @param string $englishName
     * @return Languages
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;
    
        return $this;
    }

    /**
     * Get englishName
     *
     * @return string 
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }

    /**
     * Set frenchName
     *
     * @param string $frenchName
     * @return Languages
     */
    public function setFrenchName($frenchName)
    {
        $this->frenchName = $frenchName;
    
        return $this;
    }

    /**
     * Get frenchName
     *
     * @return string 
     */
    public function getFrenchName()
    {
        return $this->frenchName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->demandeurs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add demandeurs
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeurs
     * @return Languages
     */
    public function addDemandeur(\MyFuckinJob\SiteBundle\Entity\Demandeur $demandeurs)
    {
        $this->demandeurs[] = $demandeurs;
    
        return $this;
    }

    /**
     * Remove demandeurs
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Demandeur $demandeurs
     */
    public function removeDemandeur(\MyFuckinJob\SiteBundle\Entity\Demandeur $demandeurs)
    {
        $this->demandeurs->removeElement($demandeurs);
    }

    /**
     * Get demandeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDemandeurs()
    {
        return $this->demandeurs;
    }
}