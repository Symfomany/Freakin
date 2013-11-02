<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('lieu', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Ville, Département...')))
            ->add('activity', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom, métier, tags...')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'intention' => 'test',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
            )
        );
    }

    public function getName() {
        return 'search';
    }

}