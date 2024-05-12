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
$routes->get('/registreTiquet/emissor', 'RegistresController::index2', ['filter'=>'Autentica']);

$routes->post('/editarTiquet', 'Home::editarTiquet_post', ['filter'=>'Autentica']);
$routes->get('/editarTiquet/(:any)', 'Home::editarTiquet/$1', ['filter'=>'Autentica']);
//TODO: Cuidado amb aquesta ruta perque funciona per a SSTT pero a veure si donarà problemes amb professor
$routes->get('/registreTiquet/esborrar/(:any)', 'RegistresController::index/$1', ['filter'=>'Autentica']);
$routes->get('/registreTiquet/emissor/esborrar/(:any)', 'RegistresController::index2/$1', ['filter'=>'Autentica']);


$routes->post('/registreTiquet', 'RegistresController::opcions', ['filter'=>'Autentica']);
$routes->get('/eliminarTiquet/(:any)', 'RegistresController::eliminarTiquet/$1', ['filter'=>'Autentica']);
//$routes->match(['GET','POST'],'/registreTiquet',"RegistresController::index");
/*$routes->get('/registreTiquetSSTT', 'RegistresController::index', ['filter'=>'Autentica']);
$routes->get('/registreTiquetEmissor', 'RegistresController::index', ['filter'=>'Autentica']);
*/

//Vista Tiquets
$routes->post('/vistaTiquet/cercar', 'TiquetController::viewTiquet_post', ['filter'=>'Autentica']);
$routes->get('/vistaTiquet/(:any)', 'TiquetController::viewTiquet/$1', ['filter'=>'Autentica']);

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
$routes->get('/afegir/intervencio/(:segment)', 'IntervencionsController::createIntervencio', ['filter'=>'Autentica']);
$routes->post('/afegir/intervencio', 'IntervencionsController::createIntervencio_post', ['filter'=>'Autentica']);  
$routes->get('/editar/intervencio', 'IntervencionsController::editarIntervencio', ['filter'=>'Autentica']);
$routes->get('/editar/intervencio', 'IntervencionsController::editarIntervencio_post', ['filter'=>'Autentica']);

// Canvi de language, Descarregar
$routes->get('/canviLanguage', 'Home::canviLanguage');
$routes->get('/descarregar/(:segment)', 'Home::descarregar/$1', ['filter'=>'Autentica']);

