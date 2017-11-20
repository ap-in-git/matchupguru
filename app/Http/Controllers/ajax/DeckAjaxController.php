<?php

namespace App\Http\Controllers\ajax;

use App\Http\Controllers\Controller;
use App\Model\Deck;
use Illuminate\Http\Request;

class DeckAjaxController extends Controller
{
    public function getDeckByFormatAndSeason(Request $request){
     $this->validate($request,[
         "season"=>"required|numeric",
         "format"=>"required|numeric"
     ]);

     $decks=Deck::select("id","name")->where([
         ["format_id",$request->get("format")],
         ["season_id",$request->get("season")],
         ["verified",1]
     ])->orderBy("name","asc")->get();

     return response()->json([
         "decks"=>$decks
     ],200);

    }
}
