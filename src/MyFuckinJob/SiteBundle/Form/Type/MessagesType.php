<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MessagesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('title', 'text', array('attr' => array('class' => 'form-control')))
            ->add('description', 'textarea', array('attr' => array('class' => 'form-control')));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Document\Messages',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
            )
        );
    }

    public function getName() {
        return 'message';
    }

}