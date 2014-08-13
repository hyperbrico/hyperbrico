<?php

namespace HB\HyperbricoBundle\SendNewsletter;

use HB\HyperbricoBundle\Entity\NewsletterLog;

class HBSendNewsletter
{
	protected $mailerDefault;
	protected $em;
	protected $template;
	protected $csrf_provider;
	protected $from;
	protected $secret;
  protected $mail_brico;
  protected $mailerGifi;
  protected $username_gifi;
  protected $password_gifi;

	public function __construct(\Swift_Mailer $mailerDefault, $em, $template, $csrf_provider, $from, $secret, $mail_brico, $username_gifi, $password_gifi)
  {
    $this->mailerDefault = $mailerDefault;
    $this->em = $em;
    $this->template = $template;
    $this->csrf_provider = $csrf_provider;
    $this->from = $from;
    $this->secret = $secret;
    $this->mail_brico = $mail_brico;
    $this->username_gifi = $username_gifi;
    $this->password_gifi = $password_gifi;
  }

  public function sendNewsletter($adresses, $newsletter, $view, $isGifi, $catalogue = null, $type = null)
  {
  	$numSent = 0;
	  $this->em->persist($newsletter);
	  $this->em->flush();
    if ($isGifi) {
      $transport = \Swift_SmtpTransport::newInstance()
                  ->setHost('smtp.live.com')
                  ->setPort(587)
                  ->setEncryption('tls')
                  ->setUsername($this->username_gifi)
                  ->setPassword($this->password_gifi);
      $mailer = \Swift_Mailer::newInstance($transport);
    } else {
      $mailer = $this->mailerDefault;
    }
    
	  foreach ($adresses as $adresse) {
	    $contenu = $this->template->render($view . '.html.twig', array('newsletter' => $newsletter, 'adresse' => $adresse, 'token' => $adresse->getToken(), 'catalogue' => $catalogue, 'type' => $type));
	    $plain = $this->template->render($view . '.txt.twig', array('contenu' => html_entity_decode(strip_tags($newsletter->getContenu())), 'newsletter' => $newsletter, 'adresse' => $adresse, 'token' => $adresse->getToken(), 'catalogue' => $catalogue, 'type' => $type));
      $message = \Swift_Message::newInstance()
	              ->setSubject($newsletter->getObjet())
	              ->setFrom(array($this->from => 'HYPER BRICO'))
	              ->setTo($adresse->getMail())
                ->setBody($plain, 'text/plain')
                ->addPart($contenu, 'text/html');
	    $numSent += $mailer->send($message);              

	    $newsletterLog = new NewsletterLog();
	    $newsletterLog->setObjet($newsletter->getObjet());
	    $newsletterLog->setExpediteur($this->from);
	    $newsletterLog->setDestinataire($adresse->getMail());
	    $newsletterLog->setContenu($contenu);
	    $newsletterLog->setNewsletter($newsletter);
	    $this->em->persist($newsletterLog);
	  }

	  $this->em->flush();

	  return $numSent;
  }

  public function sendMail($destinataire, $entity, $view, $isContact, $catalogue = null, $type = null)
  {
  	$entity->setToken($this->csrf_provider->generateCsrfToken($this->secret . $entity->getNom() . $entity->getMail() . $entity->getSujet() . $entity->getMessage()));
  	$this->em->persist($entity);
  	$this->em->flush();
  	$html = $this->template->render($view . '.html.twig', array('entity' => $entity, 'token' => $entity->getToken(), 'catalogue' => $catalogue, 'type' => $type));
  	$plain = $this->template->render($view . '.txt.twig', array('entity' => $entity, 'token' => $entity->getToken(), 'catalogue' => $catalogue, 'type' => $type));
    $entity->setHtml($html);
  	$this->em->flush();
  	$hasSentTwo = 1;
  	$message = \Swift_Message::newInstance()
  							->setSubject($entity->getSujet())
  							->setFrom(array($this->from => 'HYPER BRICO'))
  							->setTo($destinataire)
  							->setBody($plain, 'text/plain')
                ->addPart($html, 'text/html');
  	$hasSentOne = $this->mailerDefault->send($message);
  	if ($isContact) {
  		$message = \Swift_Message::newInstance()
	  							->setSubject($entity->getSujet())
	  							->setFrom(array($this->from => 'HYPER BRICO'))
	  							->setTo($this->mail_brico)
	  							->setBody($plain, 'text/plain')
                  ->addPart($html, 'text/html');
	  	$hasSentTwo = $this->mailerDefault->send($message);
  	}
  	if ($hasSentOne && $hasSentTwo) {
  		return true;
  	}
  	return false;
  }
}