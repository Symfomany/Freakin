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
 * @ORM\Table(name="demandeur")
 * @UniqueEntity(fields={"email"}, message="Votre email est déjà utilisé")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\DemandeurRepository")
 */
class Demandeur extends EntityRepository  implements AdvancedUserInterface, \Serializable {

    public function __construct() {
        $this->optIn = 0;
        $this->permis = 0;
        $this->mobiliter = 0;
        $this->updatedAt = new \DateTime('now');
        $this->createdAt = new \DateTime('now');
        $this->lastActivity = new \DateTime('now');
        $this->accountnonlocked = true;
        $this->accountnonexpired = true;
        $this->token = sha1($this->getEmail() . $this->getFirstname() . time());
        $this->salt = md5(sprintf(
            '%s_%d_%f', uniqid(), rand(0, 99999), microtime(true)
        ));
        $this->experiences = new ArrayCollection();
        $this->characters = "1,3";
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
     * @ORM\Column(name="firstname", type="string", length=32)
     * @Assert\NotBlank(message = "Votre prénom ne peut être vite")
     * @Assert\Length(
     *      min = "2",
     *      max = "32",
     *      minMessage = "Votre prénom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Votre prénom doit faire au maximum {{ limit }} caractères"
     *      )
     */
    protected $firstname;


    /**
     * @var string $lastname
     * @ORM\Column(name="lastname", type="string", length=32, nullable=false)
     * @Assert\NotBlank(message = "Votre nom ne peut être vite")
     * @Assert\Length(
     *      min = "2",
     *      max = "32",
     *      minMessage = "Votre nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Votre nom doit faire au maximum {{ limit }} caractères"
     *      )
     */
    protected $lastname;

    /**
     * @var text $mea
     * @ORM\Column(name="mea", type="text", nullable=true)
     */
    public $mea;


    /**
     * @var text $description
     * @Assert\Length(
     *      min = 2,
     *      max = 550,
     *      minMessage = "Votre description doit faire au moins 2 caractères",
     *      maxMessage = "Votre description ne peut pas être plus long que 550 caractères",
     *      groups={"default"}
     * )
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    public $description;


    /**
     * @var string $zipcode
     * @ORM\Column(name="zipcode", type="integer", length=11, nullable=true)
     */
    public $zipcode;

    /**
     * @var string $ville
     * @ORM\Column(name="ville", type="string", length=60, nullable=true)
     */
    public $ville;


    /**
     * @var string $tel
     * @ORM\Column(name="tel", type="string", nullable=true, length=15)
     * @Assert\Regex(pattern="/^(0|\+[1-9][0-9]{0,2})[1-9]([-. ]?[0-9]{2}){4}$/", message="Le téléphone est invalide",  groups={"default"})
     */
    public $tel;

    /**
     * @var string $password
     * @Assert\NotBlank(message = "Votre mot de passe n'est pas correct", groups={"default", "forget"})
     * @Assert\Length(
     *     min=6,
     *     minMessage="Votre mot de passe doit comporter {{ limit }} caractères.",
     *     groups={"default"}
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    public $password;

    /**
     * @ORM\OneToOne(targetEntity="DemandeurHoraires", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $horaires;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string",  length=128,  unique=true, nullable=false)
     */
    protected $titre;

    /**
     * @var string $xpPro
     *
     * @ORM\Column(name="xp_pro", type="text", nullable=true)
     */
    protected $xpPro;

    /**
     * @var string $etude
     *
     * @ORM\Column(name="etude", type="text", nullable=true)
     */
    protected $etude;

    /**
     * @var string $statut
     *
     * @ORM\Column(name="statut", type="text", nullable=false)
     */
    protected $statut;

    /**
     * @var string $permis
     *
     * @ORM\Column(name="permis", type="boolean", nullable=true)
     */
    protected $permis;

    /**
     * @var string $mobiliter
     *
     * @ORM\Column(name="mobiliter", type="smallint", nullable=true)
     */
    protected $mobiliter;

    /**
     * @var date $dob
     *
     * @ORM\Column(name="dob", type="date", nullable=true)
     * @Assert\NotBlank(groups={"default"} , message="Votre date de naissance est obligatoire")
     * @Assert\DateTime(groups={"default"}, message = "Il faut rentrer une date de naissance  de format  jj/mm/aaaa")
     */
    public $dob;



    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=128,  unique=true, nullable=false)
     * @Assert\NotBlank(message = "Votre email ne peut être vide")
     * @Assert\Email(
     *      message = "Votre email n'est pas valide",
     *      checkMX = true
     *      )
     */
    protected $email;


