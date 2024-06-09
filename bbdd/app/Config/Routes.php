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


// Tipus dispositiu
$routes->get('/tipus/dispositiu', 'TipusDispositiuController::registreTipusDispositiu', ['filter'=>'Autentica']);
$routes->post('/tipus/dispositiu/afegir', 'TipusDispositiuController::crearTipusDispositiu_post', ['filter'=>'Autentica']);
$routes->get('/tipus/dispositiu/desactivar/(:segment)', 'TipusDispositiuController::registreTipusDispositiu/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarTipusDispositiu/(:segment)', 'TipusDispositiuController::desactivarTipusDispositiu/$1', ['filter'=>'Autentica']);

// Tipus inventari
$routes->get('/tipus/inventari', 'TipusInventariController::registreTipusInventari', ['filter'=>'Autentica']);
$routes->post('/tipus/inventari/afegir', 'TipusInventariController::crearTipusInventari_post', ['filter'=>'Autentica']);
$routes->get('/tipus/inventari/desactivar/(:segment)', 'TipusInventariController::registreTipusInventari/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarTipusInventari/(:segment)', 'TipusInventariController::desactivarTipusInventari/$1', ['filter'=>'Autentica']);

// Tipus intervenció
$routes->get('/tipus/intervencio', 'TipusIntervencioController::registreTipusIntervencio', ['filter'=>'Autentica']);
$routes->post('/tipus/intervencio/afegir', 'TipusIntervencioController::crearTipusIntervencio_post', ['filter'=>'Autentica']);
$routes->get('/tipus/intervencio/desactivar/(:segment)', 'TipusIntervencioController::registreTipusIntervencio/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarTipusIntervencio/(:segment)', 'TipusIntervencioController::desactivarTipusIntervencio/$1', ['filter'=>'Autentica']);


// Curs
$routes->get('/tipus/curs', 'CursController::registreCurs', ['filter'=>'Autentica']);
$routes->post('/tipus/curs/afegir', 'CursController::crearCurs_post', ['filter'=>'Autentica']);
$routes->get('/tipus/curs/desactivar/(:segment)', 'CursController::registreCurs/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarCurs/(:segment)', 'CursController::desactivarCurs/$1', ['filter'=>'Autentica']);

// Població
$routes->get('/tipus/poblacio', 'PoblacioController::registrePoblacio', ['filter'=>'Autentica']);
$routes->post('/tipus/poblacio/afegir', 'PoblacioController::crearPoblacio_post', ['filter'=>'Autentica']);
$routes->get('/tipus/poblacio/desactivar/(:segment)', 'PoblacioController::registrePoblacio/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarPoblacio/(:segment)', 'PoblacioController::desactivarPoblacio/$1', ['filter'=>'Autentica']);

// Comarca
$routes->get('/tipus/comarca', 'ComarcaController::registreComarca', ['filter'=>'Autentica']);
$routes->post('/tipus/comarca/afegir', 'ComarcaController::crearComarca_post', ['filter'=>'Autentica']);
$routes->get('/tipus/comarca/desactivar/(:segment)', 'ComarcaController::registreComarca/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarComarca/(:segment)', 'ComarcaController::desactivarComarca/$1', ['filter'=>'Autentica']);

// Professors
$routes->get('/professor', 'LlistaAdmesosController::registreLlistaAdmesos', ['filter'=>'Autentica']);
$routes->post('/professor/afegir', 'LlistaAdmesosController::crearLlistaAdmesos_post', ['filter'=>'Autentica']);
$routes->get('/professor/desactivar/(:segment)', 'LlistaAdmesosController::registreLlistaAdmesos/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarProfessor/(:segment)', 'LlistaAdmesosController::desactivarLlistaAdmesos/$1', ['filter'=>'Autentica']);
$routes->get('/eliminarTotsProfessors', 'LlistaAdmesosController::desactivarTotsLlistaAdmesos', ['filter'=>'Autentica']);

// DADES
$routes->get('/dades', 'DadesController::registreDades', ['filter'=>'Autentica']);
$routes->post('/dades/(:segment)', 'DadesController::descarregarDades/$1', ['filter'=>'Autentica']);
$routes->post('/dades/(:segment)', 'DadesController::descarregarDades/$1', ['filter'=>'Autentica']);


//CENTRES
$routes->get('/centres', 'CentresController::registreCentres', ['filter'=>'Autentica']);
$routes->post('/centres/afegir', 'CentresController::crear_centre_post', ['filter'=>'Autentica']);
$routes->get('/formulariCentre', 'CentresController::crear_centre', ['filter'=>'Autentica']);
$routes->get('/centres/filtre/(:any)', 'CentresController::filtrar_centre/$1', ['filter'=>'Autentica']);
$routes->get('/centres/editar/(:any)', 'CentresController::editar_centre/$1', ['filter'=>'Autentica']);
$routes->post('/centres/editar', 'CentresController::editar_centre_post', ['filter'=>'Autentica']);

//Filtre CENTRES
$routes->post('/filtreCentres', 'CentresController::filtrePost', ['filter'=>'Autentica']);
$routes->post('/eliminarFiltreCentres', 'CentresController::eliminarFiltre', ['filter'=>'Autentica']);
