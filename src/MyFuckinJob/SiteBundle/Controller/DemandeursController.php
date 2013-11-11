<?php

namespace MyFuckinJob\SiteBundle\Controller;

use MyFuckinJob\SiteBundle\Form\Type\DemandeurStep3Type;
use MyFuckinJob\SiteBundle\Form\Type\MetierType;
use MyFuckinJob\SiteBundle\Form\Type\MoreThingType;
use Symfony\Component\DependencyInjection\ContainerAware;
use MyFuckinJob\SiteBundle\Entity\Demandeur;
use MyFuckinJob\SiteBundle\Form\Type\DemandeurType;
use MyFuckinJob\SiteBundle\Form\Type\RemisesType;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\RedirectResponse;


use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Image;


use Imagine\Gd\Imagine;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;


use Doctrine\Common\Util\Debug as Debug;


class DemandeursController extends ContainerAware
{


    /**
     * Homepage
     */
    public function presentationAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:presentation.html.twig', array());
    }


    /**
     * Homepage
     */
    public function homeAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:home.html.twig', array());
    }


    /**
     * Homepage connectée
     * @Secure(roles="ROLE_USER")
     */
    public function homeConnectedAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:home_connected.html.twig', array());
    }


    /**
     * Tchat
     */
    public function tchatAction()
    {
//        $array = array(
//            'id' => 15,
//            'firstname' => 'Julien',
//            'lastname' => 'Boyer',
//            'oid' => 15
//        );
//
//        $notification = new Notifications();
//        $notification->setAction(3);
//        $notification->setTitre('Rdv');
//        $notification->setContent('Okay sa va!');
//
//        $dm = $this->container->get('doctrine_mongodb')->getManager();
//        $dm->persist($notification);
//        $dm->flush();

        $dm = $this->container->get('doctrine_mongodb')
            ->getManager();

        $notifications = $dm->getRepository('MyFuckinJobSiteBundle:Notifications')
            ->findAll();


//        foreach($notifications as $notification){
//            $dm->remove($notification);
//            $dm->flush();
//        }
//
//        exit(Debug::dump($notifications));

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:tchat.html.twig',
            array(
                'notifications' => $notifications,
            )
        );
    }


    /**
     * Tchat
     */
    public function interfaceAction()
    {
        $dm = $this->container->get('doctrine_mongodb')
            ->getManager();

        $notifications = $dm->getRepository('MyFuckinJobSiteBundle:Notifications')
            ->findAll();

        $tests = $dm->getRepository('MyFuckinJobSiteBundle:Test')
            ->findAll();

//        foreach($tests as $notification){
//           print $notification->getQuestion();
//        }
//
//        exit(Debug::dump($tests));

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:interface.html.twig',
            array(
                'notifications' => $notifications,
                'tests' => $tests,
            )
        );
    }

    /**
     * Profil du demandeurs
     */
    public function profilAction($id, $firstname = null, $lastname = null)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($id);
        if (!$user || $user->getEnabled() === false) {
            $this->session->getFlashBag()->add('error', 'Profil introuvable ou désactivé');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:profil.html.twig', array('user' => $user));
    }

    /**
     * Inscription d'un demandeur
     */
    public function inscriptionAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $session = $request->getSession();

        $user = $this->container->get('security.context')->getToken()->getUser();
//        if ($user != "anon.") {
//            $this->session->getFlashBag()->add('error', 'Veillez vous déconnecter afin de créer un profil');
//            return new RedirectResponse($this->container->get('router')->generate('inscription'));
//        }
//        $place = $session->get('place');

        $user = new Demandeur();
        $form = $this->container->get('form.factory')->createBuilder(new DemandeurType($user), $user)->getForm();
        $form2 = $this->container->get('form.factory')->createBuilder(new RemisesType())->getForm();

        $form->handleRequest($request);

        // Traitement en Post
//        if ($request->isXmlHttpRequest()) {
            if ('POST' == $request->getMethod()) {
                $errors = $this->container->get('validator')->validate($form);

                if (count($errors) != 0)
                    foreach ($errors as $error){
                        $session->getFlashBag()->add('error', $error->getMessage());
                        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription.html.twig', array(
                            'form' => $form->createView(),
                            'form2' => $form2->createView()
                        ));
                    }
