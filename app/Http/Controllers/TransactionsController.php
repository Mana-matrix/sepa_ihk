<?php

namespace App\Http\Controllers;
use App\transactions;
class TransactionsController extends Controller
{
    public function getLast()
    {


            $tr_first = transactions:: where('tr_type', 'first')->orderBy('id', 'desc')->first();
            $tr_follow = transactions:: where('tr_type', 'follow')->orderBy('id', 'desc')->first();
            return response()->json([$tr_first, $tr_follow], 200);

    }
    public function get($type){
        if ($type==='open')
            return $this->getOpentransactions();
        else
            return $this->getLasttransactions();
    }
    public function getOpentransactions(){
        $first = transactions:: where('tr_type', 'first')->where('confirmed','!=',1)->withCount([
            'tl_member_transactions']);
        //$first = $first->sum('fee');

        $first = $first->orderBy('id', 'desc')->first();
        $function = function($q){
          $q ->raw("(select sum(tl.fee)from tl_member_transaction as tl as fee where tl_id=id)as fee");

        };
        $follow = transactions::select(['*',])->where('tr_type', 'follow')->$function()->where('confirmed','!=',1)
           ->withCount([
            'tl_member_transactions'])->orderBy('id', 'desc')->first();

       // dump($follow->toSql());


        return response()->json([$first, $follow], 200);

    }
    public function getLasttransactions(){
        $first = transactions:: where('tr_type', 'first')->where('confirmed','=',1)->orderBy('id', 'desc')->first();
        $follow = transactions:: where('tr_type', 'follow')->where('confirmed','=',1)->orderBy('id', 'desc')->first();
        return response()->json([$first, $follow], 200);
    }
}
