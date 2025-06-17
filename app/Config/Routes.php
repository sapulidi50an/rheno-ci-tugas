<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProductController::index');
    $routes->post('', 'ProductController::create');
    $routes->post('edit/(:any)', 'ProductController::edit/$1');
    $routes->get('delete/(:any)', 'ProductController::delete/$1');
    $routes->get('download','ProductController::download');
});


$routes->group('produk_category', function ($routes) {
    $routes->get('', 'ProductCategoryController::index');
    $routes->post('create', 'ProductCategoryController::create'); 
    $routes->post('edit/(:num)', 'ProductCategoryController::edit/$1');
    $routes->get('delete/(:num)', 'ProductCategoryController::delete/$1');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});


$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);
$routes->get('contact', 'ContactController::index', ['filter' => 'auth']);

$routes->get('faq', 'Home :: faq', ['filter' => 'auth']);
$routes->get('profile', 'Home :: profile', ['filter' => 'auth' ]);
$routes->get('contact', 'Home :: contact', ['filter' => 'auth' ]);

$routes->post('produk_category/create', 'ProductCategoryController::create');
$routes->get('product/download', 'ProductController::download');
$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);