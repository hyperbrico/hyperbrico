<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LigneDeCommandePersoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add('description', 'text', array('attr' => array('class' => 'span5', 'placeholder' => 'ex: Pot de peinture blanche mat 5 litres')))
              ->add('reference', 'text', array('attr' => array('maxlength' =>10, 'class' => 'input-small'), 'required' => false))
              ->add('marque', 'text', array('attr' => array('class' => 'input-small'), 'required' => false))
              ->add('quantite', 'number', array('label' => 'Quantité', 'attr' => array('value' => 1, 'class' => 'input-mini')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\LigneDeCommande'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_lignedecommandepersotype';
    }
}