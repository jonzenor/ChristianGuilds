<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Guild extends Model
{
    use Searchable;

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

    public function server()
    {
        return $this->belongsTo('App\Server');
    }
}
