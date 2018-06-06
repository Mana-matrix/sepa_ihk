<?php

namespace App\Http\Controllers;

use App\transactions;
use App\tl_member_transactions;
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
        $this->confirm($id_1);
        $this->confirm($id_2);
        return redirect() -> route('sepa');
    }
    public function deleteTransactions($id_1,$id_2){
       $response_1= $this->kill($id_1);
        $response_2= $this->kill($id_2);
        if($response_1->status()==200&&$response_2->status()==200)
            return redirect() -> route('sepa');
        else
            echo ($response_1->getData()->message.'<br>'.$response_2->getData()->message);

    }
    public function getOpenTransactions()
    {
        $first = transactions :: where('tr_type', 'first') -> where('confirmed', '!=', 1) -> withCount([
            'tl_member_transactions']) -> orderBy('id', 'desc') -> first();
        $follow = transactions ::where('tr_type', 'follow') -> where('confirmed', '!=', 1)
            -> withCount(['tl_member_transactions']) -> orderBy('id', 'desc') -> first();
       if($first)
        $follow -> fee;
       if($follow)
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
    public function kill($id){
        if (transactions::where('id', '=', $id)->exists()) {
            tl_member_transactions::where('transactions_id','=',$id)->delete();
            transactions::whereId($id)->delete();
            return response() -> json(['message'=>"id $id: deleted"], 200);
        }else return response() -> json(['message'=>"id $id: no matching entry"], 402);
    }
    public function confirm($id){

        transactions::whereId($id)->first()->update(['confirmed'=>1]);
    }
}
