<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reference', 'text', array('attr' => array('class' => 'input-mini')))
                ->add('designation', 'text', array('attr' => array('class' => 'input-large')))
                ->add('prix', 'number', array('attr' => array('class' => 'input-mini')))
                ->add('marque', 'text', array('required' => false, 'attr' => array('class' => 'input-mini')))
                ->add('tva', 'number', array('required' => false, 'attr' => array('class' => 'input-mini')))
                ->add('page', 'number', array('attr' => array('class' => 'input-mini')))
                ->add('description', 'textarea', array('required' => false, 'attr' => array('class' => 'input-large', 'rows' => 1)));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\Produit'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_produittype';
    }
}