//                return new Response($error->getMessage(), 200, array('Content-Type' => 'application/json'));
//
                $ville = $form['ville']->getData();
                $mdp = $form['password']->getData();
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $newpass = $encoder->encodePassword($mdp, $user->getSalt());
                $user->setPassword($newpass);
                $villesproxymite = $em->getRepository('MyFuckinJobSiteBundle:Villes')->findIdByVilleAndZipcode($ville);
                if (!empty($villesproxymite)) {
                    $villesproxymite = $villesproxymite[0];
                    $session->set('place', $villesproxymite->getNomVille());
                    $user->setVille($villesproxymite->getNomVille());
                    $user->setZipcode($villesproxymite->getCodePostal());
                    $user->setLongitude($villesproxymite->getLongitude());
                    $user->setLatitude($villesproxymite->getLatitude());
                }

                $user->setIp($this->container->get('request')->getClientIp());

                $em->persist($user);
                $em->flush();

                $session->set('uid', $user->getId());
                $session->set('ville', $ville);

                //send email confirmation
//                $this->email = $this->container->get('fuckin_email');
//                $datas = array(
//                    'link' => $this->container->get('router')->generate('confirmation', array('email' => $user->getEmail(), 'token' => $user->getToken())),
//                    'gender' => $user->getGender(),
//                    'email' => $user->getEmail(),
//                    'firstname' => $user->getFirstname(),
//                    'lastname' => $user->getLastname(),
//                );
//                $this->email->send('contact@myfuckinjob.com', 'MyFuckinJobSiteBundle:Mails:inscription.html.twig', "Demande de confirmation d’inscription", $user->getEmail(), null, $datas, null, 'http://bateau.weekinsport.fr');
                $session->getFlashBag()->add('success', 'Bienvenue nouvel adhérant!');
                return new RedirectResponse($this->container->get('router')->generate('home'));

            }
//            return new Response('false', 200, array('Content-Type' => "application/json"));
//        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription.html.twig',
            array(
                'form' => $form->createView(),
                'form2' => $form2->createView(),
            ));
    }


    /**
     * Inscription Etape 2
     */
    public function inscriptionstep2Action()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();
        $uid =  $this->session->get('uid', null);

        if (!$uid) {
            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($uid);
        if (!$user) {
            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

        $form = $this->container->get('form.factory')->createBuilder(new DemandeurStep3Type(),$user)->getForm();

        $form->handleRequest($request);

        if ('POST' === $request->getMethod()) {
            $errors = $this->container->get('validator')->validate($form, array('suscribestep2'));
            if (count($errors) != 0)
                foreach ($errors as $error){
                    $this->session->getFlashBag()->add('error', $error->getMessage());
                    return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));

                }
            $ville = $form['ville']->getData();

            $villesproxymite = $em->getRepository('MyFuckinJobSiteBundle:Villes')->findIdByVilleAndZipcode($ville);
            if (!empty($villesproxymite)) {
                $villesproxymite = $villesproxymite[0];
                $this->session->set('place', $villesproxymite->getNomVille());
                $user->setVille($villesproxymite->getNomVille());
                $user->setZipcode($villesproxymite->getCodePostal());
                $user->setLongitude($villesproxymite->getLongitude());
                $user->setLatitude($villesproxymite->getLatitude());
            }else{
                $this->session->getFlashBag()->add('error', 'Votre ville est introuvable');
                return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
            }

            $em->persist($user);
            $em->flush();

            $this->session->getFlashBag()->add('success', 'Votre profil a bien été mis a jour');
            return new RedirectResponse($this->container->get('router')->generate('inscription_step3'));
        }
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
    }


    /**
     * Inscription Etape 3
     */
    public function inscriptionstep3Action()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();
        $form = $this->container->get('form.factory')->createBuilder(new DemandeurStep3Type())->getForm();
        $uid = $this->session->get('uid', 21);


        if (!$uid) {
            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($uid);
        if (!$user) {
            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

        $form2 = $this->container->get('form.factory')->createBuilder(new MetierType($user),$user)->getForm();
        $form3 = $this->container->get('form.factory')->createBuilder(new MoreThingType($user),$user)->getForm();


        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            $errors = $this->container->get('validator')->validate($form);

            $horaires->setPhrase($phrase);
            $em->persist($horaires);
            $em->flush();

            $this->session->getFlashBag()->add('success', 'Horaires ise à jour avec succès!');
            return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step3.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'user' => $user ));
    }



    /**
     * My Account
     */
    public function myaccountAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:myaccount.html.twig');
    }


    /**
     * Mes questions
     */
    public function myquestionsAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:myquestions.html.twig');
    }


    /**
     * Mes questions
     */
    public function myentretiensAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:myentretiens.html.twig');
    }


    /**
     * Mes disponibilités
     */
    public function mydisponibilitesAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:mydisponibilites.html.twig');
    }


    /**
     * My search
     */
    public function myrechercheAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:myrecherche.html.twig');
    }


    /**
     * My search
     */
    public function mypreferencesAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:mypreferences.html.twig');
    }


