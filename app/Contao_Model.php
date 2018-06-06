<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 class Contao_Model extends Model
 {
     /**
      * @var string overwriting timestamp - format
      */
     protected $dateFormat = 'U';
     /**
      * The name of the "created at" column.
      *
      * @var string
      */
     const CREATED_AT = 'createdOn';
     /**
      * The name of the "updated at" column.
      *
      * @var string
      */
     const UPDATED_AT = 'tstamp';

     /**
      * overwrites getTable - no plural!
      * @return mixed|string
      */
     public function getTable()
     {
         if (!isset($this -> table)) {
             return str_replace(
                 '\\', '', Str ::snake(class_basename($this))
             );
         }
         return $this -> table;
     }

 }