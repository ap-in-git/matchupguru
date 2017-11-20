<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    public function game(){
      return $this->belongsTo("App\Model\Game","game_id");
    }

    public function decks(){
      return $this->hasMany("App\Model\Deck","format_id");
    }


    public function leagues(){
      return $this->hasMany("App\Model\League","format_id");
    }
}
