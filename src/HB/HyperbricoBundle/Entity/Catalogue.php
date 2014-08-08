<?php

namespace HB\HyperbricoBundle\Entity;

use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Catalogue
 */
class Catalogue
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;

    /**
     * @var \DateTime
     */
    private $datePubDebut;

    /**
     * @var \DateTime
     */
    private $datePubFin;

    /**
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @var \DateTime
     */
    private $dateFin;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var integer
     */
    private $nombrePages;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \HB\UserBundle\Entity\User
     */
    private $auteur;

    /**
     * @var \HB\HyperbricoBundle\Entity\Produit
     */
    private $produits;

    /**
     * @var string
     */
    private $type;

    private $file;

    private $hasChangedFile = true;

    private $oldSlug;

    private $tempFolderName;

    public function __construct()
    {
        $this->dateCreation = new \Datetime;
    }

    public function prePersist()
    {
        $this->dateModification = new \Datetime;
        $this->countPages();
    }

    public function preUpdate()
    {
        $this->dateModification = new \Datetime;
        if ($this->hasChangedFile) {
            $this->countPages();
        }
    }
    
    public function upload()
    {
        if ($this->hasChangedFile) {
            $this->preRemoveUpload();
            if (is_dir($this->tempFolderName)) {
              $this->rrmdir($this->tempFolderName);
            }
            if (!is_null($this->oldSlug) && is_dir($this->getUploadRootDir() . '/' . $this->oldSlug)) {
              $this->rrmdir($this->getUploadRootDir() . '/' . $this->oldSlug);
            }

            $this->file->move($this->getUploadRootDir() . $this->slug, $this->slug . '.' . $this->getExtension());
            exec('convert -density 100 -colorspace sRGB -quality 80 -alpha remove -resize x1185 ' . $this->getUploadRootDir() . $this->slug . '/' . $this->slug . '.' . $this->getExtension() . ' ' . $this->getUploadRootDir() . $this->slug . '/' . $this->slug . '.jpg');
            exec('convert -colorspace sRGB -quality 80 -alpha remove -thumbnail 410 ' . $this->getUploadRootDir() . $this->slug . '/' . $this->slug . '.' . $this->getExtension() . '[0] ' . $this->getUploadRootDir() . $this->slug . '/' . 'vignette.jpg');
        } else if (strcmp($this->slug, $this->oldSlug) != 0) {
            $this->preRemoveUpload();
            rename($this->getUploadRootDir() . '/' . $this->oldSlug, $this->tempFolderName);
            $objects = scandir($this->tempFolderName); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    rename($this->tempFolderName . '/' . $object, $this->tempFolderName . '/' . str_replace($this->oldSlug, $this->slug, $object));
                } 
            } 
            reset($objects);  
        }
    }

    public function preRemoveUpload()
    {
        $this->tempFolderName = $this->getUploadRootDir() . '/' . $this->slug;
    }

    public function removeUpload()
    {
        $this->rrmdir($this->tempFolderName);
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/' . $this->type . 's/';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin absolu vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getSlug().'.'.$this->getExtension();
    }

    protected function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
                } 
            } 
            reset($objects); 
            rmdir($dir); 
        } 
    }

    protected function countPages()
    {
        $imagick = new \Imagick(); 
        $imagick->readImage($this->file); 
        $this->nombrePages = $imagick->getNumberImages();
    }

    public function isFileValid(ExecutionContextInterface $context)
    {
        if (is_null($this->file)) {
            $this->preRemoveUpload();
            if (is_dir($this->tempFolderName) && is_file($this->tempFolderName . '/' . $this->slug . '.pdf')) {
                if ($this->areImagesValid()) {
                    $this->file = new File($this->tempFolderName . '/' . $this->slug . '.pdf');
                    $this->hasChangedFile = false;
                } else {
                    $context->addViolationAt('file', 'Une ou plusieurs images sont manquantes, veuillez rentrer un fichier pdf.', array(), null);
                }
                
            } else {
                $context->addViolationAt('file', 'Le fichier pdf est manquant, veuillez rentrer un fichier pdf.', array(), null);
            }
        }
    }

    public function isDatesValid(ExecutionContextInterface $context)
    {
        if (strtotime($this->datePubFin->format('Y-m-d')) < strtotime($this->datePubDebut->format('Y-m-d'))) {
            $context->addViolationAt('form', 'La date de publication de fin ne peut pas précéder la date de publication de début.', array(), null);
            $context->addViolationAt('datePubDebut', 'Date incorrecte', array(), null);
            $context->addViolationAt('datePubFin', 'Date incorrecte', array(), null);
        }
        if (strtotime($this->dateFin->format('Y-m-d')) < strtotime($this->dateDebut->format('Y-m-d'))) {
            $context->addViolationAt('form', 'La date de fin ne peut pas précéder la date de début.', array(), null);
            $context->addViolationAt('dateDebut', 'Date incorrecte', array(), null);
            $context->addViolationAt('dateFin', 'Date incorrecte', array(), null);
        }
    }

    protected function areImagesValid()
    {
        if ($this->nombrePages == 1 && is_file($this->tempFolderName . '/' . $this->slug . '.jpg')) {
            return true;
        } 
        for ($page = 0; $page < $this->nombrePages; $page++) {
            if (!is_file($this->tempFolderName . '/' . $this->slug . '-' . $page . '.jpg')) {
                return false;
            }
        }
        return true;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Catalogue
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Catalogue
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Catalogue
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set datePubDebut
     *
     * @param \DateTime $datePubDebut
     * @return Catalogue
     */
    public function setDatePubDebut($datePubDebut)
    {
        $this->datePubDebut = $datePubDebut;
    
        return $this;
    }

    /**
     * Get datePubDebut
     *
     * @return \DateTime 
     */
    public function getDatePubDebut()
    {
        return $this->datePubDebut;
    }

    /**
     * Set datePubFin
     *
     * @param \DateTime $datePubFin
     * @return Catalogue
     */
    public function setDatePubFin($datePubFin)
    {
        $this->datePubFin = $datePubFin;
    
        return $this;
    }

    /**
     * Get datePubFin
     *
     * @return \DateTime 
     */
    public function getDatePubFin()
    {
        return $this->datePubFin;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Catalogue
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Catalogue
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Catalogue
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
     * Set titre
     *
     * @param string $titre
     * @return Catalogue
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set auteur
     *
     * @param \HB\UserBundle\Entity\User $auteur
     * @return Catalogue
     */
    public function setAuteur(\HB\UserBundle\Entity\User $auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \HB\UserBundle\Entity\User 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set file
     *
     * @return Catalogue
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get extension
     *
     * @return string
     */
    private function getExtension()
    {
        return 'pdf';
    }

    /**
     * Set nombrePages
     *
     * @param integer $nombrePages
     * @return Catalogue
     */
    public function setNombrePages($nombrePages)
    {
        $this->nombrePages = $nombrePages;
    
        return $this;
    }

    /**
     * Get nombrePages
     *
     * @return integer 
     */
    public function getNombrePages()
    {
        return $this->nombrePages;
    }

    /**
     * Set oldSlug
     *
     * @param integer $oldSlug
     * @return Catalogue
     */
    public function setOldSlug($oldSlug)
    {
        $this->oldSlug = $oldSlug;
    
        return $this;
    }

    /**
     * Add produits
     *
     * @param \HB\HyperbricoBundle\Entity\Produit $produits
     * @return Catalogue
     */
    public function addProduit(\HB\HyperbricoBundle\Entity\Produit $produits)
    {
        $this->produits[] = $produits;
    
        return $this;
    }

    /**
     * Remove produits
     *
     * @param \HB\HyperbricoBundle\Entity\Produit $produits
     */
    public function removeProduit(\HB\HyperbricoBundle\Entity\Produit $produits)
    {
        $this->produits->removeElement($produits);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Catalogue
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
}