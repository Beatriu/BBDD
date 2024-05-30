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
$routes->get('/tiquets/pdf/(:segment)', 'TiquetController::descarregarTiquetPDF/$1', ['filter'=>'Autentica']);

$routes->post('/tiquets/editar', 'TiquetController::editarTiquet_post', ['filter'=>'Autentica']);
$routes->get('/tiquets/editar/(:any)', 'TiquetController::editarTiquet/$1', ['filter'=>'Autentica']);
$routes->get('/tiquets/esborrar/(:any)', 'RegistresController::index/$1', ['filter'=>'Autentica']);
$routes->get('/tiquets/emissor/esborrar/(:any)', 'RegistresController::index2/$1', ['filter'=>'Autentica']);


$routes->post('/tiquets', 'RegistresController::opcions', ['filter'=>'Autentica']);
$routes->get('/eliminarTiquet/(:any)', 'RegistresController::eliminarTiquet/$1', ['filter'=>'Autentica']);


//FILTRE
$routes->post('/filtre', 'RegistresController::filtrePost', ['filter'=>'Autentica']);
$routes->post('/eliminarFiltre', 'RegistresController::eliminarFiltre', ['filter'=>'Autentica']);

//Inventari
$routes->get('/inventari', 'InventarisController::registreInventari', ['filter'=>'Autentica']);
$routes->get('/inventari/afegir', 'InventarisController::crearInventari', ['filter'=>'Autentica']);
$routes->post('/inventari/afegir', 'InventarisController::crearInventari_post', ['filter'=>'Autentica']);
$routes->get('/inventari/esborrar/(:segment)', 'InventarisController::registreInventari/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarInventari/(:segment)', 'InventarisController::eliminarInventari/$1', ['filter'=>'Autentica']);

$routes->get('/tiquets/(:segment)/assignar/(:segment)', 'InventarisController::assignarInventari/$1/$2', ['filter'=>'Autentica']);
$routes->post('/tiquets/(:segment)/assignar/(:segment)', 'InventarisController::assignarInventari_post/$1/$2', ['filter'=>'Autentica']);
$routes->get('/inventari/desassignar/(:segment)', 'InventarisController::desassignarInventari/$1', ['filter'=>'Autentica']);

//FILTRE INVENTARI
$routes->post('/filtreInventari', 'InventarisController::filtrePost', ['filter'=>'Autentica']);
$routes->post('/eliminarFiltreInventari', 'InventarisController::eliminarFiltre', ['filter'=>'Autentica']);

//Alumnes
$routes->get('/alumnes', 'AlumnesController::registreAlumnes', ['filter'=>'Autentica']);
$routes->get('/alumnes/afegir', 'AlumnesController::crearAlumne', ['filter'=>'Autentica']);
$routes->post('/alumnes/afegir', 'AlumnesController::crearAlumne_post', ['filter'=>'Autentica']);
$routes->get('/alumnes/editar/(:segment)', 'AlumnesController::editarAlumne/$1', ['filter'=>'Autentica']);
$routes->post('/alumnes/editar', 'AlumnesController::editarAlumne_post', ['filter'=>'Autentica']);
$routes->get('/alumnes/esborrar/(:segment)', 'AlumnesController::registreAlumnes/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarAlumne/(:segment)', 'AlumnesController::eliminarAlumne/$1', ['filter'=>'Autentica']);

//FILTRE ALUMNES
$routes->post('/filtreAlumnes', 'AlumnesController::filtrePost', ['filter'=>'Autentica']);
$routes->post('/eliminarFiltreAlumnes', 'AlumnesController::eliminarFiltre', ['filter'=>'Autentica']);

//Backtickets



//Intervencions
$routes->get('/afegir/intervencio/(:segment)', 'IntervencionsController::createIntervencio/$1', ['filter'=>'Autentica']);
$routes->post('/afegir/intervencio', 'IntervencionsController::createIntervencio_post', ['filter'=>'Autentica']);  
$routes->get('/editar/intervencio/(:segment)/(:segment)', 'IntervencionsController::editarIntervencio/$1/$2', ['filter'=>'Autentica']);
$routes->post('/editar/intervencio', 'IntervencionsController::editarIntervencio_post', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:segment)/esborrar/(:segment)', 'IntervencionsController::eliminarIntervencio_vista/$1/$2', ['filter'=>'Autentica']);
$routes->get('/eliminarIntervencio/(:segment)/(:segment)', 'IntervencionsController::eliminarIntervencio/$1/$2', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:segment)/intervencio/(:segment)', 'TiquetController::viewTiquet/$1/$2', ['filter'=>'Autentica']);

//Vista Tiquets - Registre Intervencions
$routes->post('/tiquets/cercar', 'TiquetController::viewTiquet_post', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:segment)', 'TiquetController::viewTiquet/$1', ['filter'=>'Autentica']);
$routes->get('/tiquets/(:segment)/(:segment)', 'TiquetController::viewTiquet/$1', ['filter'=>'Autentica']);

// Canvi de language, Descarregar
$routes->get('/canviLanguage', 'Home::canviLanguage');
$routes->get('/descarregar/(:segment)', 'TiquetController::descarregar/$1', ['filter'=>'Autentica']);

