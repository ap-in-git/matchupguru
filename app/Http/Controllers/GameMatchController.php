<?php

namespace App\Http\Controllers;

use App\Model\Season;
use Illuminate\Http\Request;
use App\Model\Game;
use App\Model\Deck;
use App\Model\Format;
use App\User;
use App\Model\League;
use App\Model\Match;
use App\Model\Statics;

use Session;
use Auth;


class GameMatchController extends Controller
{
    /**
     * @param $game
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function start($game){
      if(Auth::user()->magic_name==null)
       return redirect("/");

    $game=Game::select("id","name")->where("name",$game)->first();
    $formats=Format::select("name","id")->where("game_id",$game->id)->orderBy("name","asc")->get();
    if(!$game){
      abort(404);
    }
    $activeleagues=League::where([["completed",0],["reseted",0],["user_id",Auth::user()->id]])->get();

   $has_active=$activeleagues->count()>0?'1':'0';

    return view("user.format",[
      "has_active"=>$has_active,
      "id"=>$game->id,
      "name"=>strtolower($game->name)
    ]);
  }

public function loaddata(Request $request,$deck_id){
if(!$request->ajax())
  abort(404);

  $user=User::select("name","id","magic_name")->where("id",Auth::user()->id)->first();
  $deck=Deck::select("name","format_id","id")->where([["id",$deck_id]])->first();

  $format=[
    "id"=>$deck->format->id,
    "name"=>$deck->format->name
  ];
$decks=Deck::select("name","id")->where([["format_id",$deck->format->id],["season_id",$this->getDefaultSeason("magic")]])->orderBy("name","asc")->get();

return response()->json([
  "user"=>$user,
  "format"=>$format,
  "decks"=>$decks,
  "deck"=>$deck
],200);


}




public function selectformat($game,$deck,$event){
  if(Auth::user()->magic_name==null)
   return redirect("/");

 if($event!=1&&$event!=2&&$event!=4&&$event!=5&&$event!=6&&$event!=8&&$event!=9)
     abort(404);





$deck=Deck::where("slug",$deck)->firstorfail();

if(!$deck)
abort(404);

$deck_id=$deck->id;

    $previous_league=League::where([
        ["user_id",Auth::user()->id],
        ["type",$event],
        ["format_id",$deck->format->id],
        ["completed",0],
        ["reseted",0]
    ])->first();

    if($previous_league)
        abort(404);



//return view("user.match",compact("deck_id"));


return view("user.match",[
    "deck_id"=>$deck_id,
    "event_type"=>$event
]);
}


public function getFinalData(Request $request,$deck_id,$format_id,$game_id){

   if(!$request->ajax())
   abort(404);

  $datas=Card::where([
    ["user_id",Auth::user()->id],
    ["format_id",$format_id],
    ["game_id",$game_id]
  ])->get();

return response()->json($datas,200);

}

public function formatexist(Request $request){
$league=League::where([["format_id",$request->formatid],
["user_id",Auth::user()->id]
])->latest()->first();
$response=1;
if($league){
if($league->completed==1||$league->reseted==1){
  $response=0;
}

}else{
$response=0;
}
return response()->json($response,200);





}


public function leaguereset(Request $request){
  $this->validate($request,[
    "id"=>"required|numeric"
  ]);

  $league=League::findorfail($request->id);

  $league->reseted=1;

  $league->save();

  Session::flash("success","League Reseted Successfully");

  return redirect()->back();

}


public function leaguecontinue($id){
  if(Auth::user()->magic_name==null)
   return redirect("/");
  $league=League::findorfail($id);
  if($league->completed==1||$league->reseted==1)
    abort(404);

    if($league->user_id!=Auth::user()->id)
    abort(404);

$deck_id=$league->deck_id;
$league_id=$league->id;
return view("user.continue",compact("deck_id","league_id"));

}


public function edit($id){
$match=Match::findorfail($id);
if(Auth::user()->id!=$match->user_id)
abort(404);

return view("user.match.edit",compact("match"));
}

public function loadDataMatch(Request $request,$id){
$match=Match::findorfail($id);
// dd($match);

$user=User::select("name","id","magic_name")->where("id",Auth::user()->id)->first();
$deck=Deck::select("name","format_id","id")->where("id",$match->deck_id )->first();
$format=[
  "id"=>$deck->format->id,
  "name"=>$deck->format->name
];
$decks=Deck::select("name","id")->where("format_id",$deck->format->id)->orderBy("name","asc")->get();

return response()->json([
"user"=>$user,
"format"=>$format,
"decks"=>$decks,
"deck"=>$deck,
"match"=>$match
],200);


}

public function update(Request $request){
  $match=Match::findorfail($request->match_id);
if($match->user_id!=Auth::user()->id)
abort(404);

  $this->validate($request,[
"opponent_name"=>"required|max:255",
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
]);

$league=League::find($match->league_id);

if($match->final_result==1){
  $league->league_win=$league->league_win-1;
}

if($match->final_result==0){
  $league->league_loss=$league->league_loss-1;
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


 $match->opponent_name=$request->opponent_name;


 $match->opp_deck_id=$request->opp_deck_id;

 $match->g1_play_draw=$request->g1;

 $match->g2_play_draw=$request->g2;

 $match->g1_start_size=$request->g1start;

 $match->g1_opp_size=$request->g1value;

 $match->g1_result=$request->w1;

 $match->g2_start_size=$request->g2start;

 $match->g2_opp_size=$request->g2value;

 $match->g2_result=$request->w2;


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


 return response()->json(1,200);
}


public function view($id){
  $match=Match::findorfail($id);
  // dd($id);
$decks=Deck::where("format_id",$match->format_id)->get();
  return view("user.match.view",["match"=>$match,"decks"=>$decks]);
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
