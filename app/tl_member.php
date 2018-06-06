<?php

namespace App;
use Illuminate\Support\Carbon;


class tl_member extends Contao_Model
{
    //protected $fillable = ['last_transaction'];
    public $with=['transactions'];

    /**
     * links skills to person
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transactions()
    {
        return $this->belongsToMany('App\transactions')->withTimestamps();
    }

}
