<?php

use App\Controllers\IntervencionsController;
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
$routes->get('/formulariTiquet', 'TiquetController::createTiquet', ['filter'=>'Autentica']);
$routes->post('/formulariTiquet', 'TiquetController::createTiquet_post', ['filter'=>'Autentica']);  
$routes->get('/tiquets', 'RegistresController::index', ['filter'=>'Autentica']);
$routes->get('/tiquets/emissor', 'RegistresController::index2', ['filter'=>'Autentica']);

$routes->post('/editarTiquet', 'Home::editarTiquet_post', ['filter'=>'Autentica']);
$routes->get('/editarTiquet/(:any)', 'Home::editarTiquet/$1', ['filter'=>'Autentica']);
$routes->get('/tiquets/esborrar/(:any)', 'RegistresController::index/$1', ['filter'=>'Autentica']);
$routes->get('/tiquets/emissor/esborrar/(:any)', 'RegistresController::index2/$1', ['filter'=>'Autentica']);


$routes->post('/tiquets', 'RegistresController::opcions', ['filter'=>'Autentica']);
$routes->get('/eliminarTiquet/(:any)', 'RegistresController::eliminarTiquet/$1', ['filter'=>'Autentica']);


//Inventari
$routes->get('/registreTiquetProfessor', 'RegistresController::index', ['filter'=>'Autentica']);
//$routes->get('/tiquets', 'RegistresController::index');

//Alumnes
$routes->get('/alumnes', 'AlumnesController::registreAlumnes', ['filter'=>'Autentica']);
$routes->get('/alumnes/afegir', 'AlumnesController::crearAlumne', ['filter'=>'Autentica']);
$routes->post('/alumnes/afegir', 'AlumnesController::crearAlumne_post', ['filter'=>'Autentica']);
$routes->get('/alumnes/editar/(:segment)', 'AlumnesController::editarAlumne/$1', ['filter'=>'Autentica']);
$routes->post('/alumnes/editar', 'AlumnesController::editarAlumne_post', ['filter'=>'Autentica']);
$routes->get('/alumnes/esborrar/(:segment)', 'AlumnesController::registreAlumnes/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarAlumne/(:segment)', 'AlumnesController::eliminarAlumne/$1', ['filter'=>'Autentica']);

//Backtickets



//Intervencions
$routes->get('/afegir/intervencio/(:segment)', 'IntervencionsController::createIntervencio/$1', ['filter'=>'Autentica']);
$routes->post('/afegir/intervencio', 'IntervencionsController::createIntervencio_post', ['filter'=>'Autentica']);  
$routes->get('/editar/intervencio', 'IntervencionsController::editarIntervencio', ['filter'=>'Autentica']);
$routes->get('/editar/intervencio', 'IntervencionsController::editarIntervencio_post', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:segment)/esborrar/(:segment)', 'IntervencionsController::eliminarIntervencio_vista/$1/$2', ['filter'=>'Autentica']);
$routes->get('/eliminarIntervencio/(:segment)/(:segment)', 'IntervencionsController::eliminarIntervencio/$1/$2', ['filter'=>'Autentica']);

//Vista Tiquets - Registre Intervencions
$routes->post('/tiquets/cercar', 'TiquetController::viewTiquet_post', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:any)', 'TiquetController::viewTiquet/$1', ['filter'=>'Autentica']);

// Canvi de language, Descarregar
$routes->get('/canviLanguage', 'Home::canviLanguage');
$routes->get('/descarregar/(:segment)', 'TiquetController::descarregar/$1', ['filter'=>'Autentica']);

