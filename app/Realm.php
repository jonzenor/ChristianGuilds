<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function servers()
    {
        return $this->hasMany('App\Server');
    }
}
