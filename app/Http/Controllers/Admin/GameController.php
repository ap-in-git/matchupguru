<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Game;
use App\Model\Format;
class GameController extends Controller
{
    public function all(Request $request){
      if(!$request->ajax())
      abort(404);

     $games=Game::select("name","id")->get();
     return response()->json($games,200);

    }

    public function getFormatByGameid(Request $request,$id){
      if(!$request->ajax())
      abort(404);
      $formats=Format::select("name","id")->where("game_id",$id)->get();
      return response()->json($formats,200);



    }
}
