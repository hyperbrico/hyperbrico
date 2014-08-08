<?php

namespace HB\HyperbricoBundle\Form\EventListener;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProduitDataSubscriber implements EventSubscriberInterface 
{ 
  private $factory;
  private $id;

  public function __construct(FormFactoryInterface $factory, $id)
  {
      $this->factory = $factory;
      $this->id = $id;
  }
   
  public static function getSubscribedEvents()
  {
      return array(
          FormEvents::PRE_SUBMIT => 'preSubmit',
      );
  }
   
  public function preSubmit(FormEvent $event)
  {
      $data = $event->getData();
      $form = $event->getForm();

      if (null === $data) {
          return;
      }

      $page = $data['page'];
      $id = $this->id;
       
      $form->add($this->factory->createNamed(
               'produit',
               'entity', 
               null,
               array('query_builder'=> function(EntityRepository $repository) use ($page, $id) {
                                                                   return $repository->findProduitsParPageQueryBuilder($page + 1, $id);
                                                                 },
                                               'class' =>'HBHyperbricoBundle:Produit',
                                               'auto_initialize' => false,
                                               'attr' => array('class' => 'input-xxlarge')                                    
                      )
      ));
  }        
}