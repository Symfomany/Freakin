<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CertificatesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('title', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Titre de votre job')))
            ->add('description', null, array('required' => true,'attr' => array('pattern' => '.{2,}',  'placeholder' => 'Description de votre job')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MyFuckinJob\SiteBundle\Entity\Certificat',
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