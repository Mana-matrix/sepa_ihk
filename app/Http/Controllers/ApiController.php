<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp;
class ApiController extends Controller
{
   public function getLastTransactions(){

       $client = new \GuzzleHttp\Client();
       $request = $client->get('my-sepa-tool.de/api/transaction');
       $response = $request->getBody();
      // dd($response);
       return view('sepa_tool')->withCharacters($response);
   }

   public static function guzzle(){

       $client = new \GuzzleHttp\Client();
       try {
           $res = $client -> request('GET', 'my-sepa-tool.de/api/transaction', [
               'auth' => ['user', 'pass']
           ]);
       } catch (GuzzleHttp\Exception\GuzzleException $e) {
       }
       // echo $res->getStatusCode();
// "200"
      // echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
      // echo $res->getBody();
       $results=json_decode($res->getBody());
// {"type":"User"...'
        if($res->getStatusCode()==200)
            return view('api_test')->withCharacters($results);
        else return view('to_bad')->withCharacters($res->getStatusCode());
// Send an asynchronous request.

   }
}

