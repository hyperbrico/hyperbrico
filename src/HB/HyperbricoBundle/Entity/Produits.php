<?php

namespace HB\HyperbricoBundle\Entity;


/**
 * Produit
 */
class Produits
{
    private $produits;

    private $csv;

    public function getProduits()
    {
        return $this->produits;
    }

    public function addProduit(\HB\HyperbricoBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;
    
        return $this;
    }

    public function removeProduit(\HB\HyperbricoBundle\Entity\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    public function clearProduits()
    {
        unset($this->produits);

        return $this;
    }

    public function setProduits($produits)
    {
        $this->produits = $produits;

        return $this;
    }

    public function getCsv()
    {
        return $this->csv;
    }

    public function setCsv($csv)
    {
        $this->csv = $csv;

        return $this;
    }
}