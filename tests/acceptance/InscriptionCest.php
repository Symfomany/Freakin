<?php
use \WebGuy;

class InscriptionCest
{

    public function _before()
    {
    }

    public function _after()
    {
    }

    public function goHome(WebGuy $I)
    {
        $I->wantTo('Go Gome');
        $I->amOnPage('/');
        $I->see('Inscription étape 1');
        $I->seeElement('h2');
        $I->dontSeeInCurrentUrl('/users/');
        $I->dontSeeLink('Logout');
        $I->seeCurrentUrlEquals('/');
        $I->seeInCurrentUrl('/');
    }

    public function inscriptionDemandeur(WebGuy $I)
    {
        /**
         * Step 1
         */
        $I->wantTo('Inscription de Demandeur étape 1');
        $I->amOnPage('/');
        $I->see('Inscription étape 1');
        $I->expect('Etape 1');
        $email = "zuzu38080" . time(). "@gmail.com";
        $I->submitForm('#inscription',
            array(
                'firstname' => 'Julien',
                'lastname' => 'Boyer',
                'email' => $email,
                'password' => 'coucou'
            ));
        $errors = $I->grabTextFrom('.alert');
        $I->expect($errors);

        /**
         * Step 2
         */
        $I->wantTo('Inscription étape 2');
        $I->amOnPage('/inscription-etape2');
        $I->expect('Etape 2');
        $I->see('Inscription étape 2');
        $I->seeInCurrentUrl('/inscription-etape2');

        $I->submitForm('#inscription',
            array(
                'metier' => 1,
                'firstname' => 'Julien',
                'lastname' => 'Boyer',
                'email' => $email,
                'dob[day]' => 19,'dob[month]' => 03, 'dob[year]' => 1988,
                'ville' => 'Lyon',
                'cgu' => true
            ));
        $errors = $I->grabTextFrom('.alert');
        $I->expect($errors);
        $I->seeInCurrentUrl('/inscription-etape3');

        /**
         * Step 3
         */
        $I->see('Profil');

    }

}