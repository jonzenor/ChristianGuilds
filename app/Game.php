<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Game extends Model
{
    use Searchable;

    public $algoliaSettings = [
        'attributesToIndex' => [
            'name',
        ],
        'attributesToRetrieve' => [
            'id',
            'name',
        ],
        'attributesForFaceting' => [    //here you specify which attributes could be used on the facetFilters search args
            'status'
        ]
    ];
    
    public function shouldBeSearchable()
    {
        return ($this->status == "confirmed");
    }

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

    public function realms()
    {
        return $this->hasMany('App\Realm');
    }
}
