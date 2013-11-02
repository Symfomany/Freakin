<?php
namespace MyFuckinJob\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MyFuckinJob\SiteBundle\Entity\Recruteur;
use MyFuckinJob\SiteBundle\Form\Type\RecruteurType;

use Imagine\Gd\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;

use Symfony\Component\Finder\Finder;

use MyFuckinJob\SiteBundle\Form\Type\MessagesType;


class RecruteursController extends ContainerAware
{
    public function testMongooseAction()
    {
        $dm = $this->container->get('doctrine_mongodb')
            ->getManager();

        $users = $dm->getRepository('MyFuckinJobSiteBundle:User')->findAll();



        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:testMongoose.html.twig',
            array('users' => $users));
    }

    public function testMongooseDeuxAction()
    {
        $request = $this->container->get('request');
        $dm = $this->container->get('doctrine_mongodb')->getManager();

        $user = $dm->getRepository('MyFuckinJobSiteBundle:User')->find('52472a28a5af32df0e000001');
        $messages = $dm->getRepository('MyFuckinJobSiteBundle:Messages')->findAll();

        $message = new \MyFuckinJob\SiteBundle\Document\Messages();
        $form = $this->container->get('form.factory')->createBuilder(new MessagesType(), $message)->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if($form->isValid()){
                $message->setUser($user);
                $dm->persist($message);
                $dm->flush();
            }
        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:testMongooseDeux.html.twig',
            array('messages' => $messages,
                'form' => $form->createView(),
                'user' => $user,
            ));
    }


    public function testMongoose3Action()
    {
        $request = $this->container->get('request');
        $dm = $this->container->get('doctrine_mongodb')->getManager();

        $user = $dm->getRepository('MyFuckinJobSiteBundle:User')->find('52472a28a5af32df0e000001');
        $messages = $dm->getRepository('MyFuckinJobSiteBundle:Messages')->findAll();

        $message = new \MyFuckinJob\SiteBundle\Document\Messages();
        $form = $this->container->get('form.factory')->createBuilder(new MessagesType(), $message)->getForm();

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:testMongooseTrois.html.twig',
            array('messages' => $messages,
                'form' => $form->createView(),
                'user' => $user,
            ));
    }


    public function indexAction($name)
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:index.html.twig', array('name' => $name));
    }

    public function inscriptionAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $session = $this->container->get('session');

        $place = $session->get('place');

        //Get Geolocalisation
        if (empty($place)) {
            exec(sprintf('python ../py/ipcoordonate4.py %s', $_SERVER['REMOTE_ADDR']), $result);
            if (empty($result))
                exec(sprintf('python ../py/ipcoordonate2.py %s', $_SERVER['REMOTE_ADDR']), $result);
            if (empty($result))
                exec(sprintf('python ../py/ipcoordonate.py %s', $_SERVER['REMOTE_ADDR']), $result);

            if (!empty($result))
                $session->set('place', $result[0]);
        }


        $recruteur = new Recruteur();

        $form = $this->container->get('form.factory')->createBuilder(new RecruteurType(), $recruteur)->getForm();

        // Traitement en Post
        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {
                $form->bind($request);
                $errors = $this->container->get('validator')->validate($form);

                // Errors handlers
                if (count($errors) != 0)
                    foreach ($errors as $error)
                        return new Response($error->getMessage(), 200, array('Content-Type' => 'application/json'));

                //Receive datas
                $name = $form['name']->getData();
                $mdp = $form['password']->getData();
                $tel = $form['tel']->getData();
                $cgu = $form['cgu']->getData();
                $ville = $form['ville']->getData();

                $villesproxymite = $em->getRepository('MyFuckinJobSiteBundle:Villes')->findIdByVilleAndZipcode($ville);
                if (!empty($villesproxymite)) {
                    $villesproxymite = $villesproxymite[0];
                    $session->set('place', $villesproxymite->getNomVille());
                    $recruteur->setVille($villesproxymite->getNomVille());
                    $recruteur->setZipcode($villesproxymite->getCodePostal());
                    $recruteur->setLongitude($villesproxymite->getLongitude());
                    $recruteur->setLatitude($villesproxymite->getLatitude());
                }

                $recruteur->setName($name);
                $recruteur->setTel($tel);
                $recruteur->setIp($this->container->get('request')->getClientIp());

                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $newpass = $encoder->encodePassword($mdp, $recruteur->getSalt());
                $recruteur->setPassword($newpass);

                $em->persist($recruteur);
                $em->flush();

                $session->set('uid', $recruteur->getId());
                $session->set('ville', $ville);

                //send email confirmation
                /*
                $this->email = $this->container->get('fuckin_email');
                $datas = array(
                    'link' => $this->container->get('router')->generate('confirmation_recruteur', array('email' => $recruteur->getEmail(), 'token' => $recruteur->getToken())),
                    'email' => $recruteur->getEmail(),
                    'firstname' => $recruteur->getName(),
                );
                $this->email->send('contact@myfuckinjob.com', 'MyFuckinJobSiteBundle:Mails:inscription-recruteur.html.twig', "Demande de confirmation d’inscription de recruteur", $recruteur->getEmail(), null, $datas, null, 'http://bateau.weekinsport.fr');
                */

                return new Response('true', 200, array('Content-Type' => 'application/json'));
            }
            return new Response('false', 200, array('Content-Type' => 'application/json'));
        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:inscription.html.twig', array('form' => $form->createView()));
    }

    public function inscriptionstep2Action()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $session = $request->getSession();

