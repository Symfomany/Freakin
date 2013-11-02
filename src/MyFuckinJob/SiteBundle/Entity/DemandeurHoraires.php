<?php

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MyFuckinJob\SiteBundle\Entity\Demandeur;

/**
 * MyFuckinJob\SiteBundle\Entity\DemandeurHoraires
 *
 * @ORM\Table(name="demandeur_horaires")
 * @ORM\Entity
 */
class DemandeurHoraires
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
     * @ORM\OneToOne(targetEntity="Demandeur",inversedBy="horaires")
     * @ORM\JoinColumn(name="demandeur_id", referencedColumnName="id")
     */
    protected $demandeur;

    /**
     * @var $j1
     * @ORM\Column(name="j1", type="string", nullable=true)
     */
    protected $j1;

    /**
     * @var $j2
     * @ORM\Column(name="j2", type="string", nullable=true)
     */
    protected $j2;

    /**
     * @var $j3
     * @ORM\Column(name="j3", type="string", nullable=true)
     */
    protected $j3;

    /**
     * @var $j4
     * @ORM\Column(name="j4", type="string", nullable=true)
     */
    protected $j4;

    /**
     * @var $j5
     * @ORM\Column(name="j5", type="string", nullable=true)
     */
    protected $j5;

    /**
     * @var $j6
     * @ORM\Column(name="j6", type="string", nullable=true)
     */
    protected $j6;

    /**
     * @var $j7
     * @ORM\Column(name="j7", type="string", nullable=true)
     */
    protected $j7;


    /**
     * @var \Datestring
     * @ORM\Column(name="phrase", type="string", nullable=false)
     */
    protected $phrase;


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
     * Set j1
     *
     * @param string $j1
     * @return DemandeurHoraires
     */
    public function setPhrase($ph)
    {
        $this->phrase = $ph;
    }

    /**
     * Get j1
     *
     * @return string 
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * Set j1
     *
     * @param string $j1
     * @return DemandeurHoraires
     */
    public function setJ1($j1)
    {
        $this->j1 = $j1;
    }

    /**
     * Get j1
     *
     * @return string
     */
    public function getJ1()
    {
        return $this->j1;
    }

    /**
     * Set j2
     *
     * @param string $j2
     * @return DemandeurHoraires
     */
    public function setJ2($j2)
    {
        $this->j2 = $j2;
    }

    /**
     * Get j2
     *
     * @return string 
     */
    public function getJ2()
    {
        return $this->j2;
    }

    /**
     * Set j3
     *
     * @param string $j3
     * @return DemandeurHoraires
     */
    public function setJ3($j3)
    {
        $this->j3 = $j3;
    }

    /**
     * Get j3
     *
     * @return string 
     */
    public function getJ3()
    {
        return $this->j3;
    }

    /**
     * Set j4
     *
     * @param string $j4
     * @return DemandeurHoraires
     */
    public function setJ4($j4)
    {
        $this->j4 = $j4;
    }

    /**
     * Get j4
     *
     * @return string 
     */
    public function getJ4()
    {
        return $this->j4;
    }

    /**
     * Set j5
     *
     * @param string $j5
     * @return DemandeurHoraires
     */
    public function setJ5($j5)
    {
        $this->j5 = $j5;
    }

    /**
     * Get j5
     *
     * @return string 
     */
    public function getJ5()
    {
        return $this->j5;
    }

    /**
     * Set j6
     *
     * @param string $j6
     * @return DemandeurHoraires
     */
    public function setJ6($j6)
    {
        $this->j6 = $j6;
    }

    /**
     * Get j6
     *
     * @return string 
     */
    public function getJ6()
    {
        return $this->j6;
    }

    /**
     * Set j7
     *
     * @param string $j7
     * @return DemandeurHoraires
     */
    public function setJ7($j7)
    {
        $this->j7 = $j7;
    }

    /**
     * Get j7
     *
     * @return string 
     */
    public function getJ7()
    {
        return $this->j7;
    }

    /**
     * Set demandeur
     *
     * @param Demandeur $demandeur
     * @return DemandeurHoraires
     */
    public function setDemandeur(Demandeur $demandeur = null)
    {
        $this->demandeur = $demandeur;
    }

    /**
     * Get demandeur
     *
     * @return Demandeur
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }
}