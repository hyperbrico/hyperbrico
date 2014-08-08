<?php

namespace HB\HyperbricoBundle\Entity;

class Recommande
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    protected $nom;

    /**
     * @var string
     */
    protected $mail;

    /**
     * @var string
     */
    protected $mailAmi;

    /**
     * @var string
     */
    protected $sujet;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \DateTime
     */
    private $dateEnvoi;

    /**
     * @var string
     */
    protected $html;

    protected $recaptcha;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateEnvoi = new \DateTime;
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
     * Set nom
     *
     * @param string $nom
     * @return Recommande
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

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
     * Set mail
     *
     * @param string $mail
     * @return Recommande
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mailAmi
     *
     * @return string 
     */
    public function getMailAmi()
    {
        return $this->mailAmi;
    }

    /**
     * Set mailAmi
     *
     * @param string $mailAmi
     * @return Recommande
     */
    public function setMailAmi($mailAmi)
    {
        $this->mailAmi = $mailAmi;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     * @return Recommande
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Recommande
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;

        return $this;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Recommande
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
     * Set html
     *
     * @param string $html
     * @return Recommande
     */
    public function setHtml($html)
    {
        $this->html = $html;
    
        return $this;
    }

    /**
     * Get html
     *
     * @return string 
     */
    public function getHtml()
    {
        return $this->html;
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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return Recommande
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
}