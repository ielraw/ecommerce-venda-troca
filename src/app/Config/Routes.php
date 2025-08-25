<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');

$routes->group('/', ['namespace' => 'App\\Controllers\\Api'], function($routes) {
    $routes->post('user', 'User::create');
    $routes->get('user/(:num)', 'User::show/$1');
    $routes->put('user/(:num)', 'User::update/$1');
    $routes->post('deal', 'Deal::create');
    $routes->get('deal/(:num)', 'Deal::show/$1');
    $routes->put('deal/(:num)', 'Deal::update/$1');
    $routes->post('deal/search', 'Deal::search');
    $routes->post('deal/(:num)/bid', 'Bid::create/$1');
    $routes->get('deal/(:num)/bid', 'Bid::listBids/$1');
    $routes->get('deal/(:num)/bid/(:num)', 'Bid::show/$1/$2');
    $routes->put('deal/(:num)/bid/(:num)', 'Bid::update/$1/$2');
    $routes->post('deal/(:num)/message', 'Message::create/$1');
    $routes->get('deal/(:num)/message/(:num)', 'Message::show/$1/$2');
});

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
