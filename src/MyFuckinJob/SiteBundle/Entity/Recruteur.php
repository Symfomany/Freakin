<?php

namespace MyFuckinJob\SiteBundle\Entity;

namespace MyFuckinJob\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

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

use MyFuckinJob\SiteBundle\Entity\Client;



/**
 * MyFuckinJob\SiteBundle\Entity\Recruteur
 *
 * @ORM\Table(name="recruteur")
 * @UniqueEntity(fields={"email"}, message="Votre email est déjà utilisé")
 * @ORM\Entity(repositoryClass="MyFuckinJob\SiteBundle\Repository\RecruteurRepository")
 */
class Recruteur extends EntityRepository implements AdvancedUserInterface, \Serializable {

    const nameDir = 'recruteurs';


    public function __construct(){
        $this->nbPersonne = 0;
        $this->client = new ArrayCollection();
    }

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
     *
     * @ORM\Column(name="name", type="string", length=32)
     * @Assert\NotBlank(message = "Votre nom de société ne doit pas être vide")
     * @Assert\Length(
     *      min = "2",
     *      max = "32",
     *      minMessage = "Votre nom de société doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Votre nom de société doit faire au maximum {{ limit }} caractères"
     *      )
     */
    protected $name;

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
     * @Assert\NotBlank(message = "Votre mot de passe n'est pas correct")
     * @Assert\Length(
     *     min=6,
     *     minMessage="Votre mot de passe doit comporter {{ limit }} caractères."
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    public $password;

    /**
     * @var string $structure
     * @ORM\Column(name="structure", type="string", length=30, nullable=true)
     */
    public $structure;

    /**
     * @var string $structure
     * @ORM\Column(name="nb_personne", type="integer", nullable=true)
     */
    public $nbPersonne;

    /**
     * @var string $slug
     * @Gedmo\Slug(fields={"name", "zipcode"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=200, nullable=true)
     */
    private $avatar;

    /**
     * @var string $linkFb
     * @ORM\Column(name="link_fb", type="string", length=200, nullable=true)
     */
    private $linkFb;

    /**
     * @var string $linkTw
     * @ORM\Column(name="link_tw", type="string", length=200, nullable=true)
     */
    private $linkTw;

    /**
     * @var string $specificite
     * @ORM\Column(name="specificite", type="text", nullable=true)
     */
    private $specificite;

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
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="recruteur")
     * @ORM\JoinTable(name="recruteur_client")
     */
    protected $client;


