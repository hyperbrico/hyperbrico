<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HB\HyperbricoBundle\Entity\Contact;
use HB\HyperbricoBundle\Form\ContactType;
use HB\HyperbricoBundle\Form\ContactEditType;

class ContactController extends Controller
{
	public function indexAction()
	{
		$contact = new Contact();
		$form = $this->createForm(new ContactType(), $contact, array('attr' => array('class' => 'form-horizontal')));

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
        if (is_null($contact->getTelephone())) {
          $contact->setTelephone('Non renseigné');
        }
				$hasSent = $this->get('hb_sendNewsletter')->sendMail('info@hyperbrico.pf', $contact, 'HBHyperbricoBundle:Contact:contactMail', false);

				if ($hasSent) {
					$this->get('session')->getFlashBag()->add('success', 'Mail bien envoyé.');
					return $this->redirect($this->generateUrl('hb_hyperbrico_contact'));
				} else {
					$this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'envoie du mail.');
					return $this->render('HBHyperbricoBundle:Contact:index.html.twig', array('form' => $form->createView()));
				}
			}
			$this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'envoie du mail.');
		}

		return $this->render('HBHyperbricoBundle:Contact:index.html.twig', array('form' => $form->createView()));
	}

	public function voirMailAction(Contact $contact, $token) 
  {
    if (strcmp($contact->getToken(), $token) != 0) {
      return $this->redirect($this->generateUrl('hb_hyperbrico_accueil'));
    }

    return $this->render('HBHyperbricoBundle:Contact:voir.html.twig', array('contact' => $contact));
  }

  public function adminAction()
  {
    $contacts = $this->getDoctrine()
	                 ->getManager()
	                 ->getRepository('HBHyperbricoBundle:Contact')
	                 ->findBy(array(), array('dateEnvoi'=>'desc'));
                 
      return $this->render('HBHyperbricoBundle:Contact:admin.html.twig', array('contacts' => $contacts));
  }

  public function gererAction(Contact $contact)
  {
    $form = $this->createForm(new ContactEditType(), $contact, array('attr' => array('class' => 'form-horizontal')));
    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
        $form->bind($request);
        
        if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($contact);
              $em->flush();

              $this->get('session')->getFlashBag()->add('success', 'Contact bien modifié');

              return $this->redirect($this->generateUrl('hb_hyperbrico_adminContact'));
        }
        $this->get('session')->getFlashBag()->add('error', 'Formulaire incorrect');
    }

    return $this->render('HBHyperbricoBundle:Contact:gerer.html.twig', array('form' => $form->createView(), 'contact' => $contact));
  }

}
