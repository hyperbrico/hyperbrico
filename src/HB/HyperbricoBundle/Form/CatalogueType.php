<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogueType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('datePubDebut', 'date', array('label' => 'Début de publication', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'datepicker'), 'label_attr' => array('class' => 'control-label')))
            ->add('datePubFin', 'date', array('label' => 'Fin de publication', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'datepicker'), 'label_attr' => array('class' => 'control-label')))
            ->add('dateDebut', 'date', array('label' => 'Début de validité', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'datepicker'), 'label_attr' => array('class' => 'control-label')))
            ->add('dateFin', 'date', array('label' => 'Fin de validité', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'datepicker'), 'label_attr' => array('class' => 'control-label')))
            ->add('titre', 'text', array('label_attr' => array('class' => 'control-label')))
            ->add('description', 'textarea', array('attr' => array('class' => 'input-xxlarge', 'rows' => 6), 'label_attr' => array('class' => 'control-label')))
            ->add('file', 'file', array('label' => 'Le fichier pdf', 'label_attr' => array('class' => 'control-label')));
  }
 
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'HB\HyperbricoBundle\Entity\Catalogue'
    ));
  }
 
  public function getName()
  {
    return 'hb_hyperbricobundle_cataloguetype';
  }
}