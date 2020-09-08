<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    public function members()
    {
        return $this->belongsToMany('App\User', 'community_members', 'community_id', 'user_id')->withPivot('position');
    }
}
