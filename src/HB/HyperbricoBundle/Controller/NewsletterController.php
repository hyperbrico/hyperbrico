<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HB\HyperbricoBundle\Entity\Adresse;
use HB\HyperbricoBundle\Entity\Newsletter;
use HB\HyperbricoBundle\Form\AdresseType;
use HB\HyperbricoBundle\Form\NewsletterType;
use JMS\SecurityExtraBundle\Annotation\Secure;

class NewsletterController extends Controller
{
  public function adminAdressesAction() 
  {
  	$adresses = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:Adresse')
                 ->findAll();
                 
    return $this->render('HBHyperbricoBundle:Newsletter:adminAdresses.html.twig', array('adresses' => $adresses));
  }

  public function adminAction() 
  {
    $newsletters = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:Newsletter')
                 ->findBy(array(), array('dateEnvoi'=>'desc'));
                 
    return $this->render('HBHyperbricoBundle:Newsletter:admin.html.twig', array('newsletters' => $newsletters));
  }

  public function adminLogsAction(Newsletter $newsletter) 
  {
    return $this->render('HBHyperbricoBundle:Newsletter:adminLogs.html.twig', array('newsletterLogs' => $newsletter->getNewsletterLogs()));
  }

  public function adminVoirAction($id, $destinataire) 
  {
    $newsletterLog = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('HBHyperbricoBundle:NewsletterLog')
                          ->findNewsletterLog($id, $destinataire);

    if (sizeof($newsletterLog) != 0) {
      return $this->render('HBHyperbricoBundle:Newsletter:voir.html.twig', array('newsletter' => $newsletterLog[0]));
    } else {
      $this->get('session')->getFlashBag()->add('error', 'Log de newsletter introuvable.');
      return $this->redirect($this->generateUrl('hb_hyperbrico_adminNewsletter'));
    }

    
  }

  public function envoyerAction()
  {
  	$newsletter = new Newsletter();
    $form = $this->createForm(new NewsletterType(), $newsletter, array('attr' => array('name' => 'formLoading', 'class' => 'form-horizontal')));

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
        	$adresses = $this->getDoctrine()
					                 ->getManager()
					                 ->getRepository('HBHyperbricoBundle:Adresse')
					                 ->findAll();

          $newsletter->setAuteur($this->getUser());
          
          $numSent = $this->get('hb_sendNewsletter')->sendNewsletter($adresses, $newsletter, 'HBHyperbricoBundle:Newsletter:newsletterMail', false);

          $this->get('session')->getFlashBag()->add('success', 'Newsletter a bien été envoyée à ' . $numSent . ' adresses.');

          return $this->redirect($this->generateUrl('hb_hyperbrico_admin'));
        }
        $this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'envoie de la newsletter.');
    }

    return $this->render('HBHyperbricoBundle:Newsletter:envoyer.html.twig', array('form' => $form->createView()));
  }

  public function supprimerAction(Adresse $adresse)
  {
  	$form = $this->createFormBuilder()->getForm();
 
      $request = $this->getRequest();
      if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adresse);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Adresse mail bien supprimée.');

            return $this->redirect($this->generateUrl('hb_hyperbrico_adminAdressesNewsletter'));
          }
      }

      return $this->render('HBHyperbricoBundle:Newsletter:supprimer.html.twig', array('adresse' => $adresse, 'form' => $form->createView()));
  }

  public function desinscriptionAction($mail, $token)
  {
    $adresse = $this->getDoctrine()->getManager()->getRepository('HBHyperbricoBundle:Adresse')->findOneByMail($mail);
    if ($adresse === null) {
      throw $this->createNotFoundException('L\'adresse mail demandée [mail='.$mail.'] n\'existe pas.');
    }
    $form = $this->createFormBuilder()->getForm();

    if (strcmp($adresse->getToken(), $token) != 0) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }
 
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->remove($adresse);
          $em->flush();

          $this->get('session')->getFlashBag()->add('success', 'Vous vous êtes bien désinscrits de la newsletter.');

          return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
        }
        $this->get('session')->getFlashBag()->add('error', 'Erreur lors de votre désinscription de la newsletter.');
    }

    return $this->render('HBHyperbricoBundle:Newsletter:desinscription.html.twig', array('adresse' => $adresse, 'form' => $form->createView()));
  }

  public function inscriptionAction()
  {
  	$adresse = new Adresse();
    $form = $this->createForm(new AdresseType(), $adresse, array('action' => $this->generateUrl('hb_hyperbrico_inscriptionNewsletter'), 'attr' => array('class' => 'form-inline')));

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          if (!$this->exists($adresse->getMail()))
          {
            $adresse->setToken($this->get('form.csrf_provider')->generateCsrfToken($this->container->getParameter('secret') . $adresse->getMail()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($adresse);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Vous vous êtes bien inscrit à la newsletter.');
          }
          else 
          {
            $this->get('session')->getFlashBag()->add('info', 'Vous êtes déjà inscrit à la newsletter.');
          }
        }
        else
        {
          $this->get('session')->getFlashBag()->add('error', 'Votre adresse mail est incorrecte.');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    return $this->render('HBHyperbricoBundle:Newsletter:formulaire.html.twig', array('form' => $form->createView()));
  }

  public function voirAction($id, $mail, $token) 
  {
    $adresse = $this->getDoctrine()->getManager()->getRepository('HBHyperbricoBundle:Adresse')->findOneByMail($mail);
    if ($adresse === null) {
      throw $this->createNotFoundException('L\'adresse mail demandée [mail='.$mail.'] n\'existe pas.');
    }

    if (strcmp($adresse->getToken(), $token) != 0) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }

    $newsletterLog = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('HBHyperbricoBundle:NewsletterLog')
                          ->findNewsletterLog($id, $mail);

    if (sizeof($newsletterLog) != 0) {
      return $this->render('HBHyperbricoBundle:Newsletter:voir.html.twig', array('newsletter' => $newsletterLog[0]));
    } else {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }    
  }

  /**
   * Vérifie si l'adresse mail est déjà dans la base de donnée ou pas
   *
   * @param string $mail
   * @return boolean
   */
  protected function exists($mail)
  {
    $adresse = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('HBHyperbricoBundle:Adresse')
                    ->findOneByMail($mail);
    
    return (is_null($adresse) ? false : true);
  }
}
