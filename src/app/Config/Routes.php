<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API Routes
$routes->group('/', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // User routes
    $routes->post('user', 'User::create');
});
