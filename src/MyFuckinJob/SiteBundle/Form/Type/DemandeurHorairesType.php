<?php

namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DemandeurHorairesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $minDob = new \Datetime('now');
        $maxDob = new \Datetime('now');
        $minDob->modify(('-12 years'));
        $maxDob->modify(('-90 years'));
        $horaireAm = array(
            '6' => '6h00',
            '6-3' => '6h30',
            '7' => '7h00',
            '7-3' => '7h30',
            '8' => '8h00',
            '8-3' => '8h30',
            '9' => '9h00',
            '9-3' => '9h30',
            '10' => '10h00',
            '10-3' => '10h30',
            '11' => '11h00',
            '11-3' => '11h30',
            '12' => '12h00'
        );
        $horairePm = array(
            '12-3' => '12h30',
            '13' => '13h00',
            '13-3' => '13h30',
            '14' => '14h00',
            '14-3' => '14h30',
            '15' => '15h00',
            '15-3' => '15h30',
            '16' => '16h00',
            '16-3' => '16h30',
            '17' => '17h00',
            '17-3' => '17h30',
            '18' => '18h00',
            '18-3' => '18h30',
            '19' => '19h00',
            '19-3' => '19h30',
            '20' => '20h00',
            '20-3' => '20h30',
            '21' => '21h00',
            '21-3' => '21h30',
            '22' => '22h00',
            '22-3' => '22h30',
            '23' => '23h00',
            '23-3' => '23h30',
            '24' => '00h00',
            '24-3' => '00h30',
            '1' => '1h00',
            '1-3' => '1h30',
            '2' => '2h00',
            '2-3' => '2h30',
            '3' => '3h00',
        );

        $builder
            ->add('same', 'checkbox', array(
            'required' => false,
            'mapped' => false,
            ))
            ->add('lun_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('lun_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:'

            ))
            ->add('lun_begin_pm', 'choice',
             array('choices' =>
             $horairePm,
                    'multiple' => false,
                    'required' => false,
                    'empty_value' => 'De:',

             ))
            ->add('lun_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',

            ))


            ->add('mar_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',

            ))
            ->add('mar_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('mar_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('mar_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))


            ->add('mer_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('mer_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('mer_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('mer_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))



            ->add('jeu_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('jeu_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('jeu_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('jeu_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))

            ->add('ven_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('ven_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('ven_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('ven_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))

            ->add('sam_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('sam_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('sam_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('sam_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))


            ->add('dim_begin_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('dim_end_am', 'choice',
            array('choices' =>
            $horaireAm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
            ->add('dim_begin_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'De:',
            ))
            ->add('dim_end_pm', 'choice',
            array('choices' =>
            $horairePm,
                'multiple' => false,
                'required' => false,
                'empty_value' => 'A:',
            ))
        ->add('phrase', 'textarea', array('attr' => array('cols' => 80,'rows' => 5,'pattern' => '.{4,350}', 'maxlength' => 350 ),  'required' => false));


    }

    public function getDefaultOptions(array $options) {
        return array(
            'intention' => 'suscribe2',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        );
    }

    public function getName() {
        return 'inscription_step2';
    }

}
