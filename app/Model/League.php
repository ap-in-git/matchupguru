<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public function matches(){
      return $this->hasMany("App\Model\Match");
    }

    public function format(){
      return $this->belongsTo("App\Model\Format","format_id");
    }

    public function deck(){
      return $this->belongsTo("App\Model\Deck");
    }
    public function season(){
      return $this->belongsTo("App\Model\Season");
    }
}
