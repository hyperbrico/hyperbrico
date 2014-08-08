<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommandeIlesType extends CommandeTahitiType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder->add('ile', 'text', array('label' => 'Île', 'label_attr' => array('class' => 'control-label')))
            ->add('nomBateau', 'text', array('label' => 'Nom du bateau', 'label_attr' => array('class' => 'control-label')))
            ->add('dateDepartBateau', 'date', array('label' => 'Départ du bateau', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'datepicker'), 'label_attr' => array('class' => 'control-label')))
            ->add('fretADestination', 'choice', array('choices' => array('Oui' => 'Oui', 'Non' => 'Non'), 'label' => 'Fret à destination', 'label_attr' => array('class' => 'control-label')));
  }
 
  public function getName()
  {
    return 'hb_hyperbricobundle_commandeilestype';
  }
}