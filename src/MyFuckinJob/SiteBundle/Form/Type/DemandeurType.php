<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DemandeurType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('lastname', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Prénom')))
            ->add('firstname', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom')))
            ->add('email', 'email', array('required' => true, 'attr' => array('placeholder' => 'Email')))
            ->add('password', 'password', array("attr" => array("placeholder" => 'Votre mot de passe')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Demandeur',
                'intention' => 'test',
                'csrf_protection' => true,
                'csrf_field_name' => '_token'
            )
        );
    }

    public function getName() {
        return '';
    }

}