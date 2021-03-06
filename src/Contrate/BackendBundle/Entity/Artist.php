<?php

namespace Contrate\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Artist
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Artist
{
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
      
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Digite o nome"
     *)
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="agency", type="string", length=255, nullable=true)
     */
    private $agency;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Digite o telefone para contato"
     *)
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Digite o site"
     *)
     * @ORM\Column(name="website", type="string", length=255)
     */
    private $website;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Escreva a descrição"
     *)
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @Assert\NotBlank(
     *      message = "Escolha uma categoria"
     *)
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="artists")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="artists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="artist")
     */
    protected $contacts;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="ArtistImage", mappedBy="artists", cascade={"ALL"})
     */
    protected $artist_images;

    /**
     * @var string
     *
     * @ORM\Column(name="default_img", type="string", length=255, nullable=true)
     */
    private $default_img;

    /**
     * @var string
     *
     * @ORM\Column(name="visit", type="integer")
     */
    private $visit;

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
     * @return Artist
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Artist
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Artist
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
     * Set pic
     *
     * @param string $pic
     * @return Artist
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string 
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Artist
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Artist
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set category
     *
     * @param \Contrate\BackendBundle\Entity\Category $category
     * @return Artist
     */
    public function setCategory(\Contrate\BackendBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Contrate\BackendBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \Contrate\BackendBundle\Entity\User $user
     * @return Artist
     */
    public function setUser(\Contrate\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Contrate\BackendBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCreatedAt() == null)
        {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Set agency
     *
     * @param string $agency
     * @return Artist
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
     * Set phone
     *
     * @param string $phone
     * @return Artist
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
     * Set website
     *
     * @param string $website
     * @return Artist
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Artist
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
     * Set status
     *
     * @param integer $status
     * @return Artist
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add contacts
     *
     * @param \Contrate\BackendBundle\Entity\Contact $contacts
     * @return Artist
     */
    public function addContact(\Contrate\BackendBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;

        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \Contrate\BackendBundle\Entity\Contact $contacts
     */
    public function removeContact(\Contrate\BackendBundle\Entity\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Add artist_images
     *
     * @param \Contrate\BackendBundle\Entity\ArtistImage $artistImages
     * @return Artist
     */
    public function addArtistImage(\Contrate\BackendBundle\Entity\ArtistImage $artistImages)
    {
        $this->artist_images[] = $artistImages;

        return $this;
    }

    /**
     * Remove artist_images
     *
     * @param \Contrate\BackendBundle\Entity\ArtistImage $artistImages
     */
    public function removeArtistImage(\Contrate\BackendBundle\Entity\ArtistImage $artistImages)
    {
        $this->artist_images->removeElement($artistImages);
    }

    /**
     * Get artist_images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtistImages()
    {
        return $this->artist_images;
    }

    /**
     * Set default_img
     *
     * @param string $defaultImg
     * @return Artist
     */
    public function setDefaultImg($defaultImg)
    {
        $this->default_img = $defaultImg;

        return $this;
    }

    /**
     * Get default_img
     *
     * @return string 
     */
    public function getDefaultImg()
    {
        if ($this->default_img == 'initial') 
            return "images/star.jpg";
        return $this->default_img;
    }

    /**
     * Set visit
     *
     * @param integer $visit
     * @return Artist
     */
    public function setVisit($visit)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit
     *
     * @return integer 
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
