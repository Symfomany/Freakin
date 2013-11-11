<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DemandeurStep3Type extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $minDob = new \DateTime('-18 Years');
        $maxDob = new \DateTime('-90 Years');

        $builder
            ->add('lastname', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Prénom')))
            ->add('firstname', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Nom')))
            ->add('metier', null, array('required' => 'required'))
            ->add('ville', null, array('attr' => array('pattern' => '.{2,}',  'placeholder' => 'Ville')))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Email')))
            ->add('dob', 'date', array( 'format' => 'dd - MMMM - yyyy','widget' => 'choice', 'years' => range($minDob->format('Y'), $maxDob->format('Y'))))
            ->add('cgu', 'checkbox', array(
                'required' => false,
                'mapped' => false
            ))
            ->add('experiences', 'collection', array('type' => new JobsType(),'allow_add' => true, 'allow_delete' => true,'by_reference' => false))
            ->add('formations', 'collection', array('type' => new FormationsType(),'allow_add' => true, 'allow_delete' => true,'by_reference' => false))
            ->add('certificates', 'collection', array('type' => new CertificatesType(),'allow_add' => true, 'allow_delete' => true,'by_reference' => false))
            ->add('hobbies', 'collection', array('type' => new HobbiesType(),'allow_add' => true, 'allow_delete' => true,'by_reference' => false));

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
        return '';
    }

}