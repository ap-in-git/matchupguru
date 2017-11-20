<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function game(){
       return $this->belongsTo("App\Model\Game");
    }

    public function decks(){
        return $this->hasMany("App\Model\Deck");
    }
      public function leagues(){
        return $this->hasMany("App\Model\League");
    }
    public function tournaments(){
        return $this->hasMany("App\Model\Tournament");
    }


}
