<?php
namespace Contrate\BackendBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields="email", message="Este email já está sendo utilizado.")
 * @UniqueEntity(fields="username", message="Este usuário já está sendo utilizado.")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->artists = new ArrayCollection();
        // your own logic
    }

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="É necessário preencher o seu nome")
     * @Assert\Length(
     *     min=3,
     *     max="30",
     *     minMessage="Nome muito pequeno",
     *     maxMessage="Nome muito longo"
     *     
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Caracteres inválidos"
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     *    
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     *    
     */
    protected $subtype;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     *    
     */
    protected $logo;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *    
     * @Assert\Regex(
     *     pattern="/^\(\d{2}\)\s{0,1}\d{4,5}-\d{4}$/",
     *     match=true,
     *     message="Telefone Inválido. Formato: (99) 9999-9999"
     * )
     */
    protected $phone;

    /**
     * @ORM\OneToMany(targetEntity="Artist", mappedBy="user")
     */
    protected $artists;


    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     *    
     */
    protected $site;

    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     *    
     */
    protected $agency;

    /* begin upload file */
    /**
     * @Assert\File(maxSize="4000000")
     */
    private $file;

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // check if we have an old image path
        if (isset($this->logo)) {
            // store the old name to delete after the update
            $this->temp = $this->logo;
            $this->logo = null;
        } else {
            $this->logo = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        //var_dump($this->getFile());exit;
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = "image_" . uniqid();
            $this->logo = $this->getUploadDir() . '/' . $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        //var_dump($this->getFile());exit;
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->logo);
     
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->logo
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->logo
            ? null
            : $this->getUploadDir().'/'.$this->logo;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesnt screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/ads/images';
    } 

    /* end upload file */


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
     * @return User
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
     * Set type
     *
     * @param string $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return User
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return User
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Add artists
     *
     * @param \Contrate\BackendBundle\Entity\Artist $artists
     * @return User
     */
    public function addArtist(\Contrate\BackendBundle\Entity\Artist $artists)
    {
        $this->artists[] = $artists;

        return $this;
    }

    /**
     * Remove artists
     *
     * @param \Contrate\BackendBundle\Entity\Artist $artists
     */
    public function removeArtist(\Contrate\BackendBundle\Entity\Artist $artists)
    {
        $this->artists->removeElement($artists);
    }

    /**
     * Get artists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * Set agency
     *
     * @param string $agency
     * @return User
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return string 
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * Set subtype
     *
     * @param string $subtype
     * @return User
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;

        return $this;
    }

    /**
     * Get subtype
     *
     * @return string 
     */
    public function getSubtype()
    {
        return $this->subtype;
    }
}
