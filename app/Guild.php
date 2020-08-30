<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function getCreatedAtAttribute($value)
    {
        $now = Carbon::now();
        $diff = $now->diffInDays($value);
        if ($diff < 1) {
            $readable = "Today";
        } else {
            $readable = $now->subDays($diff)->diffForHumans(); //->diffInDays()->diffForHumans();
        }

        return $readable;
    }
}
