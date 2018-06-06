<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('transactions', 'TransactionController@getLast');

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '1234',
        'redirect_uri' => 'http://my-sepa-tool.de/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://my-sepa-tool.de/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://my-sepa-tool.de/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '1234',
            'client_secret' => 'Ti8FumyQdbNreYDkL6xI6B5OJQwiEjkyywX2W0uR',
            'redirect_uri' => 'http://my-sepa-tool.de/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

