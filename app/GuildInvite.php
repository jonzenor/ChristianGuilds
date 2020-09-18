<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildInvite extends Model
{
    public function guild()
    {
        return $this->belongsTo('App\Guild');
    }

    public function community()
    {
        return $this->belongsTo('App\Community');
    }
}