//
//
//    /**
//     * Inscription Etape 2
//     */
//    public function inscriptionstep2Action()
//    {
//        $em = $this->container->get('doctrine.orm.entity_manager');
//        $request = $this->container->get('request');
//
////        $uid = $this->session->get('uid', 0);
////        if (!$uid) {
////            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
////            return new RedirectResponse($this->container->get('router')->generate('inscription'));
////        }
////
////        $user = $em->getRepository('MyFuckinJobSiteBundle:Users')->find($uid);
////        if (!$user) {
////            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
////            return new RedirectResponse($this->container->get('router')->generate('inscription'));
////        }
//
//        $form = $this->container->get('form.factory')->createBuilder(new DemandeurHorairesType())->getForm();
//
//            if ('POST' === $request->getMethod()) {
//                $form->bind($request);
//                $errors = $this->container->get('validator')->validate($form);
//
//                if (count($errors) != 0) {
//                    foreach ($errors as $error) {
//                        $this->session->getFlashBag()->add('error', $error->getMessage());
//                        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
//                    }
//                }
//
//                $horaires = new DemandeurHoraires();
//                $horaires->setDemandeur(null);
//                $lundi = $form['lun_begin_am']->getData();
//                $lundi_end = $form['lun_begin_pm']->getData();
//                $mardi = $form['mar_begin_am']->getData();
//                $mardi_end = $form['mar_begin_pm']->getData();
//                $mercredi = $form['mer_begin_am']->getData();
//                $mercredi_end = $form['mer_begin_pm']->getData();
//                $jeudi = $form['jeu_begin_am']->getData();
//                $jeudi_end = $form['jeu_begin_pm']->getData();
//                $vendredi = $form['ven_begin_am']->getData();
//                $vendredi_end = $form['ven_begin_pm']->getData();
//                $samedi = $form['sam_begin_am']->getData();
//                $samedi_end = $form['sam_begin_pm']->getData();
//                $dimanche = $form['dim_begin_am']->getData();
//                $dimanche_end = $form['dim_begin_pm']->getData();
//                $phrase = $form['phrase']->getData();
//
//
//                if (!empty($lundi) || !empty($lundi_end)) {
//                    $horaires->setJ1($form['lun_begin_am']->getData() . ',' . $form['lun_end_am']->getData() . "," . $form['lun_begin_pm']->getData() . ',' . $form['lun_end_pm']->getData());
//                } else {
//                    $horaires->setJ1(null);
//                }
//                if (!empty($mardi) || !empty($mardi_end)) {
//                    $horaires->setJ2($form['mar_begin_am']->getData() . ',' . $form['mar_end_am']->getData() . "," . $form['mar_begin_pm']->getData() . ',' . $form['mar_end_pm']->getData());
//                } else {
//                    $horaires->setJ2(null);
//                }
//                if (!empty($mercredi) || !empty($mercredi_end)) {
//                    $horaires->setJ3($form['mer_begin_am']->getData() . ',' . $form['mer_end_am']->getData() . "," . $form['mer_begin_pm']->getData() . ',' . $form['mer_end_pm']->getData());
//                } else {
//                    $horaires->setJ3(null);
//                }
//                if (!empty($jeudi) || !empty($jeudi_end)) {
//                    $horaires->setJ4($form['jeu_begin_am']->getData() . ',' . $form['jeu_end_am']->getData() . "," . $form['jeu_begin_pm']->getData() . ',' . $form['jeu_end_pm']->getData());
//                } else {
//                    $horaires->setJ4(null);
//                }
//                if (!empty($vendredi) || !empty($vendredi_end)) {
//                    $horaires->setJ5($form['ven_begin_am']->getData() . ',' . $form['ven_end_am']->getData() . "," . $form['ven_begin_pm']->getData() . ',' . $form['ven_end_pm']->getData());
//                } else {
//                    $horaires->setJ5(null);
//                }
//                if (!empty($samedi) || !empty($samedi_end)) {
//                    $horaires->setJ6($form['sam_begin_am']->getData() . ',' . $form['sam_end_am']->getData() . "," . $form['sam_begin_pm']->getData() . ',' . $form['sam_end_pm']->getData());
//                } else {
//                    $horaires->setJ6(null);
//                }
//                if (!empty($dimanche) || !empty($dimanche_end)) {
//                    $horaires->setJ7($form['dim_begin_am']->getData() . ',' . $form['dim_end_am']->getData() . "," . $form['dim_begin_pm']->getData() . ',' . $form['dim_end_pm']->getData());
//                } else {
//                    $horaires->setJ7(null);
//                }
//                $horaires->setPhrase($phrase);
//                $em->persist($horaires);
//                $em->flush();
//
//                $this->session->getFlashBag()->add('success', 'Horaires ise à jour avec succès!');
//                return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
//        }
//        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step2.html.twig', array('form' => $form->createView()));
//    }


