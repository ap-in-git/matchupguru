<?php

namespace App\Http\Controllers;

use App\Model\Game;
use App\Model\League;
use App\Model\Match;
use App\Model\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GameMatchSaveController extends Controller
{
    /**
     * [store description]
     * @method store
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){

        if(!$request->ajax())
            abort(404);
        $this->validate($request,[
            "opponent_name"=>"nullable|max:255",
            "format_id"=>"required|numeric",
            "deck_id"=>"required|numeric",
            "opp_deck_id"=>"required|numeric",
            "g1" => "required|alpha",
            "g2" => "required|alpha",
            "g3" => "required|alpha",
            "w1" => "required|alpha",
            "w2" => "required|alpha",
            "w3" => "required|alpha",
            "result" => "required|alpha",
            "key" => "nullable|max:255",
            "duds" => "nullable|max:255",
            "note" => "nullable|max:255",
            "league_id"=>"nullable|numeric"
        ]);

        if ($request->event_type==1||$request->event_type==2||$request->event_type==6){
            if($request->league_id==null){
                $league=new League();
                $league->game_id=1;
                $league->user_id=Auth::user()->id;

                $league->format_id=$request->format_id;

                $league->deck_id=$request->deck_id;

                if($request->result=='w'){
                    $league->league_win=1;
                }else{
                    $league->league_loss=1;
                }
                $league->type=$request->event_type;
                $league->season_id=$this->getDefaultSeason("magic");

                $league->save();
        }else{

                $league=League::find($request->league_id);
                if($league->user_id!=Auth::user()->id){
                    Session::flash("success","You must be a hacker");
                    return response()->json(1,200);
                }
                if(!$league){
                    Session::flash("success","You must be a hacker");
                    return response()->json(1,200);
                }

                if($league->completed==1||$league->reseted==1){
                    Session::flash("success","You must be a hacker");
                    return response()->json(1,200);
                }

                if($request->result=='w'){
                    $league->league_win=$league->league_win+1;
                }else{
                    $league->league_loss=$league->league_loss+1;
                }

                if($league->league_win+$league->league_loss>=5){
                    $league->completed=1;
                }


                $league->save();
            }


        }




        $match=new Match();
        $match->user_id=Auth::user()->id;


        if ($request->event_type==1||$request->event_type==2||$request->event_type==6){
            $match->league_id=$league->id;
        }


        $match->opponent_name=$request->opponent_name;

        $match->format_id=$request->format_id;

        $match->deck_id=$request->deck_id;

        $match->opp_deck_id=$request->opp_deck_id;

        $match->g1_play_draw=$request->g1;

        $match->g2_play_draw=$request->g2;

        $match->g1_start_size=$request->g1start;

        $match->g1_opp_size=$request->g1value;

        $match->g1_result=$request->w1;

        $match->g2_start_size=$request->g2start;

        $match->g2_opp_size=$request->g2value;

        $match->g2_result=$request->w2;

        $match->type=$request->event_type;

        $match->season_id=$this->getDefaultSeason("magic");

        $match->net_type=$this->getConnectionType($request->event_type);


        if($request->w1 != $request->w2)
        {
            $match->g3_play_draw=$request->g3 ;
            $match->g3_start_size=$request->g3start;
            $match->g3_opp_size=$request->g3value;
            $match->g3_result=$request->w3;
        }

        if($request->w1=='w'){
            if($request->w2=='w'){
                $match->match_win=2;
            }else{
                if($request->w2=='l'){
                    if($request->w3=='w'){

                        $match->match_win=2;
                        $match->match_loss=1;
                    }else{
                        $match->match_win=1;
                        $match->match_loss=2;

                    }

                }
            }


        }
        if($request->w1=='l'){
            if($request->w2=='l'){
                $match->match_loss=2;
            }else{
                if($request->w2=='w'){

                    if($request->w3=='w'){
                        $match->match_win=2;
                        $match->match_loss=1;
                    }else{
                        $match->match_win=1;
                        $match->match_loss=2;

                    }

                }
            }


        }
        if($request->result=='w'){
            $match->final_result=1;
        }else{
            $match->final_result=0;
        }
        $match->key_card=$request->key_card;
        $match->key_card=$request->key;
        $match->duds=$request->duds;
        $match->note=$request->note;
        $match->save();
        Session::flash("success","Match Completed");
        return response()->json(1,200);

    }

     private function getConnectionType($id){
        if($id==1||$id==2||$id==3||$id==4||$id==5){
            return 1;
        }else{
            return 0;
        }
     }

    private function getDefaultSeason($game_name){
        $game=Game::select("id")->where("name",$game_name)->first();
        $season=Season::select("id")->where([
            ["game_id",$game->id],
            ["is_current",1]
        ])->first();


        return $season->id;


    }

}
