<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HB\HyperbricoBundle\Entity\Catalogue;
use HB\HyperbricoBundle\Entity\Newsletter;
use HB\HyperbricoBundle\Entity\Recommande;
use HB\HyperbricoBundle\Form\CatalogueType;
use HB\HyperbricoBundle\Form\CatalogueEditType;
use HB\HyperbricoBundle\Form\RecommandeType;
use HB\HyperbricoBundle\Form\NewsletterType;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

class CatalogueController extends Controller
{
  public function indexAction()
  {
    $type = $this->getType('hb_hyperbrico_catalogues');

  	$catalogues = $this->getDoctrine()
    	                 ->getManager()
    	                 ->getRepository('HBHyperbricoBundle:Catalogue')
    	                 ->findCataloguePubliable($type);
                       
    if (count($catalogues) == 1) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogues[0]->getSlug())));
    } else {
      return $this->render('HBHyperbricoBundle:Catalogue:index.html.twig', array('catalogues' => $catalogues, 'type' => $type));
    }
  }

  public function archivesAction()
  {
    $type = $this->getType('hb_hyperbrico_archivesCatalogues');
    
    $catalogues = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('HBHyperbricoBundle:Catalogue')
                       ->findAncienCatalogue($type);
                       
    if (count($catalogues) == 1) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogues[0]->getSlug())));
    } else {
      $this->get('session')->getFlashBag()->add('info', '<p class="lead">Les prix des produits présentés dans ces ' . $type . 's ne sont plus valables.</p>');
      $this->get('session')->getFlashBag()->add('info', '<p class="lead">Ces ' . $type . 's sont mis' . ($type == 'catalogue' ? '' : 'es') . ' en ligne dans le but de présenter l\'étendue des produits que nous pouvons commercialiser.</p>');
      return $this->render('HBHyperbricoBundle:Catalogue:archives.html.twig', array('catalogues' => $catalogues, 'type' => $type));
    }
  }

  public function voirAction($slug)
  {
    $type = $this->getType('hb_hyperbrico_voirCatalogues', array('slug' => $slug));

    $catalogue = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findOneBySlug($slug);
    
    if ($catalogue === null) {
      throw $this->createNotFoundException('Le catalogue demandé [slug='.$slug.'] n\'existe pas.');
    }

    if (strtotime($catalogue->getDatePubFin()->format('Y-m-d')) >= strtotime(date('Y-m-d'))) {
      return $this->render('HBHyperbricoBundle:Catalogue:voir.html.twig', array('catalogue' => $catalogue, 'type' => $type));
    } else {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voirArchives' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
    }
  }

  public function voirArchivesAction($slug) 
  {
    $type = $this->getType('hb_hyperbrico_voirArchivesCatalogues', array('slug' => $slug));

    $catalogue = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findOneBySlug($slug);      

    if ($catalogue === null) {
      throw $this->createNotFoundException('Le catalogue demandé [slug='.$slug.'] n\'existe pas.');
    }

    if (strtotime($catalogue->getDatePubFin()->format('Y-m-d')) < strtotime(date('Y-m-d'))) {
      $this->get('session')->getFlashBag()->add('info', '<p class="lead">Les prix des produits présentés dans ce' . ($type == 'catalogue' ? ' ' : 'tte ') . $type . ' ne sont plus valables.</p>');
      $this->get('session')->getFlashBag()->add('info', '<p class="lead">Ce' . ($type == 'catalogue' ? ' ' : 'tte ') . $type . ' est mis' . ($type == 'catalogue' ? '' : 'e') . ' en ligne dans le but de présenter l\'étendue des produits que nous pouvons commercialiser.</p>');
      return $this->render('HBHyperbricoBundle:Catalogue:voir.html.twig', array('catalogue' => $catalogue, 'type' => $type));
    } else {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
    }
  }

  public function telechargerAction($slug)
  {
    $catalogue = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findOneBySlug($slug);
    
    if ($catalogue === null) {
      throw $this->createNotFoundException('Le catalogue demandé [slug='.$slug.'] n\'existe pas.');
    }

    if (strcmp($this->generateUrl('hb_hyperbrico_telechargerCatalogue', array('slug' => $slug)), $this->container->get('request')->getRequestUri()) == 0
     || strcmp($this->generateUrl('hb_hyperbrico_telechargerArchivesCatalogue', array('slug' => $slug)), $this->container->get('request')->getRequestUri()) == 0) {
      $type = 'catalogue';
    } else {
      $type ='promotion';
    }

    $response = new Response;
    $response->setContent(file_get_contents(__DIR__.'/../../../../web/uploads/' . $type . 's/' . $catalogue->getSlug() . '/' . $catalogue->getSlug() . '.pdf'));
    $response->headers->set('Content-Type', 'application/force-download');
    return $response;
  }

  public function recommanderAction($slug) 
  {
    $type = $this->getType('hb_hyperbrico_recommanderCatalogue', array('slug' => $slug));

    $catalogue = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findOneBySlug($slug);
    
    if ($catalogue === null) {
      throw $this->createNotFoundException('Le catalogue demandé [slug='.$slug.'] n\'existe pas.');
    }

    if (strtotime($catalogue->getDatePubFin()->format('Y-m-d')) < strtotime(date('Y-m-d'))) {
      $this->get('session')->getFlashBag()->add('error', 'Impossible de recommander un' . ($type == 'catalogue' ? '' : 'e') . ' ancien' . ($type == 'catalogue' ? ' ' : 'ne ') . $type . '.');
      return $this->redirect($this->generateUrl('hb_hyperbrico_voirArchives' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
    }

    $recommande = new Recommande();
    $form = $this->createForm(new RecommandeType(), $recommande, array('attr' => array('class' => 'form-horizontal')));

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $hasSent = $this->get('hb_sendNewsletter')->sendMail($recommande->getMailAmi(), $recommande, 'HBHyperbricoBundle:Catalogue:recommandeEmail', false, $catalogue, $type);

          if ($hasSent) {
            $this->get('session')->getFlashBag()->add('success', 'Recommandation bien envoyée.');
          } else {
            $this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'envoie de la recommandation.');
          }

          return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
        }
        $this->get('session')->getFlashBag()->add('error', 'Votre recommandation est incorrecte.');
    }

    return $this->render('HBHyperbricoBundle:Catalogue:recommande.html.twig', array('form' => $form->createView(), 'catalogue' => $catalogue, 'type' => $type));
      
  }

  public function envoyerAction(Catalogue $catalogue) 
  {
    $type = $this->getType('hb_hyperbrico_envoyerCatalogue', array('id' => $catalogue->getId()));
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
          
          $numSent = $this->get('hb_sendNewsletter')->sendNewsletter($adresses, $newsletter, 'HBHyperbricoBundle:Catalogue:catalogueMail', $catalogue, $type);

          $this->get('session')->getFlashBag()->add('success', 'Newsletter a bien été envoyée à ' . $numSent . ' adresses.');

          return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
        }
        $this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'envoie de la newsletter.');
    }

    return $this->render('HBHyperbricoBundle:Catalogue:envoyer.html.twig', array('form' => $form->createView(), 'catalogue' => $catalogue, 'type' => $type));
  }

  public function adminAction()
  {
    $type = $this->getType('hb_hyperbrico_adminCatalogue');
    $catalogues = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('HBHyperbricoBundle:Catalogue')
                       ->findBy(array('type' => $type), array('datePubDebut' => 'desc'));
                 
    return $this->render('HBHyperbricoBundle:Catalogue:admin.html.twig', array('catalogues' => $catalogues, 'type' => $type));
  }

  public function ajouterAction()
  {
    $type = $this->getType('hb_hyperbrico_ajouterCatalogue');
    $catalogue = new Catalogue;
    $catalogue->setAuteur($this->getUser());
    $catalogue->setType($type);
    $form = $this->createForm(new CatalogueType, $catalogue, array('attr' => array('name' => 'formLoading', 'class' => 'form-horizontal')));

    $request = $this->get('request');
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catalogue);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', ucfirst($type) . ' bien ajouté' . ($type == 'catalogue' ? '' : 'e') . '.');

            return $this->redirect($this->generateUrl('hb_hyperbrico_gererProduit' . ucfirst($type), array('id' => $catalogue->getId())));
        }
        $this->get('session')->getFlashBag()->add('error', 'Votre ' . ucfirst($type) . ' est incorrect' . ($type == 'catalogue' ? '' : 'e' . '.'));
    }

    return $this->render('HBHyperbricoBundle:Catalogue:ajouter.html.twig', array('form' => $form->createView(), 'type' => $type));
  }

  public function modifierAction(Catalogue $catalogue)
  {
    $type = $this->getType('hb_hyperbrico_modifierCatalogue', array('id' => $catalogue->getId()));
    $catalogue->setOldSlug($catalogue->getSlug());
    $form = $this->createForm(new CatalogueEditType(), $catalogue, array('attr' => array('name' => 'formLoading', 'class' => 'form-horizontal')));
    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
        $form->bind($request);
        
        if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($catalogue);
              $em->flush();

              $this->get('session')->getFlashBag()->add('success', ucfirst($type) . ' bien modifié' . ($type == 'catalogue' ? '' : 'e' . '.'));

              return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($type) . 's', array('slug' => $catalogue->getSlug())));
        }
        $this->get('session')->getFlashBag()->add('error', 'Votre ' . ucfirst($type) . ' est incorrect' . ($type == 'catalogue' ? '' : 'e') . '.');
    }

    return $this->render('HBHyperbricoBundle:Catalogue:modifier.html.twig', array('form' => $form->createView(), 'catalogue' => $catalogue, 'type' => $type));
  }

  public function supprimerAction(Catalogue $catalogue)
  {
    $type = $this->getType('hb_hyperbrico_supprimerCatalogue', array('id' => $catalogue->getId()));
  	$form = $this->createFormBuilder()->getForm();
 
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->remove($catalogue);
          $em->flush();

          $this->get('session')->getFlashBag()->add('success', ucfirst($type) . ' bien supprimé' . ($type == 'catalogue' ? '' : 'e') . '.');

          return $this->redirect($this->generateUrl('hb_hyperbrico_' . $type . 's'));
        }
    }

    return $this->render('HBHyperbricoBundle:Catalogue:supprimer.html.twig', array('catalogue' => $catalogue, 'type' => $type, 'form' => $form->createView()));
  }

  public function voirMailAction(Recommande $recommande, $token) 
  {
    if (strcmp($recommande->getToken(), $token) != 0) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }

    return $this->render('HBHyperbricoBundle:Catalogue:voirRecommandation.html.twig', array('recommande' => $recommande));
  }

  private function getType($route, $param = array()) {
    if (strcmp($this->generateUrl($route, $param), $this->container->get('request')->getRequestUri()) == 0) {
      return 'catalogue';
    } else {
      return 'promotion';
    }
  }
}
