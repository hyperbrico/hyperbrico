<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HB\HyperbricoBundle\Entity\Catalogue;
use HB\HyperbricoBundle\Entity\Produit;
use HB\HyperbricoBundle\Entity\Produits;
use HB\HyperbricoBundle\Form\ProduitType;
use HB\HyperbricoBundle\Form\ProduitsType;

class ProduitController extends Controller
{
    public function gererAction(Catalogue $catalogue)
    {
      $produits = new Produits;
      $produits->setProduits($catalogue->getProduits());
      $form = $this->createForm(new ProduitsType(), $produits, array('attr' => array('class' => 'form-horizontal')));
      $errors = array();
      $errorsCsv = array();
      $allReference = array();
      $hasErrors = false;
      $request = $this->getRequest();
      $originalProduits = array();
      foreach ($catalogue->getProduits() as $produit) {
        $originalProduits[] = $produit;
      }

      if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if (!is_null($produits->getCsv())) {
                  foreach ($produits->getProduits() as $produit) {
                    $produit->setCatalogue($catalogue);
                    $em->persist($produit);
                  }
                  $em->flush();
                  foreach ($produits->getProduits() as $produit) {
                    $em->remove($produit);
                  }
                  foreach ($originalProduits as $produit) {
                    $em->remove($produit);
                  }
                  $em->flush();
                  $produits->clearProduits();
                  $this->getProduitsFromCsv($produits, $catalogue);
                  $errorsCsv = $this->get('validator')->validate($produits);
                  if (count($errorsCsv) != 0) {
                    $hasErrors = true;
                  } else {
                    $errorsCsv = array();
                    foreach ($produits->getProduits() as $produit) {
                      if (!$this->checkReferences($allReference, $produit, $errorsCsv)) {
                        $allReference[] = $produit->getReference();
                      } else {
                        $hasErrors = true;
                      }
                    }
                  }
                } else {
                  foreach ($produits->getProduits() as $produit) {
                    $produit->setCatalogue($catalogue);
                    $error = $this->get('validator')->validate($produit);
                    if (count($error) != 0) {
                      $errors[] = $error;
                      $hasErrors = true;
                    } else if (!$this->checkReferences($allReference, $produit, $errors)) {
                      $allReference[] = $produit->getReference();
                      $errors[] = "";
                    } else {
                      $hasErrors = true;
                    }
                  }
                }
                if (!$hasErrors) {
                  foreach ($catalogue->getProduits() as $produit) {
                      foreach ($originalProduits as $key => $toDel) {
                          if ($toDel->getId() === $produit->getId()) {
                              unset($originalProduits[$key]);
                          }
                      }
                  }

                  foreach ($originalProduits as $produit) {
                    $em->remove($produit);
                  }
                  foreach ($produits->getProduits() as $produit) {
                    $produit->setCatalogue($catalogue);
                    $em->persist($produit);
                  }
                  $em->flush();

                  $this->get('session')->getFlashBag()->add('success', 'Produits bien modifiés.');

                  return $this->redirect($this->generateUrl('hb_hyperbrico_voir' . ucfirst($catalogue->getType()) . 's', array('slug' => $catalogue->getSlug())));
                }
          }
          $this->get('session')->getFlashBag()->add('error', 'Vos produits sont incorrects.');
      }
      return $this->render('HBHyperbricoBundle:Produit:gerer.html.twig', array('form' => $form->createView(), 'catalogue' => $catalogue, 'errorsCsv' => $errorsCsv, 'errors' => $errors, 'type' => $catalogue->getType()));
    }

    private function getProduitsFromCsv($produits, $catalogue)
    {
      if (($handle = fopen($produits->getCsv(), "r")) !== FALSE) {
        if (($header = fgetcsv($handle, 0, ";")) !== FALSE) {
          for ($i = 0; $i < count($header); $i++) {
            $header[$i] = $this->cleanString($header[$i]);
          }          
          $header = array_flip($header);
          while (($champ = fgetcsv($handle, 0, ";")) !== FALSE) {
            $produit = new Produit;
            $produit->setReference(str_replace(' ', '', array_key_exists('code', $header) ? $champ[$header['code']] : $champ[$header['reference']]))
                    ->setDesignation($champ[$header['designation']])
                    ->setMarque(array_key_exists('marque', $header) ? $champ[$header['marque']] : NULL)
                    ->setPrix(str_replace(' ', '', $champ[$header['prix']]))
                    ->setTva(array_key_exists('tva', $header) ? $champ[$header['tva']] : NULL)
                    ->setDescription(array_key_exists('description', $header) ? $champ[$header['description']] : NULL)
                    ->setPage(str_replace(' ', '', $champ[$header['page']]))
                    ->setCatalogue($catalogue);
            $produits->addProduit($produit);
          }
          fclose($handle);
        }
      }
    }

    private function checkReferences($allReference, $produit, &$errors)
    {
      $hasChanged = false;
      foreach ($allReference as $ref) {
        if ($ref == $produit->getReference()) {
          $hasChanged = true;
          $errors[] = "erreur référence:Deux produits ne peuvent pas avoir la même référence.";
        }
      }
      return $hasChanged;
    }

    private function cleanString($str, $charset='ISO-8859-1')
    {
      $str = strtolower($str);
      $str = htmlentities($str, ENT_NOQUOTES, $charset);
      $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
      $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
      $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
      return $str;
    }

}
