<?php

class DemandeursTest extends \Codeception\TestCase\Test
{
    /**
     * @var CodeGuy
     */
    protected $codeGuy;

    // executed before each test
    protected function _before()
    {

    }

    // executed after each test
    protected function _after()
    {
    }


    public function testfindIdByVilleAndZipcode()
    {
//        $this->assertTrue(Validator::validate('davert@mail.ua'));

        $container = $this->getModule('Symfony2')->container;
        $em = $container->get('doctrine')->getManager();


        foreach($this->villes as $ville){
            $villesproxymite = $em->getRepository('MyFuckinJobSiteBundle:Villes')->findIdByVilleAndZipcode($ville['name']);
            $this->assertEquals(1, $ville['count']);
        }

    }

}