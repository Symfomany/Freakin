<?php

namespace MyFuckinJob\SiteBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */
class User
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $firstname;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\Hash
     */
    protected $addresses = array();


    /**
     * @MongoDB\String
     */
    protected $dateCreated;


    /** @MongoDB\ReferenceMany(targetDocument="Messages", mappedBy="user") */
    protected $messages;

    public function __construct(){
        $this->token = sha1(time());
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

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setAddresses($addresse)
    {
        $this->addresses = $addresse;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add message
     *
     * @param MyFuckinJob\SiteBundle\Document\Messages $message
     */
    public function addMessage(\MyFuckinJob\SiteBundle\Document\Messages $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Remove message
     *
     * @param MyFuckinJob\SiteBundle\Document\Messages $message
     */
    public function removeMessage(\MyFuckinJob\SiteBundle\Document\Messages $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return Doctrine\Common\Collections\Collection $messages
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
