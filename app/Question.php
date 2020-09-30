<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function application()
    {
        return $this->belongsTo('\App\App');
    }
}