//
//    /**
//     * Inscription Etape 3
//     */
//    public function inscriptionstep3Action()
//    {
//        $em = $this->container->get('doctrine.orm.entity_manager');
//        $request = $this->container->get('request');
//        $this->session = $request->getSession();
//
////        $uid = $this->session->get('uid', 0);
////        if (!$uid) {
////            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
////            return new RedirectResponse($this->container->get('router')->generate('inscription'));
////        }
////
//        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find(11);
//        if (!$user) {
//            $this->session->getFlashBag()->add('error', 'Merci de créer un profil avant');
//            return new RedirectResponse($this->container->get('router')->generate('inscription'));
//        }
//
//        $experience = new Experience();
//        $user->addExperience($experience);
//
//        $form = $this->container->get('form.factory')->createBuilder(new DemandeurExtrasType(), $user)->getForm();
//
//            //Traitement
//            if ('POST' === $request->getMethod()) {
//                $form->bind($request);
//                $errors = $this->container->get('validator')->validate($form);
//
//                if (count($errors) != 0) {
//                    foreach ($errors as $error) {
//                        $this->session->getFlashBag()->add('error', $error->getMessage());
//                        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step3.html.twig', array('form' => $form->createView(), 'user' => $user));
//                    }
//                }
//
//                $skills = $form['skill']->getData();
//                if(!empty($skills))
//                    foreach($skills as $oneskill){
//                        $skill = new DemandeurSkill();
//                        $skill->setDemandeur($user);
//                        $skill->setSkill($oneskill);
//                        $em->persist($skill);
//                        $em->flush();
//                    }
//
//                $em->persist($user);
//                $em->flush();
//
//                $this->session->getFlashBag()->add('success', 'Horaires mise à jour avec succès!');
//                return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step3.html.twig', array('form' => $form->createView(), 'user' => $user));
//        }
//        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Demandeurs:inscription_step3.html.twig', array('form' => $form->createView(), 'user' => $user));
//
//    }


    /**
     *  Login Action
     * @return type
     */
    public function loginAction()
    {
        $request = $this->container->get('request');
        $session = $this->container->get('session');

        $dm = $this->container->get('doctrine_mongodb')
            ->getManager();

        $test = $dm->getRepository('MyFuckinJobSiteBundle:Test')
            ->findOneBy(array('question' => 'Tu vas bien ?'));


        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
//        exit(Debug::dump(SecurityContext::AUTHENTICATION_ERROR));
        return $this->container->get('templating')->renderResponse(
            'MyFuckinJobSiteBundle:Demandeurs:home.html.twig', array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error)
        );
    }


    /**
     * Editer avatar: Resize photo de profil
     */
    public function resizeProfilAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

//        $uid = $this->session->get('uid', 0);
//        if (!$uid) {
//            return new Response('false', 200, array('Content-Type' => 'application/json'));
//        }
        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find(11);
        if (!$user) {
            return new Response('false', 200, array('Content-Type' => 'application/json'));
        }

        //traitement du post
        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {

                $x = (int)$request->request->get('x');
                $y = (int)$request->request->get('y');
                $w = (int)$request->request->get('w');
                $h = (int)$request->request->get('h');
                $namephoto = $user->getAvatar();

                if ($x == 0 && $y == 0 && $w == 0 && $h == 0)
                    return new Response('Il y a problème lors du redimentionnement de votre photo. Veillez rafraîchir la page svp.', 200, array('Content-Type' => 'application/json'));

                $path = $user->getSrcAbsolutePath('users', $user->getId());
                $finder = new Finder();
                $finder->files()->in($path);

                $imgcroptab = pathinfo($path . $namephoto);
                $img = $imgcroptab['filename'];
                $extension = $imgcroptab['extension'];

                $namephoto = $img . '-medium.' . $extension;
                $bigphoto = $img . '-giga.' . $extension;

                $imagine = new \Imagine\Gd\Imagine();
                $imagine
                    ->open($path . $bigphoto)
                    ->crop(new Point($x, $y), new \Imagine\Image\Box($w, $h))
                    ->save(
                        $path . $namephoto, array(
                            'quality' => 100
                        )
                    );

                $this->session->getFlashBag()->add('success', 'Votre image a bien été recadrée');
                return new Response(json_encode(array("success" => true, 'img' => '')), 200, array('Content-Type' => 'text/plain'));
            }
        }
    }


    /**
     * Upload photo d'avatar
     */
    public function uploadVisiteurAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $this->session = $request->getSession();

