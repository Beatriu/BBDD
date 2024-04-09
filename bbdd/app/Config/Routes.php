<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes provisionals per primer accés per login amb google
$routes->get('/', 'RegistresController::index');
$routes->get('', 'RegistresController::index');

$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::login_post');
$routes->get('/loginSelect', 'Home::loginSelect');
$routes->get('/formulariTiquet', 'Home::createTiquet');
$routes->post('/formulariTiquet', 'Home::createTiquet_post');
$routes->match(['GET','POST'],'/crudadmin',"RegistresController::index");

//Tiquets
$routes->get('/registreTiquetProfessor', 'RegistresController::registreTiquetsProfessor');
$routes->get('/registreTiquetSSTT', 'RegistresController::registreTiquetsSSTT');
$routes->get('/registreTiquetEmissor', 'RegistresController::registreTiquetEmissor');

//Inventari
$routes->get('/registreTiquetProfessor', 'RegistresController::registreTiquetsProfessor');
//$routes->get('/tiquets', 'RegistresController::index');

//Alumnes
//$routes->get('/alumne', '');

//Backtickets


//Intervencions


// Canvi de language
$routes->get('/canviLanguage', 'Home::canviLanguage');

