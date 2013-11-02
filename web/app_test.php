<?php


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.

//if ( $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '109.213.226.95' && $_SERVER['REMOTE_ADDR'] != '109.213.226.95'  && $_SERVER['REMOTE_ADDR'] != '109.213.110.178' &&  $_SERVER['REMOTE_ADDR'] != '90.53.23.221' &&  $_SERVER['REMOTE_ADDR'] != '92.157.3.239' && $_SERVER['REMOTE_ADDR'] != '90.29.100.108' &&  $_SERVER['REMOTE_ADDR'] != '88.175.76.125' &&  $_SERVER['REMOTE_ADDR'] != '78.239.48.79')
//{
//    header('HTTP/1.0 403 Forbidden');
//    exit('You are not allowed to access this file. Check Weekinsport for more information.');
//}


$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

Debug::enable();
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
