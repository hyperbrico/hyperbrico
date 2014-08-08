<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('produits', 'collection', array('type' => new ProduitType(), 'allow_add' => true, 'allow_delete' => true))
                ->add('csv', 'file', array('required' => false, 'label_attr' => array('class' => 'control-label')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\Produits'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_produitstype';
    }
}