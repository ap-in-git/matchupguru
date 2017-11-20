<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  user_id
 * @property mixed|string version
 * @property string slug
 */
class Deck extends Model
{

    public function game(){
      return $this->belongsTo("App\Model\Game","game_id");
    }
    public function format(){
      return $this->belongsTo("App\Model\Format","format_id");
    }


    public function matches(){
      return $this->hasMany("App\Model\Match");
    }

    public function leagues(){
      return $this->hasMany("App\Model\League");
    }

    public function tournaments(){
        return $this->hasMany(\App\Model\Tournament::class);
    }

    public function season(){
        return $this->belongsTo("App\Model\Season");
    }

    public function getDeckAttribute($value){
        return ucfirst($value);
    }
    public function getVersionkAttribute($value){
        return ucfirst($value);
    }



}
