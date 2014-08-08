<?php

namespace HB\HyperbricoBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HB\HyperbricoBundle\Form\EventListener\ProduitDataSubscriber;

class LigneDeCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $id = $options['catalogue']->getId();
      $builder->add('page', 'hidden', array('label' => false, 'attr' => array('value' => 0)))
              ->add('produit', 'entity', array('query_builder'=> function(EntityRepository $repository) use ($id) {
                                                                   return $repository->findProduitsParPageQueryBuilder(1, $id);
                                                                 },
                                               'class' =>'HBHyperbricoBundle:Produit',
                                               'attr' => array('class' => 'input-xxlarge')
                                              ))
              ->add('quantite', 'number', array('label' => 'Quantité', 'attr' => array('value' => 1, 'class' => 'span1')));

      $subscriber = new ProduitDataSubscriber($builder->getFormFactory(), $id);
      $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\LigneDeCommande',
          'catalogue' => 'HB\HyperbricoBundle\Entity\Catalogue'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_lignedecommandetype';
    }
}