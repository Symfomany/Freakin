<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ExperienceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('title', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom du post')))

            ->add('description', 'textarea', array('attr' => array(  'placeholder' => 'Description du post', 'cols' => 60,'rows' => 3,'pattern' => '.{4,350}', 'maxlength' => 350 ),  'required' => false));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Experience',
                'intention' => 'inscription',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
            )
        );
    }

    public function getName() {
        return 'experience';
    }

}