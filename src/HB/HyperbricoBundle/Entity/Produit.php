<?php

namespace HB\HyperbricoBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Produit
 */
class Produit
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $designation;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var integer
     */
    private $prix;

    /**
     * @var string
     */
    private $marque;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $tva;

    /**
     * @var integer
     */
    private $page;

    /**
     * @var HB/HyperbricoBundle/Entity/Catalogue
     */
    private $catalogue;

    public function isPageCorrect(ExecutionContextInterface $context) 
    {
        if (!is_null($this->catalogue) && $this->page > $this->catalogue->getNombrePages()) {
            $context->addViolationAt('page', 'La page doit être inférieur à ' . $this->catalogue->getNombrePages() . ', le nombre de pages total du catalogue.', array(), null);
        }
    }

    public function __toString()
    {
        return $this->reference . ' - ' . $this->designation . ' - ' . $this->prix . ' XPF';
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
     * Set designation
     *
     * @param string $designation
     * @return Produit
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    
        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set reference
     *
     * @param string $reference
     * @return Produit
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    
        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set marque
     *
     * @param string $marque
     * @return Produit
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    
        return $this;
    }

    /**
     * Get marque
     *
     * @return string 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Produit
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
     * Set tva
     *
     * @param string $tva
     * @return Produit
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    
        return $this;
    }

    /**
     * Get tva
     *
     * @return string 
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set catalogue
     *
     * @param \HB\HyperbricoBundle\Entity\Catalogue $catalogue
     * @return Produit
     */
    public function setCatalogue(\HB\HyperbricoBundle\Entity\Catalogue $catalogue)
    {
        $this->catalogue = $catalogue;
    
        return $this;
    }

    /**
     * Get catalogue
     *
     * @return \HB\HyperbricoBundle\Entity\Catalogue 
     */
    public function getCatalogue()
    {
        return $this->catalogue;
    }

    /**
     * Set page
     *
     * @param integer $page
     * @return Produit
     */
    public function setPage($page)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return integer 
     */
    public function getPage()
    {
        return $this->page;
    }
}