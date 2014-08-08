<?php

namespace HB\HyperbricoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Commande
 */
class Commande
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $numeroMaison;

    /**
     * @var string
     */
    private $numeroVini;

    /**
     * @var string
     */
    private $lieuHabitation;

    /**
     * @var string
     */
    private $ile;

    /**
     * @var string
     */
    private $nomBateau;

    /**
     * @var \DateTime
     */
    private $dateDepartBateau;

    /**
     * @var \HB\HyperbricoBundle\Entity\LigneDeCommande
     */
    private $lignesDeCommande;

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $nombreArticle;

    /**
     * @var string
     */
    private $numeroCommande;

    /**
     * @var string
     */
    private $statut;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */
    private $informationComplementaire;

    /**
     * @var \DateTime
     */
    private $dateDemande;

    /**
     * @var string
     */
    private $fretADestination;

    public function isRestCorrect(ExecutionContextInterface $context)
    {
        if (strcmp($this->ile, 'tahiti') != 0) {
            if (empty($this->nomBateau)) {
                $context->addViolationAt('nomBateau', 'Veuillez entrer un nom de bateau.', array(), null);
            }
            if (!$this->dateDepartBateau instanceof \DateTime) {
                $context->addViolationAt('dateDepartBateau', 'Veuillez entrer une date correcte.', array(), null);
            }
            if (strtotime($this->dateDepartBateau->format('Y-m-d')) <= strtotime(date('Y-m-d'))) {
                $context->addViolationAt('dateDepartBateau', 'Veuillez entrer une date postérieure à celle d\'aujourd\'hui.', array(), null);
            }
            if (strcmp($this->fretADestination, 'Oui') != 0 && strcmp($this->fretADestination, 'Non') != 0) {
                $context->addViolationAt('fretADestination', 'Veuillez choisir un fret correct.', array(), null);
            }
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
     * Set nom
     *
     * @param string $nom
     * @return Commande
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Commande
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set numeroMaison
     *
     * @param string $numeroMaison
     * @return Commande
     */
    public function setNumeroMaison($numeroMaison)
    {
        $this->numeroMaison = $numeroMaison;
    
        return $this;
    }

    /**
     * Get numeroMaison
     *
     * @return string 
     */
    public function getNumeroMaison()
    {
        return $this->numeroMaison;
    }

    /**
     * Set numeroVini
     *
     * @param string $numeroVini
     * @return Commande
     */
    public function setNumeroVini($numeroVini)
    {
        $this->numeroVini = $numeroVini;
    
        return $this;
    }

    /**
     * Get numeroVini
     *
     * @return string 
     */
    public function getNumeroVini()
    {
        return $this->numeroVini;
    }

    /**
     * Set lieuHabitation
     *
     * @param string $lieuHabitation
     * @return Commande
     */
    public function setLieuHabitation($lieuHabitation)
    {
        $this->lieuHabitation = $lieuHabitation;
    
        return $this;
    }

    /**
     * Get lieuHabitation
     *
     * @return string 
     */
    public function getLieuHabitation()
    {
        return $this->lieuHabitation;
    }

    /**
     * Set ile
     *
     * @param string $ile
     * @return Commande
     */
    public function setIle($ile)
    {
        $this->ile = $ile;
    
        return $this;
    }

    /**
     * Get ile
     *
     * @return string 
     */
    public function getIle()
    {
        return $this->ile;
    }

    /**
     * Set nomBateau
     *
     * @param string $nomBateau
     * @return Commande
     */
    public function setNomBateau($nomBateau)
    {
        $this->nomBateau = $nomBateau;
    
        return $this;
    }

    /**
     * Get nomBateau
     *
     * @return string 
     */
    public function getNomBateau()
    {
        return $this->nomBateau;
    }

    /**
     * Set dateDepartBateau
     *
     * @param \DateTime $dateDepartBateau
     * @return Commande
     */
    public function setDateDepartBateau($dateDepartBateau)
    {
        $this->dateDepartBateau = $dateDepartBateau;
    
        return $this;
    }

    /**
     * Get dateDepartBateau
     *
     * @return \DateTime 
     */
    public function getDateDepartBateau()
    {
        return $this->dateDepartBateau;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignesDeCommande = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateDemande = new \DateTime;
        $this->statut = "En cours";
    }
    
    /**
     * Add lignesDeCommande
     *
     * @param \HB\HyperbricoBundle\Entity\LigneDeCommande $lignesDeCommande
     * @return Commande
     */
    public function addLignesDeCommande(\HB\HyperbricoBundle\Entity\LigneDeCommande $lignesDeCommande)
    {
        $this->lignesDeCommande[] = $lignesDeCommande;
    
        return $this;
    }

    /**
     * Remove lignesDeCommande
     *
     * @param \HB\HyperbricoBundle\Entity\LigneDeCommande $lignesDeCommande
     */
    public function removeLignesDeCommande(\HB\HyperbricoBundle\Entity\LigneDeCommande $lignesDeCommande)
    {
        $this->lignesDeCommande->removeElement($lignesDeCommande);
    }

    /**
     * Get lignesDeCommande
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLignesDeCommande()
    {
        return $this->lignesDeCommande;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Commande
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set nombreArticle
     *
     * @param integer $nombreArticle
     * @return Commande
     */
    public function setNombreArticle($nombreArticle)
    {
        $this->nombreArticle = $nombreArticle;
    
        return $this;
    }

    /**
     * Get nombreArticle
     *
     * @return integer 
     */
    public function getNombreArticle()
    {
        return $this->nombreArticle;
    }

    /**
     * Set numeroCommande
     *
     * @param integer $numeroCommande
     * @return Commande
     */
    public function setNumeroCommande($numeroCommande)
    {
        $this->numeroCommande = $numeroCommande;
    
        return $this;
    }

    /**
     * Get numeroCommande
     *
     * @return string 
     */
    public function getNumeroCommande()
    {
        return $this->numeroCommande;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Commande
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Commande
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set informationComplementaire
     *
     * @param string $informationComplementaire
     * @return Commande
     */
    public function setInformationComplementaire($informationComplementaire)
    {
        $this->informationComplementaire = $informationComplementaire;
    
        return $this;
    }

    /**
     * Get informationComplementaire
     *
     * @return string 
     */
    public function getInformationComplementaire()
    {
        return $this->informationComplementaire;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     * @return Commande
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;
    
        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime 
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set fretADestination
     *
     * @param string $fretADestination
     * @return Commande
     */
    public function setFretADestination($fretADestination)
    {
        $this->fretADestination = $fretADestination;
    
        return $this;
    }

    /**
     * Get fretADestination
     *
     * @return string 
     */
    public function getFretADestination()
    {
        return $this->fretADestination;
    }
}