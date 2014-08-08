<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogueEditType extends CatalogueType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder->remove('file');
    $builder->add('file', 'file', array('required' => false, 'label' => 'Le fichier pdf', 'label_attr' => array('class' => 'control-label')));
  }
 
  public function getName()
  {
    return 'hb_hyperbricobundle_catalogueedittype';
  }
}