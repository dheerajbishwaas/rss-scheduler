<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'RssController::import');
$routes->get('/rss', 'RssController::import');
$routes->post('/rss/fetch', 'RssController::fetch');
$routes->get('/posts', 'Posts::index');
$routes->post('/posts/reorder', 'Posts::reorder');
$routes->post('/posts/delete', 'Posts::delete');
$routes->get('/posts/assign/(:num)', 'Posts::assign/$1');  // show form
$routes->post('/posts/assign/(:num)', 'Posts::saveAssignment/$1'); // save
$routes->get('/dashboard', 'Posts::dashboard');

