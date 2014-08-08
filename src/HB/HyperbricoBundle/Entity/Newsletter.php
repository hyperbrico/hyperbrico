<?php

namespace HB\HyperbricoBundle\Entity;

/**
 * Newsletter
 */
class Newsletter
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
    private $contenu;

    /**
     * @var \DateTime
     */
    private $dateEnvoi;

    /**
     * @var \HB\UserBundle\Entity\User
     */
    private $auteur;

    /**
     * @var \HB\HyperbricoBundle\Entity\NewsletterLog
     */
    private $newsletterLogs;

    public function __construct()
    {
        $this->dateEnvoi = new \Datetime;
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
     * Set objet
     *
     * @param string $objet
     * @return Newsletter
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
     * Set contenu
     *
     * @param string $contenu
     * @return Newsletter
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
     * Set dateEnvoie
     *
     * @param \DateTime $dateEnvoie
     * @return Newsletter
     */
    public function setDateEnvoie($dateEnvoie)
    {
        $this->dateEnvoie = $dateEnvoie;
    
        return $this;
    }

    /**
     * Get dateEnvoie
     *
     * @return \DateTime 
     */
    public function getDateEnvoie()
    {
        return $this->dateEnvoie;
    }

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return Newsletter
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    
        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime 
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Add newsletterLogs
     *
     * @param \HB\HyperbricoBundle\Entity\NewsletterLog $newsletterLogs
     * @return Newsletter
     */
    public function addNewsletterLog(\HB\HyperbricoBundle\Entity\NewsletterLog $newsletterLogs)
    {
        $this->newsletterLogs[] = $newsletterLogs;
    
        return $this;
    }

    /**
     * Remove newsletterLogs
     *
     * @param \HB\HyperbricoBundle\Entity\NewsletterLog $newsletterLogs
     */
    public function removeNewsletterLog(\HB\HyperbricoBundle\Entity\NewsletterLog $newsletterLogs)
    {
        $this->newsletterLogs->removeElement($newsletterLogs);
    }

    /**
     * Get newsletterLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNewsletterLogs()
    {
        return $this->newsletterLogs;
    }

    /**
     * Set auteur
     *
     * @param \HB\UserBundle\Entity\User $auteur
     * @return Newsletter
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