<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('{locale}/login', 'Home::login');
$routes->get('{locale}/loginSelect', 'Home::loginSelect');
$routes->get('{locale}/formulariTiquet', 'Home::createTiquet');
$routes->post('{locale}/formulariTiquet', 'Home::createTiquet_post');
$routes->match(['get','post'],'{locale}/crudadmin',"RegistresController::index");

$routes->get('{locale}/registreTiquetSSTT', 'RegistresController::registreTiquets');