    /**
     * @ORM\OneToMany(targetEntity="Favoris", mappedBy="recruteur", cascade={"all"}, orphanRemoval=true)
     */
    protected $favoris;


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
     * Set name
     *
     * @param string $name
     * @return Recruteur
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Recruteur
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
     * Set zipcode
     *
     * @param integer $zipcode
     * @return Recruteur
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    
        return $this;
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
     * @return Recruteur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set structure
     *
     * @param string $structure
     * @return Recruteur
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    
        return $this;
    }

    /**
     * Get structure
     *
     * @return string 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set nbPersonne
     *
     * @param integer $nbPersonne
     * @return Recruteur
     */
    public function setNbPersonne($nbPersonne)
    {
        $this->nbPersonne = $nbPersonne;
    
        return $this;
    }

    /**
     * Get nbPersonne
     *
     * @return integer 
     */
    public function getNbPersonne()
    {
        return $this->nbPersonne;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Recruteur
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
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
     * Set avatar
     *
     * @param string $avatar
     * @return Recruteur
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
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

    /**
     * Set linkFb
     *
     * @param string $linkFb
     * @return Recruteur
     */
    public function setLinkFb($linkFb)
    {
        $this->linkFb = $linkFb;
    
        return $this;
    }

    /**
     * Get linkFb
     *
     * @return string 
     */
    public function getLinkFb()
    {
        return $this->linkFb;
    }

    /**
     * Set linkTw
     *
     * @param string $linkTw
     * @return Recruteur
     */
    public function setLinkTw($linkTw)
    {
        $this->linkTw = $linkTw;
    
        return $this;
    }

    /**
     * Get linkTw
     *
     * @return string 
     */
    public function getLinkTw()
    {
        return $this->linkTw;
    }

    /**
     * Set specificite
     *
     * @param string $specificite
     * @return Recruteur
     */
    public function setSpecificite($specificite)
    {
        $this->specificite = $specificite;
    
        return $this;
    }

    /**
     * Get specificite
     *
     * @return string 
     */
    public function getSpecificite()
    {
        return $this->specificite;
    }

    /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return array('ROLE_RECRUTEUR');
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
     * Uploads
     */

    /**
     * Get Absolute Path
     * @return type
     */
    public function getAbsolutePath($location = self::nameDir, $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadRootDir($location, $iduser) . '/' . $this->avatar;
    }

    /**
     * Get Src Absolute Path
     * @return type
     */
    public function getSrcAbsolutePath($location = self::nameDir, $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadRootDir($location, $iduser) . '/';
    }

    /**
     * Get Web Path
     * @return type
     */
    public function getWebPath($location = self::nameDir, $iduser = 'null')
    {
        return null === $this->avatar ? null : $this->getUploadDir($location, $iduser) . '/' . $this->avatar;
    }

    /**
     * Get Upload dir
     * @param type $location
     * @return type
     */
    public function getUploadRootDir($location = self::nameDir, $iduser = 'null')
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir($location, $iduser);
    }

    /**
     * Get Upload Directory
     * @param type $location
     * @return type
     */
    public function getUploadDir($location = self::nameDir, $iduser)
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
    public function upload($location = self::nameDir, $iduser)
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


    public function isAccountNonExpired()
    {
        return $this->accountnonexpired;
    }


    public function isAccountNonLocked()
    {
        return $this->accountnonlocked;
    }


    public function isCredentialsNonExpired()
    {
        if (is_null($this->password) || !$this->password || strlen($this->password) < 1) {
            return false;
        }
        return true;
    }


    public function isEnabled()
    {
        return $this->enabled;
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
     * Set salt
     *
     * @param string $salt
     * @return Recruteur
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /** Get sal
     *
     * @return Recruteur
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->roles = null;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Recruteur
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
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
     * Set ip
     *
     * @param string $ip
     * @return Recruteur
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
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
     * Set token
     *
     * @param string $token
     * @return Recruteur
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
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
     * @return Recruteur
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
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
     * @return Recruteur
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
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
     * @return Recruteur
     */
    public function setDateAuth($dateAuth)
    {
        $this->dateAuth = $dateAuth;
    
        return $this;
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
     * @return Recruteur
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
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
     * @return Recruteur
     */
    public function setAccountnonexpired($accountnonexpired)
    {
        $this->accountnonexpired = $accountnonexpired;
    
        return $this;
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
     * @return Recruteur
     */
    public function setAccountnonlocked($accountnonlocked)
    {
        $this->accountnonlocked = $accountnonlocked;
    
        return $this;
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
     * Set lastAction
     *
     * @param string $lastAction
     * @return Recruteur
     */
    public function setLastAction($lastAction)
    {
        $this->lastAction = $lastAction;
    
        return $this;
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
     * @return Recruteur
     */
    public function setLastMyAction($lastMyAction)
    {
        $this->lastMyAction = $lastMyAction;
    
        return $this;
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
     * @return Recruteur
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
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
     * @return Recruteur
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
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

    public function __toString()
    {
        return $this->email;
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
     * {@inheritdoc}
     */
    public function equals(UserInterface $user)
    {
        return $user->getEmail() == $this->getEmail();
    }

    /**
     * Add client
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Client $client
     * @return Recruteur
     */
    public function addClient(\MyFuckinJob\SiteBundle\Entity\Client $client)
    {
        $this->client[] = $client;
    }

    /**
     * Remove client
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Client $client
     */
    public function removeClient(\MyFuckinJob\SiteBundle\Entity\Client $client)
    {
        $this->client->removeElement($client);
    }

    /**
     * Get client
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Recruteur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    
        return $this;
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
     * Add favoris
     *
     * @param \MyFuckinJob\SiteBundle\Entity\Favoris $favoris
     * @return Recruteur
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
}