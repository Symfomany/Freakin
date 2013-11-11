<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DemandeurType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('metier')
            ->add('captcha', 'captcha', array(
                'width' => 200,
                'height' => 50,
                'length' => 6
            ))
            ->add('level', 'choice', array(
                'choices'   => array(1 => 'Amateur', 2 => 'Initié', 3 => 'Confirmé',4 => 'Professionnel', 5 => 'Inhumain', 6 => 'Je ne sais pas' ),
                'required'  => false,
            ))
            ->add('isShop','choice', array(
            'choices'   => array(0 => 'Non', 1 => 'Oui'),
            'required'  => true,
            'expanded'  => true,
            ))
            ->add('optin')
            ->add('urlShop', null, array('required' => false,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'http://')))
            ->add('password', 'password', array('required' => true,'attr' => array('pattern' => '.{5,}',  'placeholder' => 'Mot de passe')))
            ->add('entreprise', null, array('required' => false,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Entreprise')))
            ->add('lastname', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Prénom')))
            ->add('ville', null, array('required' => false,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Ville')))
            ->add('firstname', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom')))
            ->add('tel', null, array('required' => false,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Téléphone')))
            ->add('description', null, array('required' => false,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Commentaire')))
            ->add('email', 'email', array('required' => true, 'attr' => array('placeholder' => 'Email')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Demandeur',
                'intention' => 'test',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'validation_groups' => array('registration'),
            )
        );
    }

    public function getName() {
        return '';
    }

}