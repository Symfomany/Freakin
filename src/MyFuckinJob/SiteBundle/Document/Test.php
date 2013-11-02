<?php

namespace MyFuckinJob\SiteBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */
class Test
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $question;

    /**
     * @MongoDB\String
     */
    protected $reponse;

    /**
     * @MongoDB\String
     */
    protected $dateCreated;


    public function __construct(){
        $this->dateCreated = new \Datetime('now');
        $this->dateCreated = $this->dateCreated->format('d/m/y H:i:s');
    }



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return self
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Get question
     *
     * @return string $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     * @return self
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
        return $this;
    }

    /**
     * Get reponse
     *
     * @return string $reponse
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set dateCreated
     *
     * @param string $dateCreated
     * @return self
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return string $dateCreated
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}
