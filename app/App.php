<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    public function guild()
    {
        return $this->belongsTo('\App\Guild', 'org_id');
    }

    public function questions()
    {
        return $this->hasMany('\App\Question');
    }
}
