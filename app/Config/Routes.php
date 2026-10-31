<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->post('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Main::index');

    $routes->get('kategori', 'Kategori::index');
    $routes->match(['get', 'post'], 'kategori/index', 'Kategori::index');
    $routes->get('kategori/formtambah', 'Kategori::formtambah');
    $routes->post('kategori/simpandata', 'Kategori::simpandata');
    $routes->get('kategori/formedit/(:num)', 'Kategori::formedit/$1');
    $routes->post('kategori/updatedata', 'Kategori::updatedata');
    $routes->delete('kategori/hapus/(:num)', 'Kategori::hapus/$1');

    $routes->get('satuan', 'Satuan::index');
    $routes->match(['get', 'post'], 'satuan/index', 'Satuan::index');
    $routes->get('satuan/formtambah', 'Satuan::formtambah');
    $routes->post('satuan/simpandata', 'Satuan::simpandata');
    $routes->get('satuan/formedit/(:num)', 'Satuan::formedit/$1');
    $routes->post('satuan/updatedata', 'Satuan::updatedata');
    $routes->delete('satuan/hapus/(:num)', 'Satuan::hapus/$1');

    $routes->get('barang', 'Barang::index');
    $routes->match(['get', 'post'], 'barang/index', 'Barang::index');
    $routes->get('barang/tambah', 'Barang::tambah');
    $routes->post('barang/simpandata', 'Barang::simpandata');
    $routes->get('barang/edit/(:segment)', 'Barang::edit/$1');
    $routes->post('barang/updatedata', 'Barang::updatedata');
    $routes->delete('barang/hapus/(:segment)', 'Barang::hapus/$1');
    $routes->get('barang/gambar/(:segment)', 'Barang::gambar/$1');

    $routes->get('transaksi', 'Transaksi::index');
    $routes->get('transaksi/index', 'Transaksi::index');
    $routes->post('transaksi/simpan', 'Transaksi::simpan');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
