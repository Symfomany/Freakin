<?php

namespace MyFuckinJob\SiteBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */
class Messages
{

    /**
     * @MongoDB\Id
     */
    protected $id;
    

    /** @MongoDB\ReferenceOne(targetDocument="User", inversedBy="messages") */
    protected $user;

    /**
     * @MongoDB\String
     */
    protected $title;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\String
     */
    protected $dateCreated;


    /**
     * @MongoDB\ReferenceOne(targetDocument="Messages")
     */
    protected $parent;



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
     * Set user
     *
     * @param MyFuckinJob\SiteBundle\Document\User $user
     * @return self
     */
    public function setUser(\MyFuckinJob\SiteBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return MyFuckinJob\SiteBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set parent
     *
     * @param MyFuckinJob\SiteBundle\Document\Messages $parent
     * @return self
     */
    public function setParent(\MyFuckinJob\SiteBundle\Document\Messages $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return MyFuckinJob\SiteBundle\Document\Messages $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get parent
     *
     * @return MyFuckinJob\SiteBundle\Document\Messages $parent
     */
    public function __toString()
    {
        return $this->title;
    }
}
