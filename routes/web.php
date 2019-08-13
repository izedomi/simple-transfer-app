<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'SupplierController@index')->name('home');
Route::post('/add-supplier', 'SupplierController@add_supplier');
Route::post('/update-supplier', 'SupplierController@update_supplier');
Route::post('/delete-supplier', 'SupplierController@delete_supplier');
Route::post('/transfer-cash', 'SupplierController@transfer_cash');
Route::post('/finalize-transfer', 'SupplierController@finalize_transfer');
Route::post('/bulk-transfer', 'SupplierController@bulk_transfer');
Route::post('/bulk-transfer-recipients', 'SupplierController@get_bulk_transfer_recipients');
