<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Authentication routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Product routes - grouped with auth filter (using 'produk' to match your application)
$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProductController::index');
    $routes->post('create', 'ProductController::create');
    $routes->post('edit/(:num)', 'ProductController::edit/$1');
    $routes->get('delete/(:num)', 'ProductController::delete/$1');
    $routes->get('download', 'ProductController::download');
    $routes->get('download/(:num)', 'ProductController::download/$1');
});

// Product Category routes - grouped with auth filter
$routes->group('produk_category', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProductCategoryController::index');
    $routes->post('create', 'ProductCategoryController::create'); 
    $routes->post('edit/(:num)', 'ProductCategoryController::edit/$1');
    $routes->get('delete/(:num)', 'ProductCategoryController::delete/$1');
});

// Cart/Keranjang routes - grouped (remove duplicate)
$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

// Transaction routes - grouped
$routes->group('transaksi', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->get('checkout', 'TransaksiController::checkout');
    $routes->get('history', 'TransaksiController::history');
    $routes->match(['get', 'post'], 'edit-nama/(:num)', 'TransaksiController::editNama/$1');
    $routes->post('buy', 'TransaksiController::buy');
});

// API routes for location and cost
$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);

// Static pages
$routes->get('contact', 'Home::contact', ['filter' => 'auth']);
$routes->get('faq', 'Home::faq', ['filter' => 'auth']);
$routes->get('profile', 'Home::profile', ['filter' => 'auth']);

// Alternative route for checkout (if needed)
$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->get('produk/download', 'ProductController::download');

$routes->resource('api', ['controller' => 'ApiController']);