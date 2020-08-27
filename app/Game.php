<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

    public function guilds()
    {
        return $this->hasMany('App\Guild');
    }

    public function guild()
    {
        return $this->hasOne('App\Guild');
    }
}
