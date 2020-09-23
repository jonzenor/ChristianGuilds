<?php

namespace App;

use Carbon\Carbon;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use Searchable;

    public function members()
    {
        return $this->belongsToMany('App\User', 'community_members', 'community_id', 'user_id')->withPivot('position');
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

    public function invites()
    {
        return $this->hasMany('App\GuildInvite');
    }
}
