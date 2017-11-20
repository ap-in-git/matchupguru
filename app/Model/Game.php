<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function formats(){
      return $this->hasMany("App\Model\Format","game_id");
    }

    public function decks(){
      return $this->hasMany("App\Model\Deck");
    }

    public function seasons(){
        return $this->hasMany("App\Model\Season");
    }
}
