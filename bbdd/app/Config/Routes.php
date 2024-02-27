<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('{locale}/login', 'Home::login');
$routes->get('{locale}/loginSelect', 'Home::loginSelect');
$routes->get('{locale}/formulariTiquet', 'Home::createTiquet');
