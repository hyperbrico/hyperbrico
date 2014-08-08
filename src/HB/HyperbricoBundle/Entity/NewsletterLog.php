<?php

namespace HB\HyperbricoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterLog
 */
class NewsletterLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $objet;

    /**
     * @var string
     */
    private $expediteur;

    /**
     * @var string
     */
    private $destinataire;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var \HB\HyperbricoBundle\Entity\Newsletter
     */
    private $newsletter;

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
     * Set objet
     *
     * @param string $objet
     * @return NewsletterLog
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
    
        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set expediteur
     *
     * @param string $expediteur
     * @return NewsletterLog
     */
    public function setExpediteur($expediteur)
    {
        $this->expediteur = $expediteur;
    
        return $this;
    }

    /**
     * Get expediteur
     *
     * @return string 
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * Set destinataire
     *
     * @param string $destinataire
     * @return NewsletterLog
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;
    
        return $this;
    }

    /**
     * Get destinataire
     *
     * @return string 
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return NewsletterLog
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
     * Set newsletter
     *
     * @param \HB\HyperbricoBundle\Entity\Newsletter $newsletter
     * @return NewsletterLog
     */
    public function setNewsletter(\HB\HyperbricoBundle\Entity\Newsletter $newsletter = null)
    {
        $this->newsletter = $newsletter;
    
        return $this;
    }

    /**
     * Get newsletter
     *
     * @return \HB\HyperbricoBundle\Entity\Newsletter 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
}