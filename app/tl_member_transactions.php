<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tl_member_transactions extends Model
{
    //
    public function transaction()
    {
        return $this->hasOne('App\transaction');
    }
}
