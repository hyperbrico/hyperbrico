<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HB\HyperbricoBundle\Entity\News;
use HB\HyperbricoBundle\Entity\Catalogue;
use HB\HyperbricoBundle\Form\NewsType;
use JMS\SecurityExtraBundle\Annotation\Secure;


class NewsController extends Controller
{
  public function indexAction()
  {
  	$news = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:News')
                 ->findNewsPubliable();

    $catalogue = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findDernierCataloguePubliable('catalogue');

    $promotion = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Catalogue')
                      ->findDernierCataloguePubliable('promotion');

    return $this->render('HBHyperbricoBundle:News:index.html.twig', array('news' => $news, 'catalogue' => $catalogue, 'promotion' => $promotion));
  }

  public function archivesAction()
  {
    $news = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:News')
                 ->findAncienneNews();

    return $this->render('HBHyperbricoBundle:News:archives.html.twig', array('news' => $news));
  }

  public function voirAction($news_slug)
  {
    $news = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:News')
                 ->findOneBySlug($news_slug);

    if ($news === null) {
      throw $this->createNotFoundException('La news demandée [news_slug='.$news_slug.'] n\'existe pas.');
    }

    if (strtotime($news->getDatePubFin()->format('Y-m-d')) >= strtotime(date('Y-m-d'))) {
      return $this->render('HBHyperbricoBundle:News:voir.html.twig', array('news' => $news));
    } else {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voirArchivesNews', array('news_slug' => $news->getSlug())));
    }
  }

  public function voirArchivesAction($news_slug) 
  {
    $news = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:News')
                 ->findOneBySlug($news_slug);

    if ($news === null) {
      throw $this->createNotFoundException('La news demandée [news_slug='.$news_slug.'] n\'existe pas.');
    }

    
    if (strtotime($news->getDatePubFin()->format('Y-m-d')) < strtotime(date('Y-m-d'))) {
      return $this->render('HBHyperbricoBundle:News:voir.html.twig', array('news' => $news));
    } else {
      return $this->redirect($this->generateUrl('hb_hyperbrico_voirNews', array('news_slug' => $news->getSlug())));
    }
  }

  public function adminAction()
  {
    $news = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('HBHyperbricoBundle:News')
                 ->findBy(array(), array('datePubDebut'=>'desc'));
                 
      return $this->render('HBHyperbricoBundle:News:admin.html.twig', array('news' => $news));
  }

  public function ajouterAction()
  {
    $news = new News;
    $news->setAuteur($this->getUser());
    $form = $this->createForm(new NewsType, $news, array('attr' => array('class' => 'form-horizontal')));
   
    $request = $this->get('request');
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
   
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'News bien ajouté.');

        return $this->redirect($this->generateUrl('hb_hyperbrico_voirNews', array('news_slug' => $news->getSlug())));
      }
      $this->get('session')->getFlashBag()->add('error', 'Votre news est incorrecte.');
    }
   
    return $this->render('HBHyperbricoBundle:News:ajouter.html.twig', array('form' => $form->createView()));
  }

  public function modifierAction(News $news)
  {
    $form = $this->createForm(new NewsType(), $news, array('attr' => array('class' => 'form-horizontal')));

    $request = $this->getRequest();
 
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
 
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();
 
        $this->get('session')->getFlashBag()->add('success', 'News bien modifié.');
 
        return $this->redirect($this->generateUrl('hb_hyperbrico_voirNews', array('news_slug' => $news->getSlug())));
      }
      $this->get('session')->getFlashBag()->add('error', 'Votre news est incorrecte.');
    }
 
    return $this->render('HBHyperbricoBundle:News:modifier.html.twig', array('form' => $form->createView(), 'news' => $news));
  }

  public function supprimerAction(News $news)
  {
    $form = $this->createFormBuilder()->getForm();
 
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);
 
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();
 
        $this->get('session')->getFlashBag()->add('success', 'News bien supprimé.');
 
        return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
      }
    }
 
    return $this->render('HBHyperbricoBundle:News:supprimer.html.twig', array('news' => $news, 'form' => $form->createView()));
  }
}
