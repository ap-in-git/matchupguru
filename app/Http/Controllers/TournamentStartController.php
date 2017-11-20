<?php

namespace App\Http\Controllers;

use App\Model\Deck;
use App\Model\Game;
use App\Model\Match;
use App\Model\Season;
use App\Model\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TournamentStartController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  start(Request $request){
        $this->validate($request,[
            "deck_id"=>"required|exists:decks,slug",
            "name"=>"required|max:255",
            "size"=>"required|max:255",
            "event_type"=>"required|numeric"
        ]);

        $deck=Deck::where("slug",$request->get("deck_id"))->firstorfail();


        return view("user.tournament.start",[
            "deck_id"=>$deck->id,
            "tournament_name"=>$request->name,
            "tournament_size"=>$request->size,
            "event_type"=>$request->event_type
        ]);

    }

    public function store(Request $request){

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
            "tournament_name"=>"required|max:255",
            "type"=>"required|in:3,7",
            "tournament_size"=>"required|in:1,2,3,4",
            "finish"=>"required|in:0,1"
        ]);

        if ($request->has("tournament_id")){
           $tournament=Tournament::where("slug",$request->get("tournament_id"))->firstorfail();

             if($tournament->user_id!=Auth::user()->id)
                 abort(404);

             if($request->get("finish")==1){
                 $tournament->completed=1;
                 $tournament->save();
             }
        }else{
            $tournament=new Tournament();
            $tournament->name=$request->tournament_name;
            $tournament->size=$request->tournament_size;
            $tournament->slug=uniqid(true);
            $tournament->deck_id=$request->deck_id;

            $tournament->season_id=$this->getDefaultSeason("magic");
            if($request->type==3){
                $tournament->connection=1;
            }else{
                $tournament->connection=0;
            }

            $tournament->user_id=Auth::user()->id;

            $tournament->save();
        }



        $match=new Match();
        $match->user_id=Auth::user()->id;

        $match->tournament_id=$tournament->id;

        if ($request->top_8==true){
            $match->top_8=1;
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

        $match->type=$request->type;

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

    public function continueTournament($slug){
        $tournament=Tournament::where("slug",$slug)->firstorfail();
        if($tournament->user_id!=Auth::user()->id)
            abort(404);
        if($tournament->connection==1){
            $event_type=3;
        }else{
            $event_type=7;
        }
        return view("user.tournament.continue",[
           "deck_id"=>$tournament->deck_id,
            "event_type"=>$event_type,
            "tournament_name"=>$tournament->name,
            "tournament_size"=>$tournament->size,
            "slug"=>$tournament->slug
        ]);
    }

    public function complete(Request $request){
       $this->validate($request,[
           "id"=>"required"
       ]);
       $tournament=Tournament::where("slug",$request->get("id"))->firstorfail();
       if($tournament->user_id!=Auth::user()->id)
           abort(404);

       $tournament->completed=1;
       $tournament->save();
       Session::flash("success","Tournament Completed");

       return redirect()->back();
    }


    public function loadActiveTournamentData($slug){
       $tournament=Tournament::where("slug",$slug)->firstorFail();

       if($tournament->user_id!=Auth::user()->id)
           abort(404);

       if ($tournament->completed==1)
           abort(404);

       $deck=Deck::findorfail($tournament->deck_id);
       $format=$deck->format;
       $opponent_decks=Deck::select("name","id")->where("format_id",$format->id)->orderBy("name","asc")->get();



       return response()->json([
           "magic_name"=>Auth::user()->magic_name,
            "deck_name"=>$deck->name,
           "format_id"=>$format->id,
           "format_name"=>$format->name,
           "opponent_decks"=>$opponent_decks
       ]);


    }
}
