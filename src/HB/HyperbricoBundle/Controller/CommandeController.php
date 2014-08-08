<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HB\HyperbricoBundle\Entity\LigneDeCommande;
use HB\HyperbricoBundle\Entity\Commande;
use HB\HyperbricoBundle\Entity\CommandeTahiti;
use HB\HyperbricoBundle\Entity\Catalogue;
use HB\HyperbricoBundle\Entity\Produit;
use HB\HyperbricoBundle\Form\LigneDeCommandeType;
use HB\HyperbricoBundle\Form\LigneDeCommandePersoType;
use HB\HyperbricoBundle\Form\CommandeTahitiType;
use HB\HyperbricoBundle\Form\CommandeIlesType;
use HB\HyperbricoBundle\Form\CommandeEditType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandeController extends Controller
{
  public function adminAction() 
  {
    $commandes = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('HBHyperbricoBundle:Commande')
                      ->findBy(array(), array('numeroCommande'=>'desc'));
                 
    return $this->render('HBHyperbricoBundle:Commande:admin.html.twig', array('commandes' => $commandes));
  }

  public function voirAction()
  {
    $ligneDeCommande = new LigneDeCommande();
    $form = $this->createForm(new LigneDeCommandePersoType(), $ligneDeCommande, array('attr' => array('name' => 'ajoutProduit', 'class' => 'form-inline')));
    $panier = array();
    $session = $this->get('session');
    if (!is_null($session->get('reference'))) {
      foreach ($session->get('reference') as $ref) {
        $panier[] = $session->get($ref);
      }
    }

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
        $form->bind($request);

        $errorDescription = false;
        $errorQuantite = false;
        if ($form->isValid()) { // Mettre en rouge la quantité si erreur
          if (is_null($ligneDeCommande->getDescription())) {
            $errorDescription = true;
          }
          if (!is_numeric($ligneDeCommande->getQuantite())) {
            $errorQuantite = true;
          }
          if (!$errorDescription && !$errorQuantite) {
            if (!is_null($session->get('produitPerso'))) {
              $produitPerso = $session->get('produitPerso');
              array_push($produitPerso, $ligneDeCommande);
              $session->set('produitPerso', $produitPerso);
            } else {
              $session->set('produitPerso', array($ligneDeCommande));
            }
            $session->set('quantiteTotale', $session->get('quantiteTotale') + $ligneDeCommande->getQuantite());
            $html = '<tr style="height:72px;"><td>' . $ligneDeCommande->getReference() . '</td><td><span class="span5">' . $ligneDeCommande->getDescription() . '</span></td><td>' . $ligneDeCommande->getMarque() . '</td><td style="width:82px;"><div class="text-center"></div></td><td></td></tr>';
            return new Response('success????' . $this->generatePanierHtml($session, 'panier') . '????' . $html . '????' . $ligneDeCommande->getQuantite());
          }
        }
        if (is_numeric($ligneDeCommande->getQuantite()) && $ligneDeCommande->getQuantite() <= 0) {
          $errorQuantite = true;
        }
        return new Response('error????' . ($errorDescription ? 'description' : '') . '????' . ($errorQuantite ? 'quantite' : ''));
    } else {
      $this->cleanProduitPerso($session);
    }
    
    $panierPerso = array();
    if (!is_null($session->get('produitPerso'))) {
      foreach ($session->get('produitPerso') as $prodPerso) {
        $panierPerso[] = $prodPerso;
      }
    }

    return $this->render('HBHyperbricoBundle:Commande:index.html.twig', array('panierPerso' => $panierPerso, 'panier' => $panier, 'form' => $form->createView())); 
  }

  public function supprimerLigneDeCommandeAction()
  {
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
      $index = $request->request->get('index');
      $session = $this->get('session');
      if ($index == -1) {
        $reference = $request->request->get('reference'); 
        $session->set('quantiteTotale', $session->get('quantiteTotale') - $session->get($reference)->getQuantite());
        $session->remove($reference);
        $session->get('reference')->remove($reference);
      } else {
        $produitPerso = $session->get('produitPerso');
        $session->set('quantiteTotale', $session->get('quantiteTotale') - $produitPerso[$index]->getQuantite());
        $produitPerso[$index]->setDesignation('????');
        $session->set('produitPerso', $produitPerso);
      }
      return new Response($this->generatePanierHtml($session, 'panier'));
    }
    
    return $this->redirect($this->generateUrl('hb_hyperbrico_panier'));
  }

  public function modifierLigneDeCommandeAction()
  {
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
      $index = $request->request->get('index');
      $quantite = $request->request->get('quantite');
      $session = $this->get('session');
      if ($index == -1) {
        $reference = $request->request->get('reference');
        $session->set('quantiteTotale', $session->get('quantiteTotale') + ($quantite - $session->get($reference)->getQuantite()));
        $session->get($reference)->setQuantite($quantite);
      } else {
        $produitPerso = $session->get('produitPerso');
        $session->set('quantiteTotale', $session->get('quantiteTotale') + ($quantite - $produitPerso[$index]->getQuantite()));
        $produitPerso[$index]->setQuantite($quantite);
        $session->set('produitPerso', $produitPerso);
      }
      return new Response($this->generatePanierHtml($session, 'panier'));
    }
    
    return $this->redirect($this->generateUrl('hb_hyperbrico_panier'));
  }

  public function ajouterLigneDeCommandeAction(Catalogue $catalogue)
  {
  	$ligneDeCommande = new LigneDeCommande();
    $form = $this->createForm(new LigneDeCommandeType(), $ligneDeCommande, array('catalogue' => $catalogue, 'action' => $this->generateUrl('hb_hyperbrico_ajouterLigneDeCommande', array('id' => $catalogue->getId())), 'attr' => array('name' => 'ajoutProduit', 'class' => 'form-inline')));

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
        $form->bind($request);

        if ($form->isValid()) {
          $ref = $ligneDeCommande->getProduit()->getReference();
          $session = $this->get('session');

          $session->set('quantiteTotale', $session->get('quantiteTotale') + $ligneDeCommande->getQuantite());
          if (is_null($session->get($ref))) {
            $session->set($ref, $ligneDeCommande);
            if (is_null($session->get('reference'))) {
              $references = new \Symfony\Component\HttpFoundation\ParameterBag;
              $references->set($ref, $ref);
              $session->set('reference', $references);
            } else {
              $references = $session->get('reference');
              $references->set($ref, $ref);
              $session->set('reference', $references);
            }
          } else {
            $session->set($ref, $session->get($ref)->setQuantite($ligneDeCommande->getQuantite() + $session->get($ref)->getQuantite()));
          }
          return new Response($this->generatePanierHtml($session, 'catalogue'));
        }
        return new Response('error');
    }

    return $this->render('HBHyperbricoBundle:Commande:formulaireAjouterProduit.html.twig', array('form' => $form->createView()));
  }

  public function getProduitsAction()
  {
    $request = $this->getRequest();
    if ($request->getMethod() == 'GET' && $request->isXmlHttpRequest() && !is_null($request->query->get('page')) && !is_null($request->query->get('id'))) {
      $page = $request->query->get('page');
      $id = $request->query->get('id');
      $produits = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('HBHyperbricoBundle:Produit')
                       ->findProduitsParPage($page + 1, $id);
      $html = '';
      foreach ($produits as $produit) {
          $html = $html . '<option value=' . $produit->getId() . '>' . $produit->getReference() . ' - ' . $produit->getDesignation() . ' - ' . $produit->getPrix() . ' XPF' . '</option>';
      }          
      if (strcmp($html, '') == 0) {
        $html = '<option value="none">Aucun produit disponible sur cette page.</option>';
      }
      return new Response($html);
    }

    return new Response('Erreur.');
  }

  public function informationsAction()
  {
    $commande = new Commande;
    $session = $this->get('session');
    if (strcmp($this->generateUrl('hb_hyperbrico_tahitiCommande'), $this->container->get('request')->getRequestUri()) == 0) {
      $lieu = 'tahiti';
      $commande->setIle($lieu);
    } else {
      $lieu = 'iles';
    }
    if (!is_null($session->get('commande'))) {
      if ((strcmp($lieu, 'tahiti') == 0 && strcmp($session->get('commande')->getIle(), 'tahiti') == 0)
          || (strcmp($lieu, 'iles') == 0 && strcmp($session->get('commande')->getIle(), 'tahiti') != 0)) {
        $commande = $session->get('commande');
      }
    }

    $form = $this->createForm(($lieu == 'tahiti' ? new CommandeTahitiType : new CommandeIlesType), $commande, array('attr' => array('class' => 'form-horizontal')));

    $request = $this->get('request');
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
            $session->set('commande', $commande);

            return $this->redirect($this->generateUrl('hb_hyperbrico_confirmationCommande'));
        }
        $session->getFlashBag()->add('error', 'Les informations rentrées sont incorrectes.');
    }
    return $this->render('HBHyperbricoBundle:Commande:informations.html.twig', array('lieu' => $lieu, 'form' => $form->createView()));
  }

  public function confirmationAction()
  {
    $panier = array();
    $session = $this->get('session');
    $this->cleanProduitPerso($session);
    if (!is_null($session->get('reference'))) {
      foreach ($session->get('reference') as $ref) {
        $panier[] = $session->get($ref);
      }
    }

    $panierPerso = array();
    if (!is_null($session->get('produitPerso'))) {
      foreach ($session->get('produitPerso') as $prodPerso) {
        $panierPerso[] = $prodPerso;
      }
    }

    $commande = $session->get('commande');

    $form = $this->createFormBuilder()->getForm();
   
    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $commande->setNumeroCommande(str_pad($this->getDoctrine()->getManager()->getRepository('HBHyperbricoBundle:Commande')->countCommandes() + 1, 6, '0', STR_PAD_LEFT))
                   ->setNombreArticle((is_null($session->get('quantiteTotale')) ? 0 : $session->get('quantiteTotale')))
                   ->setToken($this->get('form.csrf_provider')->generateCsrfToken($this->container->getParameter('secret') . $commande->getNom() . $commande->getPrenom() . $commande->getNumeroMaison() . $commande->getNumeroVini() . $commande->getLieuHabitation()));
          $em = $this->getDoctrine()->getManager();
          $em->persist($commande);
          $em->flush();
          foreach ($panier as $ligneDeCommande) {
            $ligneDeCommande->setCommande($commande);
            $ligneDeCommande->setType('Officiel');
            $ligneDeCommande->completeChamps();
            $em->persist($ligneDeCommande);
          }
          foreach ($panierPerso as $ligneDeCommande) {
            $ligneDeCommande->setCommande($commande);
            $ligneDeCommande->setType('Perso');
            $em->persist($ligneDeCommande);
          }
          $em->flush();
          $mailer = $this->get('swiftmailer.mailer.immediate');
          $html = $this->renderView('HBHyperbricoBundle:Commande:commandeMail.html.twig', array('information' => $commande, 'panier' => $panier, 'panierPerso' => $panierPerso));
          $plain = $this->renderView('HBHyperbricoBundle:Commande:commandeMail.txt.twig', array('information' => $commande, 'panier' => $panier, 'panierPerso' => $panierPerso));
          $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de facture proforma')
                        ->setFrom(array($this->container->getParameter('mailer_user') => 'HYPER BRICO'))
                        ->setTo($this->container->getParameter('mail_brico'))
                        ->setBody($plain, 'text/plain')
                        ->addPart($html, 'text/html');
          $mailer->send($message);
          $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de facture proforma')
                        ->setFrom(array($this->container->getParameter('mailer_user') => 'HYPER BRICO'))
                        ->setTo($commande->getMail()) 
                        ->setBody($plain, 'text/plain')
                        ->addPart($html, 'text/html');
          $mailer->send($message);

          $session->clear();
          $session->getFlashBag()->add('success', 'Demande bien envoyée.');

          return $this->redirect($this->generateUrl('hb_hyperbrico_recapitulationCommande', array('id' => $commande->getId(), 'token' => $commande->getToken())));
        }
    }

    return $this->render('HBHyperbricoBundle:Commande:confirmation.html.twig', array('form' => $form->createView(), 'panierPerso' => $panierPerso, 'panier' => $panier, 'information' => $session->get('commande'))); 
  }

  public function recapitulationAction(Commande $commande, $token)
  {
    if (strcmp($commande->getToken(), $token) != 0) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }

    return $this->render('HBHyperbricoBundle:Commande:recapitulation.html.twig', array('commande' => $commande));
  }

  public function gererAction(Commande $commande)
  {
    $form = $this->createForm(new CommandeEditType(), $commande, array('attr' => array('class' => 'form-horizontal')));
    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
        $form->bind($request);
        
        if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($commande);
              $em->flush();

              $this->get('session')->getFlashBag()->add('success', 'Demande bien modifiée');

              return $this->redirect($this->generateUrl('hb_hyperbrico_recapitulationCommande', array('id' => $commande->getId(), 'token' => $commande->getToken())));
        }
        $this->get('session')->getFlashBag()->add('error', 'Votre demande est incorrecte');
    }

    return $this->render('HBHyperbricoBundle:Commande:gerer.html.twig', array('form' => $form->createView(), 'commande' => $commande));
  }

  private function generatePanierHtml($session, $from)
  {
    $html = '<a class="dropdown-toggle' . ($from == 'panier' ? ' active' : '') . '" href="' . $this->generateUrl('hb_hyperbrico_panier') . '"><i class="icon-shopping-cart"></i> Panier ' . ($session->get('quantiteTotale') == 0 ? '(0)' : '(' . $session->get('quantiteTotale') . ')') . ' <span class="caret"></span></a>';
    $html = $html . '<ul class="dropdown-menu pull-right">';
    $index = 0;
    if (!is_null($session->get('reference'))) {
      foreach ($session->get('reference') as $ligne) {
        if ($index < 5) {
          $html = $html . '<li><a href="#">' . substr($session->get($ligne)->getProduit()->getDesignation(), 0, 40) . (strlen($session->get($ligne)->getProduit()->getDesignation()) > 40 ? '...' : '') . '<br />Quantité: ' . $session->get($ligne)->getQuantite() . '</a></li>';
        }
        $index++;
      }
    }
    if (!is_null($session->get('produitPerso'))) {
      foreach ($session->get('produitPerso') as $ligne) {
        if ($index < 5) {
          $html = $html . '<li><a href="#">' . substr($ligne->getDescription(), 0, 39) . (strlen($ligne->getDescription()) > 40 ? '...' : '') . '<br />Quantité: ' . $ligne->getQuantite() . '</a></li>';
        }
        $index++;
      }
    } 
    if ($index == 0) {
      $html = $html . '<li><a href="#">Votre panier est vide.</a></li>';
    }
    $html = $html . '<li id="btn-panier"><a class="btn btn-hyperbrico" href="' . $this->generateUrl('hb_hyperbrico_panier') . '">Voir le panier ' . ($session->get('quantiteTotale') ? '(' . $session->get('quantiteTotale') . ' article' . ($session->get('quantiteTotale') > 1 ? 's' : '') . ')' : '') . '</a>';
    $html = $html . '</ul>';
    return $html;
  }

  private function cleanProduitPerso($session) {
    if (!is_null($session->get('produitPerso'))) {
      $produitPerso = $session->get('produitPerso');
      $produitPersoTemp = $produitPerso;
      $produitPerso = array();
      foreach ($produitPersoTemp as $prodPerso) {
        if ($prodPerso->getDesignation() != '????') {
          array_push($produitPerso, $prodPerso);
        }
      }
      $session->set('produitPerso', $produitPerso);
    }
  }
}
