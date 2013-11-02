<?php
namespace MyFuckinJob\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use MyFuckinJob\SiteBundle\Entity\Demandeur;
use MyFuckinJob\SiteBundle\Form\Type\DemandeurType;


class DefaultController extends ContainerAware
{

    public function indexAction($name)
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Default:index.html.twig', array('name' => $name));
    }

    public function testAction()
    {
        $request = $this->container->get('request');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $demandeur = new Demandeur();

        $form = $this->container->get('form.factory')->createBuilder(new DemandeurType(), $demandeur)->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                die('coucou julien. Ton formulaire est valide');
            }
        }
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Default:test.html.twig', array('form' => $form->createView()));
    }



}