//        $uid = $session->get('uid', 0);
//        if (!$uid) {
//            $session->getFlashBag()->add('error', 'Merci de créer un profil avant');
//            return new RedirectResponse($this->container->get('router')->generate('inscription_recruteur'));
//        }
//

        $recruteur = $em->getRepository('MyFuckinJobSiteBundle:Recruteur')->find(6);
        if (!$recruteur) {
            $session->getFlashBag()->add('error', 'Merci de créer un profil avant');
            return new RedirectResponse($this->container->get('router')->generate('inscription_recruteur'));
        }

        //$form = $this->container->get('form.factory')->createBuilder(new RecruteurStep2(), $recruteur)->getForm();

        // Traitement
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            $errors = $this->container->get('validator')->validate($form);

            if (count($errors) != 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error->getMessage());
                    return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:inscription_step2.html.twig', array('form' => $form->createView(), 'recruteur' => $recruteur));
                }
            }

            $em->persist($recruteur);
            $em->flush();

            $session->getFlashBag()->add('success', 'Fait avec succès');
            return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:inscription_step2.html.twig', array('form' => $form->createView(), 'recruteur' => $recruteur));
        }
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Recruteurs:inscription_step2.html.twig', array('recruteur' => $recruteur));
    }

    public function uploadRecruteurAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $session = $request->getSession();

