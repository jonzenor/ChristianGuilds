<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Game extends Model
{
    use Searchable;
    
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
