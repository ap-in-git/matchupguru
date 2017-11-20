<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function league(){
      return $this->belongsTo("App\Model\League","league_id");
    }

    public function deck(){
      return $this->belongsTo("App\Model\Deck","opp_deck_id");
    }

    public function tournament(){
        return $this->belongsTo(\App\Model\Tournament::class);
    }
}
