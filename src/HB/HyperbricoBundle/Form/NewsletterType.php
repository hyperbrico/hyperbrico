<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('objet', 'text', array('label_attr' => array('class' => 'control-label')))
                ->add('contenu', 'textarea', array('label_attr' => array('class' => 'control-label')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\Newsletter'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_newslettertype';
    }
}