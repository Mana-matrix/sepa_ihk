<?php
Auth::routes();
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


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
Route::get('clients/{type}', 'ClientController@getClients');

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/

Route::post('/', 'Client_TableController@index');
Route::post('/sepa','sepa\SepaXML@printSepa');
Route::get('/sepa', [
    'as'         => 'sepa',
    'uses'       => 'Client_TableController@index',
    //'middleware' => 'auth',
]);
Route::get('transactions/{type}', 'TransactionsController@get');
Route::get('transactions/confirm/{id_1}/{id_2}', [
    'as'         => 'confirm',
    'uses'       => 'TransactionsController@cofirmTransactions',
    //'middleware' => 'auth',
]);
Route::get('transactions/delete/{id_1}/{id_2}', [
    'as'         => 'delete',
    'uses'       => 'TransactionsController@deleteTransactions',
    //'middleware' => 'auth',
]);


