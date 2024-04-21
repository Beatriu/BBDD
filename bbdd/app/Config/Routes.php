<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes provisionals per primer accés per login amb google
$routes->get('/', 'Home::index');
$routes->get('', 'Home::index');

// Gestió de la sessió i usuaris
$routes->get('/login', 'UsuarisController::login');
$routes->post('/login', 'UsuarisController::login_post');
$routes->get('/loginSelect', 'UsuarisController::loginSelect');
$routes->post('/loginSelect', 'UsuarisController::loginSelect_post');
$routes->get('/logout', 'UsuarisController::logout', ['filter'=>'Autentica']);

// Registres
$routes->match(['GET','POST'],'/crudadmin',"RegistresController::index");

//Tiquets
$routes->get('/formulariTiquet', 'Home::createTiquet', ['filter'=>'Autentica']);
$routes->post('/formulariTiquet', 'Home::createTiquet_post', ['filter'=>'Autentica']);
$routes->get('/registreTiquet', 'RegistresController::index', ['filter'=>'Autentica']);
/*$routes->get('/registreTiquetSSTT', 'RegistresController::index', ['filter'=>'Autentica']);
$routes->get('/registreTiquetEmissor', 'RegistresController::index', ['filter'=>'Autentica']);
*/
//Inventari
$routes->get('/registreTiquetProfessor', 'RegistresController::index', ['filter'=>'Autentica']);
//$routes->get('/tiquets', 'RegistresController::index');

//Alumnes
//$routes->get('/alumne', '');

//Backtickets


//Intervencions


// Canvi de language
$routes->get('/canviLanguage', 'Home::canviLanguage');

