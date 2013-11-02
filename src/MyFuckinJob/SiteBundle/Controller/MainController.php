<?php

namespace MyFuckinJob\SiteBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use MyFuckinJob\SiteBundle\Form\Type\SearchType;
use Doctrine\Common\Util\Debug as Debug;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\Response;

class MainController extends ContainerAware
{

    /**
     * Search Demandeurs
     */
    public function searchAction()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        $session = $this->container->get('session');

        $form = $this->container->get('form.factory')->createBuilder(new SearchType())->getForm();
        $results = array();
        $offers = array();
        $oids = $session->get('did');

        $ville = $request->query->get('ville');

        //Get Ville
        if(!empty($ville)){
            $sphinxSearch = $this->container->get('search.sphinxsearch.search');

            $sphinxSearch->sphinx->SetArrayResult(true);
            $sphinxSearch->sphinx->ResetFilters();
            $sphinxSearch->sphinx->SetLimits(0,1500);

            $sphinxSearch->sphinx->SetMatchMode(SPH_MATCH_ALL);

            /**
             * Traitement des filtres...
             */
            $sphinxSearch->sphinx->SetFieldWeights(array(
                "title" => 100,
                "ville" => 80,
                "zipcode" => 100,
                "description" => 60,
                "equipement" => 60,
            ));

            $results = (Array)$sphinxSearch->sphinx->Query($ville, 'demandeurs');

            if (array_key_exists('matches', $results))
                $resultat = $results['matches'];
            else
                $resultat = null;


            $oids = array();
            if (!empty($resultat))
                foreach ($resultat as $res) {
                    $offer = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($res['id']);
                    $oids[] = $res['id'];
                    if ($offer)
                        $offers[] = $offer;
                }

//                exit(Debug::dump(($offers)));

            $session->set('did', $oids);

            if (array_key_exists('total', $results))
                $total = $results['total'];

            $session->set('total', $total);

            $paginator = $this->container->get('knp_paginator');
            $resultTotal = new ArrayCollection($offers);

            $pagination = $paginator->paginate(
                $resultTotal,
                $this->container->get('request')->query->get('page', 1) /*page number*/,
                10 /*limit per page*/
            );

        }
//        Sans Get Ville
        else{
            if (!empty($oids))
                foreach ($oids as $res) {
                    $offer = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($res);
                    if ($offer)
                        $offers[] = $offer;
                }
            $total = count($offers);
            $session->set('total', $total);

            $paginator = $this->container->get('knp_paginator');
            $resultTotal = new ArrayCollection($offers);

            $pagination = $paginator->paginate(
                $resultTotal,
                $this->container->get('request')->query->get('page', 1) /*page number*/,
                10 /*limit per page*/
            );
        }



//        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {

                $form->bind($request);
                $errors = $this->container->get('validator')->validate($form);
                // Errors handlers
                if (count($errors) != 0)
                    foreach ($errors as $error)
                        return new Response($error->getMessage(), 200, array('Content-Type' => 'application/json'));

                //Receive datas
                $lieu = $form['lieu']->getData();
                $session->set('place', $lieu);
                $activity = $form['activity']->getData();
                $session->set('activity', $activity);

                $offers = array();
                $villes = array();
                $total = 0;
                $resultTotal = array();

                $sphinxSearch = $this->container->get('search.sphinxsearch.search');

                $sphinxSearch->sphinx->SetArrayResult(true);
                $sphinxSearch->sphinx->ResetFilters();
                $sphinxSearch->sphinx->SetLimits(0,1500);

                $sphinxSearch->sphinx->SetMatchMode(SPH_MATCH_ALL);

                /**
                 * Traitement des filtres...
                 */
                $sphinxSearch->sphinx->SetFieldWeights(array(
                    "title" => 100,
                    "ville" => 80,
                    "zipcode" => 100,
                    "description" => 60,
                    "equipement" => 60,
                ));

                $results = (Array)$sphinxSearch->sphinx->Query($lieu. " ".$activity, 'demandeurs');

                if (array_key_exists('matches', $results))
                    $resultat = $results['matches'];
                else
                    $resultat = null;




                $oids = array();
                if (!empty($resultat))
                    foreach ($resultat as $res) {
                        $offer = $em->getRepository('MyFuckinJobSiteBundle:Demandeur')->find($res['id']);
                        $oids[] = $res['id'];
                        if ($offer)
                            $offers[] = $offer;
                    }

//                exit(Debug::dump(($offers)));

                $session->set('did', $oids);

                if (array_key_exists('total', $results))
                    $total = $results['total'];

                $session->set('total', $total);

