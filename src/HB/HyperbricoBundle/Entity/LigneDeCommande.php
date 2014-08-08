<?php

namespace HB\HyperbricoBundle\Entity;

/**
 * Article
 */
class LigneDeCommande
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
     * @var integer
     */
    private $tva;

    /**
     * @var integer
     */
    private $quantite;

    /**
     * @var integer
     */
    private $page;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \HB\HyperbricoBundle\Entity\Commande
     */
    private $commande;

    /**
     * @var \HB\HyperbricoBundle\Entity\Produit
     */
    private $produit;

    public function completeChamps()
    {
        if (!is_null($this->produit)) {
            $this->designation = $this->produit->getDesignation();
            $this->reference = $this->produit->getReference();
            $this->prix = $this->produit->getPrix();
            $this->marque = $this->produit->getMarque();
            $this->description = $this->produit->getDescription();
            $this->tva = $this->produit->getTva();
        }
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
     * Set page
     *
     * @param integer $page
     * @return Article
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

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return Article
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    
        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set produit
     *
     * @param \HB\HyperbricoBundle\Entity\Produit $produit
     * @return Article
     */
    public function setProduit(\HB\HyperbricoBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \HB\HyperbricoBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return LigneDeCommande
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
     * @return LigneDeCommande
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
     * @return LigneDeCommande
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
     * @return LigneDeCommande
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
     * @return LigneDeCommande
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
     * @param integer $tva
     * @return LigneDeCommande
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    
        return $this;
    }

    /**
     * Get tva
     *
     * @return integer 
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set commande
     *
     * @param \HB\HyperbricoBundle\Entity\Commande $commande
     * @return LigneDeCommande
     */
    public function setCommande(\HB\HyperbricoBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;
    
        return $this;
    }

    /**
     * Get commande
     *
     * @return \HB\HyperbricoBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return LigneDeCommande
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