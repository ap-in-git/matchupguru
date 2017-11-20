<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{

    public function matches(){
        return $this->hasMany(\App\Model\Match::class);
    }

    public function deck(){
        return $this->belongsTo(Deck::class);
    }
    public function season(){
        return $this->belongsTo(Season::class);
    }
}
