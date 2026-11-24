<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes (Public)
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('logout', 'AuthController::logout');

// Redirect root to dashboard
$routes->get('/', function() {
    return redirect()->to(base_url('dashboard'));
});

// Protected Routes (Harus Login)
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Transaksi: Faktur Penjualan (Admin & Kasir)
    $routes->get('invoices', 'InvoiceController::index');
    $routes->get('invoices/create', 'InvoiceController::create');
    $routes->post('invoices/store', 'InvoiceController::store');
    $routes->get('invoices/(:num)', 'InvoiceController::show/$1');
    $routes->get('invoices/(:num)/print', 'InvoiceController::printInvoice/$1');
    $routes->get('invoices/(:num)/print-thermal', 'InvoiceController::printThermal/$1');
    $routes->post('invoices/(:num)/update-status', 'InvoiceController::updateProcessStatus/$1');
    $routes->get('invoices/delete/(:num)', 'InvoiceController::delete/$1');

    // Transaksi: Penerimaan Kas (Admin & Kasir)
    $routes->get('receipts', 'ReceiptController::index');
    $routes->get('receipts/create', 'ReceiptController::create');
    $routes->post('receipts/store', 'ReceiptController::store');

    // Transaksi: Retur Penjualan (Admin & Kasir)
    $routes->get('returns', 'ReturnController::index');
    $routes->get('returns/create', 'ReturnController::create');
    $routes->get('returns/ajax-lines/(:num)', 'ReturnController::getInvoiceLinesJson/$1');
    $routes->post('returns/store', 'ReturnController::store');

    // Master Pelanggan (Admin CRUD, Kasir Read)
    $routes->get('customers', 'CustomerController::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('customers/create', 'CustomerController::create');
        $routes->post('customers/store', 'CustomerController::store');
        $routes->get('customers/edit/(:num)', 'CustomerController::edit/$1');
        $routes->post('customers/update/(:num)', 'CustomerController::update/$1');
        $routes->get('customers/delete/(:num)', 'CustomerController::delete/$1');
    });

    // Master Jasa & Layanan (Admin CRUD, Kasir Read & AJAX)
    $routes->get('items', 'ItemController::index');
    $routes->get('items/ajax-price/(:num)', 'ItemController::getPriceJson/$1');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('items/create', 'ItemController::create');
        $routes->post('items/store', 'ItemController::store');
        $routes->get('items/edit/(:num)', 'ItemController::edit/$1');
        $routes->post('items/update/(:num)', 'ItemController::update/$1');
        $routes->get('items/delete/(:num)', 'ItemController::delete/$1');
    });

    // Laporan, Pengaturan, & Manajemen User (Khusus Admin)
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('reports/sales', 'ReportController::sales');
        $routes->get('reports/sales/print', 'ReportController::salesPrint');
        $routes->get('reports/sales/export-csv', 'ReportController::exportSalesCsv');
        $routes->get('reports/receivables', 'ReportController::receivables');
        $routes->get('reports/receivables/print', 'ReportController::receivablesPrint');
        $routes->get('reports/invoice-status', 'ReportController::invoiceStatus');

        $routes->get('settings', 'SettingController::index');
        $routes->post('settings/update', 'SettingController::update');

        // Manajemen User (Poin Penilaian Security & User Management)
        $routes->get('users', 'UserController::index');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users/store', 'UserController::store');
        $routes->get('users/edit/(:num)', 'UserController::edit/$1');
        $routes->post('users/update/(:num)', 'UserController::update/$1');
        $routes->get('users/delete/(:num)', 'UserController::delete/$1');
    });
});
