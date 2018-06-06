<?php

namespace App\Http\Controllers;

use App\transactions;

class TransactionsController extends Controller
{
    public function getLast()
    {
        $tr_first = transactions :: where('tr_type', 'first') -> orderBy('id', 'desc') -> first();
        $tr_follow = transactions :: where('tr_type', 'follow') -> orderBy('id', 'desc') -> first();
        return response() -> json([$tr_first, $tr_follow], 200);
    }

    public function get($type)
    {
        if ($type === 'open')
            return $this -> getOpenTransactions();
        else if ($type === 'last')
            return $this -> getLastTransactions();
        else if ($type === 'generate')
            return $this -> getGenerateTransactions();
    }
    public function cofirmTransactions($id_1,$id_2){

    }
    public function deleteTransactions($id_1,$id_2){

    }
    public function getOpenTransactions()
    {
        $first = transactions :: where('tr_type', 'first') -> where('confirmed', '!=', 1) -> withCount([
            'tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow = transactions ::where('tr_type', 'follow') -> where('confirmed', '!=', 1)
            -> withCount(['tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow -> fee;
        $first -> fee;
        return response() -> json(['first'=>$first,'follow'=> $follow], 200);
    }

    public function getLastTransactions()
    {
        $first = transactions :: where('tr_type', 'first') -> where('confirmed', '=', 1)  -> withCount(['tl_member_transactions'])-> orderBy('id', 'desc') -> first();
        $follow = transactions :: where('tr_type', 'follow') -> where('confirmed', '=', 1) -> withCount(['tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow -> fee;
        $first -> fee;
        return response() -> json(['first'=>$first,'follow'=> $follow], 200);
    }

    public function getGenerateTransactions()
    {
        $first = transactions :: where('tr_type', 'first') -> where('confirmed', '=', 1) -> withCount(['tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow = transactions :: where('tr_type', 'follow') -> where('confirmed', '=', 1) -> withCount(['tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow -> fee;
        $first -> fee;
        return response() -> json(['first'=>$first,'follow'=> $follow], 200);
    }
}
