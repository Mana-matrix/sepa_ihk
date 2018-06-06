<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usertransaction extends Model
{
   protected $fillable=['user_id','transaction_id','fee','iban'];
  //public $with=['transactions'];
}
