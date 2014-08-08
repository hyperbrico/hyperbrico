<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommandeTahitiType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('nom', 'text', array('label' => 'Nom', 'label_attr' => array('class' => 'control-label')))
            ->add('prenom', 'text', array('label' => 'Prénom', 'label_attr' => array('class' => 'control-label')))
            ->add('numeroMaison', 'text', array('label' => 'Téléphone domicile', 'label_attr' => array('class' => 'control-label')))
            ->add('numeroVini', 'text', array('label' => 'Téléphone portable', 'label_attr' => array('class' => 'control-label')))
            ->add('mail', 'email', array('label' => 'Votre adresse e-mail', 'label_attr' => array('class' => 'control-label')))
            ->add('informationComplementaire', 'textarea', array('attr' => array('rows' => 5, 'class' => 'input-xxlarge', 'placeholder' => 'Vous pouvez entrer des remarques...'), 'required' => false, 'label' => 'Informations complémentaires', 'label_attr' => array('class' => 'control-label')))
            ->add('lieuHabitation', 'text', array('label' => 'Adresse', 'label_attr' => array('class' => 'control-label')));
  }
 
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'HB\HyperbricoBundle\Entity\Commande'
    ));
  }
 
  public function getName()
  {
    return 'hb_hyperbricobundle_commandetahititype';
  }
}