//        $uid = $this->session->get('uid', 0);
//        if (!$uid) {
//            return new Response('false', 200, array('Content-Type' => 'application/json'));
//        }
        $user = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find(11);
        if (!$user) {
            return new Response('false', 200, array('Content-Type' => 'application/json'));
        }

        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {

                $file = $request->files->get('qqfile');

                $collectionConstraint = new Collection(array(
                    'images' => array(
                        new Image(array(
                            'minWidth' => 100,
                            'minHeight' => 100,
                            'maxWidth' => 5000,
                            'maxHeight' => 5000,
                            'maxHeight' => 5000,
                            'maxSize' => 5000000,
                            'mimeTypes' => array("image/jpg", "image/jpeg", "image/png", "image/gif", " image/bmp"),
                            'maxWidthMessage' => "Image trop grande en largeur {{ width }}px. Le maximum en largeur est de {{ max_width }}px",
                            'minWidthMessage' => "Image trop petite en largeur {{ width }}px. Le minimum en largeur est de {{ min_width }}px",
                            'minHeightMessage' => "Image trop petite en hauteur {{ height }}px. Le mimum en hauteur est de {{ min_height }}px",
                            'maxHeightMessage' => "Image trop grande en hauteur  {{ height }}px. Le maximum en hauteur est de {{ max_height }}px",

                        ))
                    )));

                $data = array(
                    'images' => $file,
                );
                $errorList = $this->container->get('validator')->validateValue($data, $collectionConstraint);

                if (count($errorList) != 0) {
                    foreach ($errorList as $error) {
                        $this->session->getFlashBag()->add('error', $error->getMessage());
                        return new Response($error->getMessage(), 200, array('Content-Type' => 'application/json'));
                    }
                }

                if (!is_dir($user->getSrcAbsolutePath('users', $user->getId())))
                    @mkdir($user->getUploadRootDir('users', $user->getId()));

                $path = $this->container->get('kernel')->getRootDir() . '/../web/uploads/users/' . $user->getId();

                $finder = new Finder();
                $search = $finder->files()->in($path);
                if (!empty($search))
                    foreach ($search as $fi) {
                        if (!empty($fi))
                            if (@file_exists($fi->getRealpath()))
                                @unlink($fi->getRealpath());
                    }
                $user->setFile($file);
                $user->upload('users', $user->getId());
                $em->persist($user);
                $em->flush();

                $imgcroptab = pathinfo($user->getAvatar());
                $imgcrop = $imgcroptab['filename'] . '-cropped';
                $ext = $imgcroptab['extension'];

                $img = "/uploads/users/" . $user->getId() . "/" . $imgcrop . '.' . $ext;

                return new Response(json_encode(array("success" => true, 'img' => $img)), 200, array('Content-Type' => 'text/plain'));
            }
        }
        return new Response('false', 200, array('Content-Type' => 'application/json'));

    }


    /**
     * Validation d'un Compte par email
     * @return type
     */
    public function validationAction($email = null, $token = null)
    {
        $request = $this->container->get('request');
        $this->session = $request->getSession();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('WksSiteBundle:Users')->findOneBy(array('email' => $email));
        if (!$user) {
            $this->session->getFlashBag()->add('error', 'Veuillez vous contacter ou nous contacter si vous rencontrer des difficultés');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }
        if ($user->getToken() == $token) {
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();

            //autologin
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'site', $user->getRoles());
            $securityContext = $this->container->get('security.context');
            $securityContext->setToken($token);

            $this->session->getFlashBag()->add('success', 'Votre compte a été validé! Bienvenue sur Weekinsport ;)');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        } else {
            $this->session->getFlashBag()->add('error', 'Veuillez-vous nous contacter');
            return new RedirectResponse($this->container->get('router')->generate('inscription'));
        }

    }
}
