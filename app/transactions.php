<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\tl_member;

use Illuminate\Support\Facades\DB;
class transactions extends Model
{
    protected $fillable = ['tr_type', 'tr_date','confirmed'];

    public function getAll(){


    }
    public function clean(){

            /*$items = tl_member ::select('id')->whereHas('transactions', function ($q) {
                $q -> where('confirmed', '!=', 1);
            });*/
            //dump($items->get());
          //  foreach ($items as $item)

        //$item=$item->transactions()->detach($unconfirmed->id);

    }
    public function unconfirmed(){
        return $this->where('confirmed','!=',1)->get();
    }
    public function setFeeAttribute(){

    }
    public function getFeeAttribute(){
        $sql="Select SUM(fee) as fee From tl_member_transactions Where transactions_id=$this->id";
        $count=DB::select($sql);
        $this->attributes['fee']=$count[0]->fee!=null?$count[0]->fee:0;
     return $this->attributes['fee'];
    }


    public function tl_member()
    {

        return $this->belongsToMany('App\tl_member')->as('transactions')->withTimestamps();
    }
    public function tl_member_transactions(){

        return $this->hasMany('App\tl_member_transactions');
    }
}
