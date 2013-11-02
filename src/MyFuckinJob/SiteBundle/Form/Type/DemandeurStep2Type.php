<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DemandeurType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $minDob = new \DateTime('-18 Years');
        $maxDob = new \DateTime('-90 Years');

        $builder
            ->add('lastname', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Prénom')))
            ->add('firstname', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom')))
            ->add('ville', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Ville')))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Email')))
            ->add('tel', null, array('attr' => array('placeholder' => '06XXXXXXXX', 'class' => 'input-medium',  'maxlength' => 17), 'required' => false))
            ->add('dob', 'date', array( 'format' => 'dd - MMMM - yyyy','widget' => 'choice', 'years' => range($minDob->format('Y'), $maxDob->format('Y'))))
            ->add('optin','checkbox', array('attr' => array(),'required' => false))
            ->add('cgu', 'checkbox', array(
                'required' => false,
                'mapped' => false
            ))
            ->add('password', 'repeated', array(
                    'type' => 'password',
                    'first_name' => 'mdp',
                    'second_name' => 'mdp_conf',
                    'invalid_message' => "Le mot de passe n'est pas le même",
                    'error_bubbling' => true,
                    'first_options'  => array('label' => 'Mot de passe' ,   'attr' => array('placeholder' => 'Au moins 6 caractères', 'pattern' => '.{6,}')),
                    'second_options' => array('label' => 'Mot de passe (resaissez-le)',   'attr' => array('placeholder' => 'Retapez votre mot de passe', 'pattern' => '.{6,}'))
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Demandeur',
                'intention' => 'test',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
            )
        );
    }

    public function getName() {
        return 'inscription';
    }

}