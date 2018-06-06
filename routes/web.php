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

Auth::routes();

Route::get('clients/{type}', 'ClientController@getClients');



Route::get('/sepa/new', function () {
    return view('sepa.new', []);
});
Route::get('/sepa/old', function () {
    return view('sepa.old', []);
});
Route::get('/sepa/disabled', function () {
    return view('sepa.disabled', []);
});

Route::get('/', function () {
    return view('sepa_tool', []);
});

Route::post('/sepa','sepa\SepaXML@printSepa');


//Route::get ('/', 'Client_TableController@index');
Route::post('/', 'Client_TableController@index');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sepa', [
    'as'         => 'sepa',
    'uses'       => 'Client_TableController@index',
    'middleware' => 'auth',
]);
Route::get('transactions/{type}', 'TransactionsController@get');


Route::get('transactions/confirm/{id_1}/{id_2}', [
    'as'         => 'confirm',
    'uses'       => 'TransactionsController@confim',
    'middleware' => 'auth',
]);

Route::get('transactions/delete/{id_1}/{id_2}', [
    'as'         => 'delete',
    'uses'       => 'TransactionsController@delete',
    'middleware' => 'auth',
]);


Route::get ('/api_test', 'ApiController@guzzle');

