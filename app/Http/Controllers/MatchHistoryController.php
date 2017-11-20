<?php

namespace App\Http\Controllers;

use App\Model\League;
use App\Model\Match;
use App\Model\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MatchHistoryController extends Controller{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
{
    $event_type=$request->get("event");
    $seasons=$request->get("seasons");
    $versions=$request->get("versions");


    if($event_type===1||$event_type===2||$event_type===6){
        $leauges=League::where([["type",$event_type],["user_id",Auth::user()->id]])
                        ->whereIn("season_id",$seasons)
                        ->whereIn("deck_id",$versions)
                        ->orderBy("created_at","desc")->get();
        $total=$leauges->count();

       $views=View::make("user.ajax.league")->with("leagues",$leauges)->with("total",$total)->render();

       return response()->json([
           "view"=>$views
       ],200);
    }


    //Tournament

    if($event_type===3||$event_type===9){
        if($event_type){
            $connection=1;
        }else{
            $connection=0;
        }

        $tournaments=Tournament::where([["connection",$connection],["user_id",Auth::user()->id]])
                     ->whereIn("season_id",$seasons)
                      ->whereIn("deck_id",$versions)
            ->orderBy("created_at","desc")
                      ->get();
         $total=$tournaments->count();
        $views=View::make("user.ajax.tournament")->with("tournaments",$tournaments)->with("total",$total)->render();
        return response()->json([
            "view"=>$views
        ],200);
    }

    $matches=Match::where([["type",$event_type],[Auth::user()->id]])
        ->whereIn("season_id",$seasons)
        ->whereIn("deck_id",$versions)
        ->orderBy("created_at","desc")
        ->get();
    $total=$matches->count();
    $views=View::make("user.ajax.single")->with("matches",$matches)->render();
    return response()->json([
        "view"=>$views
    ],200);

}

}
