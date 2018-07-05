<?php

namespace Greenenjoy\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Greenenjoy\PostBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @Assert\File(
     *    maxSize = "4M",
     *    maxSizeMessage = "Le fichier est trop volumineux. La taille maximale autorisé est {{ limit }} {{ suffix }}.",
     *    mimeTypes = {"image/jpeg","image/png"},
     *    mimeTypesMessage = "L'extension {{ type }} n'est pas acceptée. Seuls les extensions de type {{ types }} le sont.",
     *    notFoundMessage = "Le fichier n'a pas été trouvé.",
     *    notReadableMessage = "Impossible de lire le fichier envoyé.",
     *    uploadIniSizeErrorMessage = "Le fichier est trop volumineux. La taille maximale autorisé est {{ limit }} {{ suffix }}.",
     *    uploadFormSizeErrorMessage = "Le fichier est trop volumineux.",
     *    uploadErrorMessage = "Impossible d'ajouter ce fichier."
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tempFileName;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set extension.
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if ($this->extension !== null) {
            $this->tempFileName = $this->extension;
            $this->extension = null;
            $this->alt = null;
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->file === null) {
            return;
        }

        $this->extension = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload()
    {
        if ($this->file === null) {
            return;
        }

        if ($this->tempFileName !== null) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFileName;

            if (file_exists($oldFile)) {
                unlink($oldFile);
              }
        }

        $this->file->move($this->getUploadRootDir(), $this->id.'.'.$this->extension);
    }

    /**
    * @ORM\PreRemove()
    */
    public function preRemoveUpload()
    {
        $this->tempFileName = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
    * @ORM\PostRemove()
    */
    public function removeUpload()
    {
        if (file_exists($this->tempFileName)) {
            unlink($this->tempFileName);
        }
    }

    public function getUploadDir()
    {
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
}
