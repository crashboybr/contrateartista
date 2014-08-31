<?php

namespace Contrate\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Artist", mappedBy="category")
     */
    protected $artists;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
    }



    /**
     * @var string
     *
     * @ORM\Column(name="pic", type="string", length=255)
     */
    private $pic;

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
        if (isset($this->pic)) {
            // store the old name to delete after the update
            $this->temp = $this->pic;
            $this->pic = null;
        } else {
            $this->pic = 'initial';
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
            $this->pic = $this->getUploadDir() . '/' . $filename.'.'.$this->getFile()->guessExtension();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->pic);
     
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
        return null === $this->pic
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->pic
            ? null
            : $this->getUploadDir().'/'.$this->pic;
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
        return 'upload/category';
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
     * @return Category
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
     * Set pic
     *
     * @param string $pic
     * @return Category
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
     * Add artists
     *
     * @param \Contrate\BackendBundle\Entity\Artist $artists
     * @return Category
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
}
