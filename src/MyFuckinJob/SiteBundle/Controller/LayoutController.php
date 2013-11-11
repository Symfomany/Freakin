<?php
namespace MyFuckinJob\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use MyFuckinJob\SiteBundle\Entity\Demandeur;
use MyFuckinJob\SiteBundle\Form\Type\DemandeurType;


class LayoutController extends ContainerAware
{

    public function topmenuAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Slot:topmenu.html.twig');
    }

    public function rightmenuAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Slot:rightmenu.html.twig');
    }

    public function bottomBrandAction()
    {
        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Slot:bottombrand.html.twig');
    }


}
