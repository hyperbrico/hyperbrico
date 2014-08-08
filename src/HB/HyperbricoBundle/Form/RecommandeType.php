<?php

namespace HB\HyperbricoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('label' => 'Votre nom', 'label_attr' => array('class' => 'control-label')))
                ->add('mail', 'email', array('label' => 'Votre adresse e-mail', 'label_attr' => array('class' => 'control-label')))
                ->add('mailAmi', 'email', array('label' => 'L\'adresse e-mail de votre ami(e)', 'label_attr' => array('class' => 'control-label')))
                ->add('sujet', 'text', array('label_attr' => array('class' => 'control-label')))
                ->add('message', 'textarea', array('attr' => array('class' => 'input-xxlarge', 'rows' => 5), 'label_attr' => array('class' => 'control-label')))
                ->add('recaptcha', 'genemu_recaptcha', array('label' => 'Vérification humaine', 'label_attr' => array('class' => 'control-label')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'HB\HyperbricoBundle\Entity\Recommande'
        ));
    }

    public function getName()
    {
        return 'hb_hyperbricobundle_recommandetype';
    }
}