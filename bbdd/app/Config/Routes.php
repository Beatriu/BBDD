<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('{locale}/login', 'Home::login');
$routes->post('{locale}/login', 'Home::login_post');
$routes->get('{locale}/loginSelect', 'Home::loginSelect');
$routes->get('{locale}/formulariTiquet', 'Home::createTiquet');
$routes->post('{locale}/formulariTiquet', 'Home::createTiquet_post');
$routes->match(['GET','POST'],'{locale}/crudadmin',"RegistresController::index");

//Tiquets
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