    /**
     * @var boolean
     *
     * @ORM\Column(name="optin", type="boolean", nullable=true)
     */
    public $optin;


    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=true)
     */
    public $ip;



    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    public $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50, nullable=true)
     */
    public $token;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    public $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    public $createdAt;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_auth", type="datetime", nullable=true)
     */
    public $dateAuth;


    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    public $enabled;


    /**
     * @var boolean
     *
     * @ORM\Column(name="accountnonexpired", type="boolean", nullable=true)
     */
    public $accountnonexpired;

    /**
     * @var boolean $accountnonlocked
     *
     * @ORM\Column(name="accountnonlocked", type="boolean")
     */
    public $accountnonlocked;

    /**
     * @var string $slug
     * @Gedmo\Slug(fields={"firstname","lastname","zipcode"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @var string $characters
     * @ORM\Column(name="characters", type="string", length=255, nullable=true)
     */
    protected $characters;

    /**
     * @var integer
     *
     * @ORM\Column(name="fid", type="integer", nullable=true)
     */
    public $fid;

    /**
     * @var string
     *
     * @ORM\Column(name="fuser", type="text", nullable=true)
     */
    public $fuser;

    /**
     * @var string
     *
     * @ORM\Column(name="guser", type="text", nullable=true)
     */
    public $guser;


    /**
     * @var datetime
     *
     * @ORM\Column(name="lastAction", type="text", nullable=true)
     */
    public $lastAction;
    /**
     * @var datetime
     *
     * @ORM\Column(name="lastMyAction", type="text", nullable=true)
     */
    public $lastMyAction;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    public $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    public $latitude;


    /**
     * @var string
     *
     * @ORM\Column(name="vue", type="integer",  nullable=true)
     */
    private $vue;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=200, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="DemandeurSkill", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    private $skill;

    /**
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    protected $experiences;

    /**
     * @ORM\OneToMany(targetEntity="Certificat", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    protected $certificates;

    /**
     * @ORM\ManyToMany(targetEntity="Languages")
     * @ORM\JoinTable(name="demandeur_i18n_language_codes")
     */
    protected $speaks;

    /**
     * @ORM\OneToMany(targetEntity="Entretien", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    protected $entretiens;

    /**
     * @ORM\OneToOne(targetEntity="Metier", cascade={"all"}, orphanRemoval=true)
     */
    protected $metier;

    /**
     * @ORM\OneToMany(targetEntity="Favoris", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    protected $favoris;

    /**
     * @ORM\OneToMany(targetEntity="Formation", mappedBy="demandeur", cascade={"all"}, orphanRemoval=true)
     */
    protected $formations;

    /**
     * @ORM\ManyToMany(targetEntity="Languages")
     * @ORM\JoinTable(name="demandeur_i18n_language_codes")
     */
    private $languages;

    /**
     *
     * @ORM\OneToOne(targetEntity="DemandeurNewsletter",mappedBy="demandeur")
     */
    protected $newsletter;



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
     * Set firstname
     *
     * @param string $firstname
     * @return Users
     */
    public function setFirstname($firstname)
    {
        $this->firstname = ucfirst(mb_strtolower($firstname, 'UTF-8'));
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return ucfirst(mb_strtolower($this->firstname, 'UTF-8'));
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Users
     */
    public function setLastname($lastname)
    {

        $this->lastname = ucfirst(mb_strtolower($lastname, 'UTF-8'));
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return ucfirst(mb_strtolower($this->lastname, 'UTF-8'));
    }


    /**
     * Set path
     *
     * @param string $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }


    /**
     * Set path
     *
     * @param string $file
     */
    public function setFileUploadStream(File $file)
    {
        $this->file = $file;
    }

    /**
     * Set path
     *
     * @param string $file
     */
    public function setFileBrut(File $file)
    {
        $this->filebrut = $file;
    }
    /**
     * Set description
     *
     * @param string $description
     * @return Demandeur
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * Set zipcode
     *
     * @param integer $zipcode
     * @return Demandeur
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * Return Departement of User
     * @return string
     */
    public function getDepartement(){

        return substr($this->zipcode,0,2);
    }

    /**
     * Get zipcode
     *
     * @return integer 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Demandeur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return ucfirst(mb_strtolower($this->ville, 'UTF-8'));
    }
    /**
     * Set tel
     *
     * @param string $tel
     * @return Demandeur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Demandeur
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return Demandeur
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Demandeur
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Demandeur
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
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
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return Demandeur
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set optin
     *
     * @param boolean $optin
     * @return Demandeur
     */
    public function setOptin($optin)
    {
        $this->optin = $optin;
    }

    /**
     * Get optin
     *
     * @return boolean 
     */
    public function getOptin()
    {
        return $this->optin;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Demandeur
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return Demandeur
     */
    public function setPoint($point)
    {
        $this->point = $point;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Demandeur
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Demandeur
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Demandeur
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Demandeur
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set dateAuth
     *
     * @param \DateTime $dateAuth
     * @return Demandeur
     */
    public function setDateAuth($dateAuth)
    {
        $this->dateAuth = $dateAuth;
    }

    /**
     * Get dateAuth
     *
     * @return \DateTime 
     */
    public function getDateAuth()
    {
        return $this->dateAuth;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Demandeur
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set accountnonexpired
     *
     * @param boolean $accountnonexpired
     * @return Demandeur
     */
    public function setAccountnonexpired($accountnonexpired)
    {
        $this->accountnonexpired = $accountnonexpired;
    }

    /**
     * Get accountnonexpired
     *
     * @return boolean 
     */
    public function getAccountnonexpired()
    {
        return $this->accountnonexpired;
    }

    /**
     * Set accountnonlocked
     *
     * @param boolean $accountnonlocked
     * @return Demandeur
     */
    public function setAccountnonlocked($accountnonlocked)
    {
        $this->accountnonlocked = $accountnonlocked;
    }

    /**
     * Get accountnonlocked
     *
     * @return boolean 
     */
    public function getAccountnonlocked()
    {
        return $this->accountnonlocked;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Demandeur
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set fid
     *
     * @param integer $fid
     * @return Demandeur
     */
    public function setFid($fid)
    {
        $this->fid = $fid;
    }

    /**
     * Get fid
     *
     * @return integer 
     */
    public function getFid()
    {
        return $this->fid;
    }

    /**
     * Set fuser
     *
     * @param string $fuser
     * @return Demandeur
     */
    public function setFuser($fuser)
    {
        $this->fuser = $fuser;
    }

    /**
     * Get fuser
     *
     * @return string 
     */
    public function getFuser()
    {
        return $this->fuser;
    }

    /**
     * Set guser
     *
     * @param string $guser
     * @return Demandeur
     */
    public function setGuser($guser)
    {
        $this->guser = $guser;
    }

    /**
     * Get guser
     *
     * @return string 
     */
    public function getGuser()
    {
        return $this->guser;
    }

    /**
     * Set lastAction
     *
     * @param string $lastAction
     * @return Demandeur
     */
    public function setLastAction($lastAction)
    {
        $this->lastAction = $lastAction;
    }

    /**
     * Get lastAction
     *
     * @return string 
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * Set lastMyAction
     *
     * @param string $lastMyAction
     * @return Demandeur
     */
    public function setLastMyAction($lastMyAction)
    {
        $this->lastMyAction = $lastMyAction;
    }

    /**
     * Get lastMyAction
     *
     * @return string 
     */
    public function getLastMyAction()
    {
        return $this->lastMyAction;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Demandeur
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Demandeur
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set vue
     *
     * @param integer $vue
     * @return Demandeur
     */
    public function setVue($vue)
    {
        $this->vue = $vue;
    }

    /**
     * Get vue
     *
     * @return integer 
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Demandeur
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }


    /*     * ****  SECURITY ****** */

    /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        if (is_null($this->password) || !$this->password || strlen($this->password) < 1) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isaccountnonexpired()
    {
        return $this->accountnonexpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isaccountnonlocked()
    {
        return $this->accountnonlocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    public function eraseCredentials()
    {
        $this->roles = null;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(UserInterface $user)
    {
        return $user->getEmail() == $this->getEmail();
    }

//    Reccurrente function


    public function getUsername()
    {
        return $this->email;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->password,
            $this->email
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->password,
            $this->email
            ) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getAge()
    {
        if ($dob = $this->getDob()) {
            $now = new \Datetime('now');
            $today['mois'] = $now->format('m');
            $today['jour'] = $now->format('d');
            $today['annee'] = $now->format('Y');
            $annees = $today['annee'] - $dob->format('Y');
            if ($today['mois'] <= $dob->format('m')) {
                if ($dob->format('m') == $today['mois']) {
                    if ($dob->format('d') > $today['jour'])
                        $annees--;
                } else
                    $annees--;
            }
            return $annees;
        }
        return null;
    }




    /**
     * Uploads
     */

    /**
     * Get Absolute Path
     * @return type
     */
    public function getAbsolutePath($location = 'users', $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadRootDir($location, $iduser) . '/' . $this->avatar;
    }

    /**
     * Get Src Absolute Path
     * @return type
     */
    public function getSrcAbsolutePath($location = 'users', $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadRootDir($location, $iduser) . '/';
    }

    /**
     * Get Web Path
     * @return type
     */
    public function getWebPath($location = 'users', $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadDir($location, $iduser) . '/' . $this->avatar;
    }

    /**
     * Get Upload dir
     * @param type $location
     * @return type
     */
    public function getUploadRootDir($location = 'users', $iduser = 'null')
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir($location, $iduser);
    }

    /**
     * Get Upload Directory
     * @param type $location
     * @return type
     */
    public function getUploadDir($location = 'users', $iduser)
    {
        return 'uploads/' . $location . '/' . $iduser;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return MediasUsers
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }


    /**
     * Get Upload Action
     * @param type $location
     * @return type
     */
    public function upload($location = 'users', $iduser)
    {

        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return false;
        }
        $mime = $this->file->getMimeType();
        $this->extension = $this->file->guessExtension();

        if ($mime !== 'image/jpeg' && $mime !== 'image/jpg' && $mime !== 'image/png' && $mime !== 'image/gif' && $mime != 'image/bmp')
            return false;

        if (!is_dir($this->getUploadRootDir($location, $iduser)))
            if (!@mkdir($this->getUploadRootDir($location, $iduser)))
                return;


        $this->avatar_name = md5(uniqid(rand(), true));
        $this->avatar = $this->avatar_name . '.' . $this->file->guessExtension();
        $this->avatarbig = $this->avatar_name . '-big.' . $this->file->guessExtension();
        $this->avatarmedium = $this->avatar_name . '-medium.' . $this->file->guessExtension();
        $this->avatarcamera = $this->avatar_name . '-camera.' . $this->file->guessExtension();

        //Original Picture
        $path = $this->file->move($this->getUploadRootDir($location, $iduser), $this->avatar);
        $bigfile = $this->getUploadRootDir($location, $iduser) . '/' . $this->avatar_name . '.' . $this->extension;

        if ($this->extension == "jpg" || $this->extension == "jpeg") {
            $src_img = imagecreatefromjpeg($this->getUploadRootDir($location, $iduser) . '/' . $this->avatar);
        }
        if ($this->extension == "png") {
            $src_img = imagecreatefrompng($this->getUploadRootDir($location, $iduser) . '/' . $this->avatar);
        }
        if ($this->extension == "gif") {
            $src_img = imagecreatefromgif($this->getUploadRootDir($location, $iduser) . '/' . $this->avatar);
        }

        // Le ratio de l'image uploadée
        $oldWidth = imageSX($src_img);
        $oldHeight = imageSY($src_img);
        $ratio = $oldWidth / $oldHeight;

        // Les tailles à générer
        $taille = array(

            array(
                'name' => 'medium',
                'width' => 290,
                'height' => 250
            ),
            array(
                'name' => 'giga',
                'width' => 800,
                'height' => 600
            ),
            array(
                'name' => 'mini',
                'width' => 70,
                'height' => 60
            ),
        );

        // C'est parti
        foreach ($taille as $value) {
            // On prépare les valeurs
            $width = $value['width']-1;
            $height = $value['height']-1;
            $ratioImg = $width / $height;

            // On calcule les nouvelles
            if ($ratioImg > $ratio) {
                $newWidth = $width;
                $newHeight = $width / $ratio;
            } elseif ($ratioImg < $ratio) {
                $newHeight = $height;
                $newWidth = $height * $ratio;
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            // Point de départ du crop
            $x = ($newWidth - $width) / 2;
            $y = 0;

            // On bosse sur l'image
            $imagine = new \Imagine\Gd\Imagine();
            $imagine
                ->open($bigfile)
                ->resize(new \Imagine\Image\Box($newWidth, $newHeight))
                ->save(
                $this->getUploadRootDir($location, $iduser) . '/' . $this->avatar_name . '-' . $value['name'] . '.' . $this->extension,
                array(
                    'quality' => 100
                )
            );

        }

        // Les tailles à générer
        $taille2 = array(

            array(
                'name' => 'normal',
                'width' => 290,
                'height' => 200
            ),

        );

        // C'est parti
        foreach ($taille2 as $value) {
            // On prépare les valeurs
            $width = $value['width']-1;
            $height = $value['height']-1;
            $ratioImg = $width / $height;

            // On calcule les nouvelles
            if ($ratioImg > $ratio) {
                $newWidth = $width;
                $newHeight = $width / $ratio;
            } elseif ($ratioImg < $ratio) {
                $newHeight = $height;
                $newWidth = $height * $ratio;
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            // Point de départ du crop
            $x = ($newWidth - $width) / 2;
            $y = 0;

            // On bosse sur l'image
            $imagine = new \Imagine\Gd\Imagine();
            $imagine
                ->open($bigfile)
                ->thumbnail(new \Imagine\Image\Box($newWidth, $newHeight))
                ->crop(new Point($x, $y), new Box($width, $height))
                ->save(
                $this->getUploadRootDir($location, $iduser) . '/' . $this->avatar_name . '-' . $value['name'] . '.' . $this->extension,
                array(
                    'quality' => 100
                )
            );
        }

        // On supprime le fichier maintenant que l'on en a plus besoin
        if (@file_exists($bigfile))
            @unlink($bigfile);


        // clean up the file property as you won't need it anymore
        $this->file = null;

        return true;
    }

    /**
     * Set lastActivity
     *
     */
    public function isOnline()
    {
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));
        if($this->getLastActivity() < $delay)
            return false;
        return true;
    }


    public function isActive(){
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('5 minutes ago'));
        if($this->getLastActivity() >= $delay )
            return true;
        else
            return false;
    }


    /**
     * Set titre
     *
     * @param string $titre
     * @return Demandeur
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set xpPro
     *
     * @param string $xpPro
     * @return Demandeur
     */
    public function setXpPro($xpPro)
    {
        $this->xpPro = $xpPro;
    }

    /**
     * Get xpPro
     *
     * @return string 
     */
    public function getXpPro()
    {
        return $this->xpPro;
    }

    /**
     * Set etude
     *
     * @param string $etude
     * @return Demandeur
     */
    public function setEtude($etude)
    {
        $this->etude = $etude;
    }

    /**
     * Get etude
     *
     * @return string 
     */
    public function getEtude()
    {
        return $this->etude;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Demandeur
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set permis
     *
     * @param integer $permis
     * @return Demandeur
     */
    public function setPermis($permis)
    {
        $this->permis = $permis;
    }

    /**
     * Get permis
     *
     * @return integer 
     */
    public function getPermis()
    {
        return $this->permis;
    }

    /**
     * Set mobiliter
     *
     * @param integer $mobiliter
     * @return Demandeur
     */
    public function setMobiliter($mobiliter)
    {
        $this->mobiliter = $mobiliter;
    }

    /**
     * Get mobiliter
     *
     * @return integer 
     */
    public function getMobiliter()
    {
        return $this->mobiliter;
    }

    /**
     * Set horaires
     *
     * @param DemandeurHoraires $horaires
     * @return Demandeur
     */
    public function setHoraires(DemandeurHoraires $horaires = null)
    {
        $this->horaires = $horaires;
    }

    /**
     * Get horaires
     *
     * @return \MyFuckinJob\SiteBundle\Entity\DemandeurHoraires 
     */
    public function getHoraires()
    {
        return $this->horaires;
    }

    /**
     * Add skill
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Skill $skill
     * @return Demandeur
     */
    public function addSkill(\MyFuckinJob\SiteBundle\Entity\Skill $skill)
    {
        $this->skill[] = $skill;
    
        return $this;
    }

    /**
     * Remove skill
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Skill $skill
     */
    public function removeSkill(\MyFuckinJob\SiteBundle\Entity\Skill $skill)
    {
        $this->skill->removeElement($skill);
    }

    /**
     * Get skill
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Add experiences
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Experience $experiences
     * @return Demandeur
     */
    public function addExperience(\MyFuckinJob\SiteBundle\Entity\Experience $experiences)
    {
        $this->experiences[] = $experiences;
    
        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Experience $experiences
     */
    public function removeExperience(\MyFuckinJob\SiteBundle\Entity\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    public function setExperiences(ArrayCollection $experiences)
    {
        $this->experiences = $experiences;
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMea()
    {
        return $this->mea;
    }

    public function setMea($mea)
    {
        $this->mea = $mea;
    }


    /**
     * Add certificates
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Certificat $certificates
     * @return Demandeur
     */
    public function addCertificate(\MyFuckinJob\SiteBundle\Entity\Certificat $certificates)
    {
        $this->certificates[] = $certificates;
    
        return $this;
    }

    /**
     * Remove certificates
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Certificat $certificates
     */
    public function removeCertificate(\MyFuckinJob\SiteBundle\Entity\Certificat $certificates)
    {
        $this->certificates->removeElement($certificates);
    }

    /**
     * Get certificates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCertificates()
    {
        return $this->certificates;
    }

    /**
     * Set characters
     *
     * @param string $characters
     * @return Demandeur
     */
    public function setCharacters($characters)
    {
        $this->characters = $characters;
    
        return $this;
    }

    /**
     * Get characters
     *
     * @return string 
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Add speaks
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Certificat $speaks
     * @return Demandeur
     */
    public function addSpeak(\MyFuckinJob\SiteBundle\Entity\Certificat $speaks)
    {
        $this->speaks[] = $speaks;
    
        return $this;
    }

    /**
     * Remove speaks
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Certificat $speaks
     */
    public function removeSpeak(\MyFuckinJob\SiteBundle\Entity\Certificat $speaks)
    {
        $this->speaks->removeElement($speaks);
    }

    /**
     * Get speaks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSpeaks()
    {
        return $this->speaks;
    }

    /**
     * Add languages
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Languages $languages
     * @return Demandeur
     */
    public function addLanguage(\MyFuckinJob\SiteBundle\Entity\Languages $languages)
    {
        $this->languages[] = $languages;
    
        return $this;
    }

    /**
     * Remove languages
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Languages $languages
     */
    public function removeLanguage(\MyFuckinJob\SiteBundle\Entity\Languages $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Add entretiens
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Entretien $entretiens
     * @return Demandeur
     */
    public function addEntretien(\MyFuckinJob\SiteBundle\Entity\Entretien $entretiens)
    {
        $this->entretiens[] = $entretiens;
    
        return $this;
    }

    /**
     * Remove entretiens
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Entretien $entretiens
     */
    public function removeEntretien(\MyFuckinJob\SiteBundle\Entity\Entretien $entretiens)
    {
        $this->entretiens->removeElement($entretiens);
    }

    /**
     * Get entretiens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntretiens()
    {
        return $this->entretiens;
    }

    /**
     * Set newsletter
     *
     * @param \MyFuckinJob\SiteBundle\Entity\DemandeurNewsletter $newsletter
     * @return Demandeur
     */
    public function setNewsletter(\MyFuckinJob\SiteBundle\Entity\DemandeurNewsletter $newsletter = null)
    {
        $this->newsletter = $newsletter;
    
        return $this;
    }

    /**
     * Get newsletter
     *
     * @return \MyFuckinJob\SiteBundle\Entity\DemandeurNewsletter 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Add favoris
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Favoris $favoris
     * @return Demandeur
     */
    public function addFavori(\MyFuckinJob\SiteBundle\Entity\Favoris $favoris)
    {
        $this->favoris[] = $favoris;
    
        return $this;
    }

    /**
     * Remove favoris
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Favoris $favoris
     */
    public function removeFavori(\MyFuckinJob\SiteBundle\Entity\Favoris $favoris)
    {
        $this->favoris->removeElement($favoris);
    }

    /**
     * Get favoris
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFavoris()
    {
        return $this->favoris;
    }

    /**
     * Set metier
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Metier $metier
     * @return Demandeur
     */
    public function setMetier(\MyFuckinJob\SiteBundle\Entity\Metier $metier = null)
    {
        $this->metier = $metier;
    
        return $this;
    }

    /**
     * Get metier
     *
     * @return \MyFuckinJob\SiteBundle\Entity\Metier 
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * Add formations
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Formation $formations
     * @return Demandeur
     */
    public function addFormation(\MyFuckinJob\SiteBundle\Entity\Formation $formations)
    {
        $this->formations[] = $formations;
    
        return $this;
    }

    /**
     * Remove formations
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Formation $formations
     */
    public function removeFormation(\MyFuckinJob\SiteBundle\Entity\Formation $formations)
    {
        $this->formations->removeElement($formations);
    }

    /**
     * Get formations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormations()
    {
        return $this->formations;
    }
}