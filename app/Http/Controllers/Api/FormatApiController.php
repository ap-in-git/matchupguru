<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Format;
use App\Model\Game;
use Illuminate\Http\Request;

class FormatApiController extends Controller
{


    /**
     * @url  /api/game-format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){


          $request_game=strtolower($request->get("game"));
          $game=Game::where("name",$request_game)->firstorfail();
          $game_id=$game->id;


      if($game_id==null)
          abort(404);

      $unfiltered_formats=Format::where("game_id",$game_id)->orderBy("name","asc")->get();

      $filtered_formats=[];

      foreach ($unfiltered_formats as $format){
           $temp_format=[
             "id"=>$format->id,
               "name"=>$format->name
           ];
           array_push($filtered_formats,$temp_format);
        }

        return response()->json([
            "formats"=>$filtered_formats
        ],200);

    }
}
