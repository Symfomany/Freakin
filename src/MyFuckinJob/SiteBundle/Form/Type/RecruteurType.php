<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RecruteurType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
            ->add('name', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom de votre société')))
            ->add('ville', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Ville')))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Email')))
            ->add('tel', null, array('attr' => array('placeholder' => '06XXXXXXXX', 'class' => 'input-medium',  'maxlength' => 17), 'required' => false))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_name' => 'mdp',
                'second_name' => 'mdp_conf',
                'invalid_message' => "Le mot de passe n'est pas le même",
                'error_bubbling' => true,
                'first_options'  => array('label' => 'Mot de passe' , 'attr' => array('placeholder' => 'Au moins 6 caractères', 'pattern' => '.{6,}')),
                'second_options' => array('label' => 'Confirmation du mot de passe',   'attr' => array('placeholder' => 'Retaper votre mot de passe', 'pattern' => '.{6,}'))
            ))
            ->add('cgu', 'checkbox', array(
                'required' => false,
                'mapped' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Recruteur',
                'intention' => 'inscription_recruteur',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
            )
        );
    }

    public function getName() {
        return 'inscription_recruteur';
    }

}