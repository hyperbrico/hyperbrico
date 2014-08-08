<?php

namespace HB\HyperbricoBundle\Entity;

use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * News
 */
class News
{
    /**
     * @var string
     */
    private $titre;

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
     * @var string
     */
    private $contenu;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \HB\UserBundle\Entity\User
     */
    private $auteur;

    public function isDatesValid(ExecutionContextInterface $context)
    {
        if (strtotime($this->datePubFin->format('Y-m-d')) < strtotime($this->datePubDebut->format('Y-m-d'))) {
            $context->addViolationAt('form', 'La date de publication de fin ne peut pas précéder la date de publication de début.', array(), null);
            $context->addViolationAt('datePubDebut', 'Date incorrecte', array(), null);
            $context->addViolationAt('datePubFin', 'Date incorrecte', array(), null);
        }
    }

    public function __construct()
    {
        $this->dateCreation = new \Datetime;
        $this->dateModification = new \Datetime;
    }

    public function preUpdate()
    {
        $this->dateModification = new \Datetime;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return News
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
     * Set slug
     *
     * @param string $slug
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * Set contenu
     *
     * @param string $contenu
     * @return News
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
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
     * @return News
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
}
