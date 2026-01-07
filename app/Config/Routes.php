<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('expenses/create', 'Home::create');
$routes->get('expenses/delete/(:num)', 'Home::delete/$1');
$routes->get('expenses/exportCSV', 'Home::exportCSV');
$routes->get('expenses/exportXlsx', 'Home::exportXlsx');
