<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/(:segment)', 'Home::index/$1');
//$routes->get('{locale}/(:segment)', 'Home::index/$1');



 // Routes provisionals per primer accés per login amb google
$routes->get('/', 'RegistresController::index');
$routes->get('{locale}', 'RegistresController::index');



$routes->get('{locale}/login', 'Home::login');
$routes->post('{locale}/login', 'Home::login_post');
$routes->get('{locale}/loginSelect', 'Home::loginSelect');
$routes->get('{locale}/formulariTiquet', 'Home::createTiquet');
$routes->post('{locale}/formulariTiquet', 'Home::createTiquet_post');
$routes->match(['GET','POST'],'{locale}/crudadmin',"RegistresController::index");

//Tiquets
$routes->get('ca/registreTiquetProfessor', 'RegistresController::registreTiquetsProfessor');
$routes->get('{locale}/registreTiquetProfessor', 'RegistresController::registreTiquetsProfessor');
$routes->get('{locale}/registreTiquetSSTT', 'RegistresController::registreTiquetsSSTT');
$routes->get('{locale}/registreTiquetEmissor', 'RegistresController::registreTiquetEmissor');

//Inventari
$routes->get('{locale}/registreTiquetProfessor', 'RegistresController::registreTiquetsProfessor');
//$routes->get('{locale}/tiquets', 'RegistresController::index');
//Alumnes
//$routes->get('{locale}/alumne', '')

//Backtickets


//Intervencions


