<?php
$I = new WebGuy($scenario);

/**
 * Step 1
 */

$I->wantTo('Bienvenue sur la Home');
$I->amOnPage('/');
$I->see('Inscription étape 1');

/**
 * Submit Inscription Step 1
 */
//$I->submitForm('#inscription', array(
//    'firstname' => 'Julien',
//    'lastname' => 'Davis',
//    'email' => 'juju@gmail.com',
//    'password' => 'coucousavaetmoi',
//));

$I->sendAjaxPostRequest('#inscription', array(
    'firstname' => 'Julien',
    'lastname' => 'Davis',
    'email' => 'jujqsdsqu@gmail.com',
    'password' => 'coucousavaetmoi',
));


/**
 * Step 2
 */
//$I->amOnPage('/inscription-etape2');
//$I->see('Inscription étape 2');
//$firstname = $I->grabTextFrom('#firstname');
//print_r($firstname);

//$I->sendAjaxPostRequest('#inscription', array(
//    'firstname' => 'Julien',
//    'lastname' => 'Davis',
//    'email' => 'juju@gmail.com',
//    'password' => 'coucousavaetmoi',
//));

