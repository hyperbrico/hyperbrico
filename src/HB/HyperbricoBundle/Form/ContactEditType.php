<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('statut', 'choice', array('choices' => array('Non traité' => 'Non traité', 'Traité' => 'Traité'), 'label_attr' => array('class' => 'control-label')));
  }
 
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'HB\HyperbricoBundle\Entity\Contact'
    ));
  }
 
  public function getName()
  {
    return 'hb_hyperbricobundle_contactedittype';
  }
}