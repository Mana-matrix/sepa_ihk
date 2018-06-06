<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\tl_member;
class transactions extends Model
{
    protected $fillable = ['tr_type', 'tr_date'];

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
    public static function unconfirmed(){
        return self::where('confirmed','!=',1)->get();
    }
    public function tl_member()
    {
        return $this->belongsToMany('App\tl_member')->as('transactions')->withTimestamps();
    }
    public function tl_member_transactions(){
        return $this->hasMany('App\tl_member_transactions');
    }
}
