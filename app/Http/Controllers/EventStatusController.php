<?php

namespace App\Http\Controllers;

use App\Model\League;
use App\Model\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventStatusController extends Controller
{
 public function check(Request $request)
    {
       if(!$request->ajax())
           abort(404);

           $previous_league=League::where([
               ["user_id",Auth::user()->id],
               ["type",$request->currentEvent],
               ["format_id",$request->formatid],
               ["completed",0],
               ["reseted",0]
           ])->first();



           if($previous_league){
               return response()->json([
                   "message"=>"League is already progess with the same format",
                   "status"=>0
               ],200);
           }else{
               return response()->json([
                   "message"=>null,
                   "status"=>1
               ],200);

           }



       }

    public function getActiveEvent($game){
        if(strtolower($game)=='magic'){

            $leagues=League::where([["completed",0],["reseted",0],["user_id",Auth::user()->id]])->orderBy("created_at","asc")->get();
            $total_leagues=$leagues->count();

            $tournaments=Tournament::where([["completed",0],["user_id",Auth::user()->id]])->orderBy("created_at","desc")->get();

            $total_tournament=$tournaments->count();


            if($total_leagues==0&&$total_tournament==0)
                return response()->json(["data"=>0]);

            return response()->json([
                "data"=>view("user.ajax.active",compact("leagues","total_leagues","tournaments","total_tournament"))->render()
            ]);
        }else{
            abort(404);
        }
    }


    public function test(){

        return view("admin.test");

    }

       


}
