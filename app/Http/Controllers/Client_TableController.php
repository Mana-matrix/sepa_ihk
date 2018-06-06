<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
class Client_TableController extends Controller
{



    public static function index()
    {
        if (Auth::guest())
            return redirect()->route('login');
        else {
            $cr = new ClientController();
            $cr = $cr->index();
            $new_clients = array();
            $old_clients = array();
            $disables_clients = array();

            foreach ($cr as $client) {
                if ($client->disable == 1 || $client->xt_memberfee < 2.50)
                    array_push($disables_clients, $client);
                else
                    array_push($old_clients, $client);
            }

            return view('sepa_tool')->withCharacters([
                'new' => $new_clients,
                'old' => $old_clients,
                'disabled' => $disables_clients]);
        }
    }
}