                $paginator = $this->container->get('knp_paginator');
                $resultTotal = new ArrayCollection($offers);

                $pagination = $paginator->paginate(
                    $resultTotal,
                    $this->container->get('request')->query->get('page', 1) /*page number*/,
                    10 /*limit per page*/
                );


                return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Main:search.html.twig', array(
                    'form' => $form->createView(),
                    'pagination' => $pagination
                ));
            }
//        }

        return $this->container->get('templating')->renderResponse('MyFuckinJobSiteBundle:Main:search.html.twig', array(
            'form' => $form->createView(),
            'pagination' => $pagination
        ));
    }



    /**
     * Search villes
     * @return type
     */
    public function villesAction()
    {

        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        if ($request->isXmlHttpRequest()) {


            $sphinxSearch = $this->container->get('search.sphinxsearch.search');
            $sphinxSearch->sphinx->SetArrayResult(true);
            $sphinxSearch->sphinx->SetMatchMode(SPH_MATCH_ALL); //ça ou ça ou les 2 -> avant ça et ça
            $sphinxSearch->sphinx->SetLimits(0, 50);

            $ville = $request->query->get('term', 'Lyon');
            $results_lieu = $sphinxSearch->sphinx->Query($ville, 'villes');

            if (is_array($results_lieu))
                if (array_key_exists('matches', $results_lieu))
                    $resultat_lieu = $results_lieu['matches'];
                else
                    $resultat_lieu = null;

            if (!empty($resultat_lieu))
                foreach ($resultat_lieu as $res) {
                    $ville = $em->getRepository('MyFuckinJobSiteBundle:Villes')->find($res['id']);
                    if ($ville) {
                        $villes[] = $ville->getCodePostal() . ' | ' . preg_replace("/^(.+) .+ arrondissement$/", "$1", $ville->getNomVille());
                    }
                }

            if (!empty($resultat_lieu)) {
                return new Response(json_encode($villes), 200, array('Content-Type' => 'application/json'));
            }
        }
        return new Response('false', 200, array('Content-Type' => 'application/json'));
    }


    /**
     * Search Simple Villes
     * @return type
     */
    public function simplevillesAction()
    {

        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        if ($request->isXmlHttpRequest()) {

            $sphinxSearch = $this->container->get('search.sphinxsearch.search');
            $sphinxSearch->sphinx->SetArrayResult(true);
            $sphinxSearch->sphinx->SetMatchMode(SPH_MATCH_ALL); //ça ou ça ou les 2 -> avant ça et ça
            $sphinxSearch->sphinx->SetLimits(0, 50);

            $ville = $request->query->get('term', 'Lyon');
            $results_lieu = $sphinxSearch->sphinx->Query($ville, 'villes');

            if (array_key_exists('matches', $results_lieu))
                $resultat_lieu = $results_lieu['matches'];
            else
                $resultat_lieu = null;

            if (!empty($resultat_lieu))
                foreach ($resultat_lieu as $res) {
                    $ville = $em->getRepository('MyFuckinJobSiteBundle:Villes')->find($res['id']);
                    if ($ville) {
                        $villes[] = $ville->getNomVille();
                    }
                }

            if (!empty($resultat_lieu)) {
                return new Response(json_encode($villes), 200, array('Content-Type' => 'application/json'));
            }
        }
        return new Response('false', 200, array('Content-Type' => 'application/json'));
    }


    /**
     * Search Simple Skills
     * @return type
     */
    public function simpleskillsAction()
    {

        $em = $this->container->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');
        if ($request->isXmlHttpRequest()) {

            $sphinxSearch = $this->container->get('search.sphinxsearch.search');
            $sphinxSearch->sphinx->SetArrayResult(true);
            $sphinxSearch->sphinx->SetMatchMode(SPH_MATCH_ALL); //ça ou ça ou les 2 -> avant ça et ça
            $sphinxSearch->sphinx->SetLimits(0, 50);

            $skill = $request->query->get('term', '');
            $results_lieu = $sphinxSearch->sphinx->Query($skill, 'skills');

            if (array_key_exists('matches', $results_lieu))
                $resultat_lieu = $results_lieu['matches'];
            else
                $resultat_lieu = null;

            if (!empty($resultat_lieu))
                foreach ($resultat_lieu as $res) {
                    $skill = $em->getRepository('MyFuckinJobSiteBundle:Skill')->find($res['id']);
                    if ($skill) {
                        $villes[] = $skill->getTitle();
                    }
                }

            if (!empty($resultat_lieu)) {
                return new Response(json_encode($villes), 200, array('Content-Type' => 'application/json'));
            }
        }
        return new Response('false', 200, array('Content-Type' => 'application/json'));
    }



}