//        $uid = $this->session->get('uid', 0);
//        if (!$uid) {
//            return new Response('false', 200, array('Content-Type' => 'application/json'));
//        }

        $recruteur = $em->getRepository('MyFuckinJobSiteBundle:Recruteur')->find(6);
        if (!$recruteur)
            return new Response('false', 200, array('Content-Type' => 'application/json'));

        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {

                $file = $request->files->get('qqfile');
                die(var_dump($file));

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
                        $session->getFlashBag()->add('error', $error->getMessage());
                        return new Response($error->getMessage(), 200, array('Content-Type' => 'application/json'));
                    }
                }

                if (!is_dir($recruteur->getSrcAbsolutePath('recruteurs', $recruteur->getId())))
                    @mkdir($recruteur->getUploadRootDir('recruteurs', $recruteur->getId()));

                $path = $this->container->get('kernel')->getRootDir() . '/../web/uploads/recruteurs/' . $recruteur->getId();

                $finder = new Finder();
                $search = $finder->files()->in($path);
                if (!empty($search))
                    foreach ($search as $fi) {
                        if (!empty($fi))
                            if (@file_exists($fi->getRealpath()))
                                @unlink($fi->getRealpath());
                    }
                $recruteur->setFile($file);
                $recruteur->upload('recruteurs', $recruteur->getId());
                $em->persist($recruteur);
                $em->flush();

                $imgcroptab = pathinfo($recruteur->getAvatar());
                $imgcrop = $imgcroptab['filename'] . '-cropped';
                $ext = $imgcroptab['extension'];

                $img = "/uploads/recruteurs/" . $recruteur->getId() . "/" . $imgcrop . '.' . $ext;

                return new Response(json_encode(array("success" => true, 'img' => $img)), 200, array('Content-Type' => 'text/plain'));
            }
        }
        return new Response('false', 200, array('Content-Type' => 'application/json'));

    }

    public function cropImgRecruteurAction()
    {
        die('ok');
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');

        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {
                $recruteur = $em->getRepository('MyFuckinJobSiteBundle:Recruteur')->find(6);
                if ($recruteur){

                    $x = (int)$request->request->get('x');
                    $y = (int)$request->request->get('y');
                    $w = (int)$request->request->get('w');
                    $h = (int)$request->request->get('h');

                    if ($x == 0 && $y == 0 && $w == 0 && $h == 0)
                        return new Response('Problème lors du redimentionnement de votre photo. Veuillez rafraîchir la page svp.', 200, array('Content-Type' => 'application/json'));
                    die('');
                    $path = __DIR__ . '/../../../../web/images/';
                    $namephoto = 'exaple-image.jpg';
                    $finder = new Finder();
                    $finder->files()->in($path);

                    $imgcroptab = pathinfo($path.$namephoto);
                    $img = $imgcroptab['filename'];
                    $extension = $imgcroptab['extension'];

                    $bigphoto = $img.'.'.$extension;
                    $namephoto = $img.'-medium.'.$extension;

                    $imagine = new \Imagine\Gd\Imagine();
                    $imagine
                        ->open($path.$bigphoto)
                        ->crop(new Point($x, $y), new \Imagine\Image\Box($w, $h))
                        ->resize(new \Imagine\Image\Box(290, 250))
                        ->save(
                        $path.$namephoto,array(
                            'quality' => 100
                        )
                    );

                    $img = 'images/'.$namephoto.'?v'.time();
                    return new Response(json_encode(array("success" => true, 'img' => $img )), 200, array('Content-Type' => 'application/json'));
                }
            }
            return new Response('false', 200, array('Content-Type' => 'application/json'));
        }
        return new RedirectResponse($this->container->get('router')->generate('inscription_recruteur'));
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
        $recruteur = $em->getRepository('MyFuckinJobSiteBundle:Recruteur')->findOneBy(array('email' => $email));
        if (!$recruteur) {
            $this->session->getFlashBag()->add('error', 'Veuillez vous contacter ou nous contacter si vous rencontrer des difficultés');
            return new RedirectResponse($this->container->get('router')->generate('inscription_recruteur'));
        }
        if ($recruteur->getToken() == $token) {
            $recruteur->setEnabled(true);
            $em->persist($recruteur);
            $em->flush();

            //autologin
            $token = new UsernamePasswordToken($recruteur, $recruteur->getPassword(), 'site', $recruteur->getRoles());
            $securityContext = $this->container->get('security.context');
            $securityContext->setToken($token);

            $this->session->getFlashBag()->add('success', 'Votre compte a été validé! Bienvenue sur MyFuckinJob ;)');
            return new RedirectResponse($this->container->get('router')->generate('home'));
        } else {
            $this->session->getFlashBag()->add('error', 'Veuillez nous contacter');
            return new RedirectResponse($this->container->get('router')->generate('inscription_recruteur'));
        }

    }

}
