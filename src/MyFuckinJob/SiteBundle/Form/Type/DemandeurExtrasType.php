<?php
namespace MyFuckinJob\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use MyFuckinJob\SiteBundle\Form\Type\ExperienceType;

class DemandeurExtrasType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('experiences', 'collection', array(
                'type' => new ExperienceType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('etude', 'choice', array(
                'attr' => array('class' => 'select'),
                'choices' =>
                array(
                    '1' => "Bac non validé",
                    '2' => "Lycée, Niveau Bac",
                    '3' => "Bac Professionnel, BEP, CAP",
                    '4' => "DUT, BTS, Bac + 2",
                    '5' => "Diplôme non validé",
                    '6' => "Licence, Bac + 3",
                    '7' => "Maîtrise, IEP, IUP, Bac + 4",
                    '8' => "DESS, DEA, Grandes Ecoles, Bac + 5",
                    '9' => "Doctorat, 3ème cycle",
                    '10' => "Expert, Recherche"
                ),
                'required'    => true,
             ))
            ->add('statut', 'choice', array(
            'attr' => array('class' => 'select'),
            'choices' =>
            array(
                '1' => "Etudiant",
                '2' => "Jeune Diplômé",
                '3' => "Junior",
                '4' => "Confirmé / Senior",
                '5' => "Responsable d'équipe",
                '6' => "Responsable de Département",
                '7' => "Dirigeant/Entrepreneur",
            ),
            'required'    => true,
        ))
            ->add('permis', 'checkbox', array(
                'required' => false,
            ))
            ->add('description', 'textarea', array('attr' => array(  'placeholder' => 'Description de votre profil', 'cols' => 60,'rows' => 3,'pattern' => '.{4,350}', 'maxlength' => 350 ),  'required' => false))
            ->add('skill', 'entity', array(
            'class' => 'MyFuckinJob\SiteBundle\Entity\Skill',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.title', 'ASC');
            },
            'multiple'  => true,
            'mapped' => false,
            'attr' => array('placeholder' => 'ex:Rugby', 'class' => 'multipleselect, input-xxlarge'),
            'required' => false
        ));

    }

    public function getDefaultOptions(array $options) {
        return array(
            'intention' => 'suscribe2',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        );
    }

    public function getName() {
        return 'inscription_step3';
    }
}
