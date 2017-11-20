<?php
namespace App\Http\Controllers;

use App\Model\Tournament;
use Illuminate\Http\Request;
use App\Model\League;
use App\Model\Format;
use App\Model\Deck;

use App\Model\Match;
use Auth;
use Response;
use DB;
use View;

class StatusController extends Controller
{

    public function show(Request $request){

     $this->validate($request,[
         "game"=>"required",
         "event"=>"required|numeric",
         "view"=>"required|numeric",
         "data_type"=>"required|numeric",
         "deck"=>"required|numeric"

     ]);
     $return_data=null;
     $data_type=$request->get("data_type");
     $view_type=$request->get("view");
     $event_id=$request->get("event");
     $deck_id=$request->get("deck");
     $season_id=$request->get("season");
     if($request->game=="magic")
     {
          if($view_type==1){
             $data=$this->getMatchStatusByEventType($deck_id,$data_type,$event_id,$season_id);
             $return_data=[
                 "data"=>$data,
                  "view_type"=>1
             ];
             return response()->json($return_data,200);

          }

          if($view_type==4){
             $matches=Match::where([
                 ["user_id",Auth::user()->id],
                 ["deck_id",$deck_id],
                 ["type",$event_id],
                 ["season_id",$season_id]

             ])->get();

             if($matches->count()==0){
                 return response()->json([
                     "data"=>0,
                     "view_type"=>4
                 ]);
             }else{
                 $deck_name=Deck::find($deck_id)->name;
                 $return_data=[
                     "data"=>View::make("user.ajax.single")->with("deck",$deck_name)->with("matches",$matches)->render(),
                     "view_type"=>4
                 ];
                 return response($return_data);
             }

          }


          //For active leagues and tournament
         if($view_type==2){
              if($event_id==3){
                  $tournaments=Tournament::where([
                      ["user_id",Auth::user()->id],
                      ["deck_id",$deck_id],
                      ["completed",0],
                      ["connection",1],
                      ["season_id",$season_id]
                      ])->orderBy("created_at","desc")->get();
                  $total=$tournaments->count();
                  $return_data=[
                      "data"=>View::make("user.ajax.tournament")->with("tournaments",$tournaments)->with("total",$total)->render(),
                      "view_type"=>4
                  ];

                  return response()->json($return_data,200);

              }
              if($event_id==7){

                      $tournaments=Tournament::where([
                          ["user_id",Auth::user()->id],
                          ["deck_id",$deck_id],
                          ["completed",0],
                          ["connection",0],
                          ["season_id",$season_id]
                      ])->orderBy("created_at","desc")->get();
                      $total=$tournaments->count();
                      $return_data=[
                          "data"=>View::make("user.ajax.tournament")->with("tournaments",$tournaments)->with("total",$total)->render(),
                          "view_type"=>4
                      ];

                      return response()->json($return_data,200);


              }


             $leagues=League::where(
                 [["game_id",1],
                     ["deck_id",$deck_id],
                     ["user_id",Auth::user()->id],
                     ["type",$event_id],
                     ["completed",0],
                     ["reseted",0],
                     ["season_id",$season_id]
                 ])->orderBy("created_at","desc")->get();

             $total=$leagues->count();
             $return_data=[
                 "data"=>View::make("user.ajax.active")->with("leagues",$leagues)->with("total",$total)->render(),
                 "view_type"=>4
             ];

             return response()->json($return_data,200);
     }

     if($view_type==0)
     {

         if($event_id==3){
             $tournaments=Tournament::where([
                 ["user_id",Auth::user()->id],
                 ["deck_id",$deck_id],
                 ["connection",1],
                 ["season_id",$season_id]
             ])->orderBy("created_at","desc")->get();
             $total=$tournaments->count();
//           dd($tournaments);
             $return_data=[
                 "data"=>View::make("user.ajax.tournament")->with("tournaments",$tournaments)->with("total",$total)->render(),
                 "view_type"=>4
             ];

             return response()->json($return_data,200);

         }
         if($event_id==7){

             $tournaments=Tournament::where([
                 ["user_id",Auth::user()->id],
                 ["deck_id",$deck_id],
                 ["connection",0],
                 ["season_id",$season_id]
                 ])->orderBy("created_at","desc")->get();
             $total=$tournaments->count();
             $return_data=[
                 "data"=>View::make("user.ajax.tournament")->with("tournaments",$tournaments)->with("total",$total)->render(),
                 "view_type"=>4
             ];

             return response()->json($return_data,200);


         }

         $leagues=League::where([["user_id",Auth::user()->id],
             ["game_id",1],
             ["deck_id",$deck_id],
             ["type",$event_id],
             ["season_id",$season_id]
         ])->orderBy("created_at","desc")->get();
         $total=$leagues->count();

         $return_data=[
             "data"=>View::make("user.ajax.active")->with("leagues",$leagues)->with("total",$total)->render(),
             "view_type"=>0
         ];


         return response()->json($return_data,200);




     }


     }





    }


    private function getMatchStatusByEventType($deck_id,$data_type,$event_id,$season_id){
        if ($data_type==0){
            $data=$this->getMatchStatusByDeckCalculationAndEvent($deck_id,$event_id,$season_id);
            $return_array=[
                "data"=>$data,
                "data_type"=>0
            ];

        }elseif ($data_type==1){
            $data=$this->getMatchStatusVs($deck_id,$event_id,$season_id);
            $return_array=[
                "data"=>$data,
                "data_type"=>1
            ];
        }else{
            $data=$this->getMatchStatusAll($deck_id,$event_id,$season_id);
            $return_array=[
                "data"=>$data,
                "data_type"=>2
            ];
        }

        return $return_array;
    }

  private function cmp($a,$b){
    return strcmp($a["opposing_deck"], $b["opposing_deck"]);

  }
/**
 * [index description]
 * @method index
 * @param  Request $request [description]
 * @param  [type]  $game    [description]
 * @param  [type]  $id      [description]
 * @return [type]           [Returns the view of active leagues]
 */
   public function index(Request $request,$game,$id){
     if(!$request->ajax())
        abort(404);
    if($game=='magic'){
  $leagues=League::where(
    [["game_id",1],
    ["user_id",Auth::user()->id],
    ["completed",0],
    ["reseted",0],
    ])->orderBy("created_at","desc")->get();
$total=$leagues->count();
  return Response::view("user.ajax.active",["leagues"=>$leagues,"total"=>$total]);
}else{
  return null;
}
   }

/**
 * [getLeagueByFormat description]
 * Get leagues by format
 * @method getLeagueByFormat
 * @param  Request           $request [description]
 * @return [View]                     [description]
 */
   public function getLeagueByFormat(Request $request){
   if($request->status==0){
     $leagues=League::where([
       ["format_id",$request->id],
       ["user_id",Auth::user()->id],
       ["completed",0],
       ["reseted",0]
       ])->orderBy("created_at","desc")->get();

       if($leagues->count()==0){
         return response(0);
       }
   }else{
     $leagues=League::where([["format_id",$request->id],["user_id",Auth::user()->id]])->orderBy("created_at","desc")->get();
     if($leagues->count()==0){
       return response(0);
     }
   }

   $total=$leagues->count();

     return Response::view("user.ajax.empty",["leagues"=>$leagues,"total"=>$total]);
   }

/**
 * Get leagues By Deck
 * @method getLeagueByDeck
 * @param  Request         $request [description]
 * @param  [type]          $game    [description]
 * @param  [type]          $deck    [description]
 * @param  [type]          $format  [description]
 * @param  [type]          $view    [description]
 * @return [type]                   [description]
 */
   public function getLeagueByDeck(Request $request,$game,$deck,$view){
     if(!$request->ajax())
     abort(404);
     if($game=="magic"){
       if($view==0){
         $leagues=League::where([["user_id",Auth::user()->id],
         ["game_id",1],
         ["deck_id",$deck],
         ["completed",0],["reseted",0]])
         ->orderBy("created_at","desc")->get();
         if($leagues->count()==0){
           return response()->json(0,200);
         }
         $total=$leagues->count();
        return Response::view("user.ajax.active",["leagues"=>$leagues,"total"=>$total]);
       }
       if($view==2){
           $leagues=League::where([["user_id",Auth::user()->id],
           ["game_id",1],
           ["deck_id",$deck]])
          ->orderBy("created_at","desc")->get();
           if($leagues->count()==0){
             return response()->json(0,200);
           }

           $total=$leagues->count();

   return Response::view("user.ajax.empty",["leagues"=>$leagues,"total"=>$total]);

}
   }
   }


   /**
    * [getAllLeague description]
    * This gets all the leagues
    * @method getAllLeague
    * @param  Request      $request [description]
    * @return [type]                [description]
    */
   public function getAllLeague(Request $request){
     if(!$request->ajax())
      abort(404);

      if($request->status==0){
        $leagues=League::where(
          [["game_id",1],
          ["user_id",Auth::user()->id],
          ["completed",0],
          ["reseted",0]
          ])->orderBy("created_at","desc")->get();
      }else{
        $leagues=League::where(
          [["game_id",1],
          ["user_id",Auth::user()->id],
          ])->orderBy("created_at","desc")->get();
      }
      $total=$leagues->count();
     return Response::view("user.ajax.empty",["leagues"=>$leagues,"total"=>$total]);
   }


/**
 * [getMatchStatusByDeck description]
 * Returns Only me match Status by deck
 * @method getMatchStatusByDeck
 * @param  Request              $request [description]
 * @param  [type]               $game    [description]
 * @param  [type]               $deck_id [description]
 * @return [Json]               $sending_array    [description]
 */
   public function getMatchStatusByDeck(Request $request,$game,$deck_id){
    //  if(!$request->ajax())
    //    abort(404);

  $sending_array=$this->getMatchStatusByDeckCalculation($deck_id);

  if($sending_array==0)
  return response(0);

  usort($sending_array,Array($this,"cmp"));

  return response()->json($sending_array,200);
 }

/**
 * [getMatchStatusByDeckCalculation description]
 * This does the actual calcuation of only me match status
 * @method getMatchStatusByDeckCalculation
 * @param  [type]                          $deck_id [description]
 * @return [type]                                   [description]
 */
   private function getMatchStatusByDeckCalculationAndEvent($deck_id,$event_id,$season_id){
    $deck=Deck::findorfail($deck_id);
     $matches=Match::select("opp_deck_id","deck_id")->where([["deck_id",$deck_id
     ],["user_id",Auth::user()->id],["type",$event_id],["season_id",$season_id]])->groupby("matches.opp_deck_id")->get();


       if($matches->count()==0){
         return 0;
       }

        $first_matches=[];

       if($matches->count()>0){
         foreach ($matches as $key => $match) {

           $some=$this->calulatewin(
         Match::where([["deck_id",$deck_id],["opp_deck_id",$match->opp_deck_id],["user_id",Auth::user()->id],["type",$event_id],["season_id",$season_id]])->get()
           );

      $single_match=array_merge($some,["deck"=>$deck->id,"opposing_deck"=>$match->deck->id]);
           array_push($first_matches,$single_match);
         }
       }
   $second_matches=[];
   $sending_array=$this->compareAndCalcualte($first_matches, $second_matches);
    return $sending_array;

 }

/**
 * [getMatchStatusVs description]
 * Return the me vs all player data
 * @method getMatchStatusVs
 * @param  Request          $request [description]
 * @param  [type]           $game    [description]
 * @param  [type]           $deck_id [description]
 * @return [Json]                   [description]
 */
 private function getMatchStatusVs($deck_id,$event_id,$season_id){



  $first_matches=$this->getMatchStatusByDeckCalculationAndEvent($deck_id,$event_id,$season_id);
   if($first_matches==0)
   return 0;

  $second_matches=$this->getMatchStatusAllCalculation($deck_id,$event_id,$season_id);

  $return_sending_array=[];

  foreach ($first_matches as $key => $m) {
     if($m["opposing_deck"]==$second_matches[$key]["opposing_deck"])
       {


        $single_match=[
          "deck_name"=>$m["deck_name"],
          "opposing_deck"=>$m["opposing_deck"],
          "wins"=>$m["wins"],
          "loss"=>$m["loss"],
          "match_win_me"=>$m["match_win"],
          "match_win_all"=>$second_matches[$key]["match_win"],
          "game_win_me"=>$m["game_win"],
          "game_win_all"=>$second_matches[$key]["game_win"],
          "play_pre_me"=>$m["play_pre"],
          "play_pre_all"=>$second_matches[$key]["play_pre"],
          "draw_pre_me"=>$m["draw_pre"],
          "draw_pre_all"=>$second_matches[$key]["draw_pre"],
          "play_post_me"=>$m["play_post"],
          "play_post_all"=>$second_matches[$key]["play_post"],
          "draw_post_me"=>$m["draw_post"],
          "draw_post_all"=>$second_matches[$key]["draw_post"]
        ];
        array_push($return_sending_array,$single_match);
       }
  }

    usort($return_sending_array,Array($this,"cmp"));
  return $return_sending_array;

 }

private function getMatchStatusAll($deck_id,$event_id,$season_id){
  $return_array=$this->getMatchStatusAllCalculation($deck_id,$event_id,$season_id);
  if($return_array==0)
  return 0;

    usort($return_array,Array($this,"cmp"));

  return $return_array;
}





/**
 * [getMatchStatusAllCalculation description]
 * Does the actual calcualtion of  all player
 * @method getMatchStatusAllCalculation
 * @param  [type]                       $deck_id [description]
 * @return [Araay]                                [description]
 */
   private function getMatchStatusAllCalculation($deck_id,$event_id,$season_id){
    $deck=Deck::findorfail($deck_id);

     $matches=Match::select("opp_deck_id","deck_id")->where([["deck_id",$deck_id
     ],["type",$event_id],["season_id",$season_id]])->groupby("matches.opp_deck_id")->get();

     $opponent_matches=Match::select("deck_id")->where([[
       "opp_deck_id",$deck_id
     ],["type",$event_id],["season_id",$season_id]])->groupby("matches.deck_id")->get();
       if($matches->count()==0&&$opponent_matches->count()==0){
         return 0;
       }

        $first_matches=[];

       if($matches->count()>0){
         foreach ($matches as $key => $match) {

           $some=$this->calulatewin(
         Match::where([["deck_id",$deck_id],["opp_deck_id",$match->opp_deck_id],["type",$event_id],["season_id",$season_id]])->get()
           );

      $single_match=array_merge($some,["deck"=>$deck->id,"opposing_deck"=>$match->deck->id]);
           array_push($first_matches,$single_match);
         }
       }

       $second_matches=[];
        if($opponent_matches->count()>0)
        {
          foreach ($opponent_matches as $key => $match) {
            $some_matches=  Match::where([["opp_deck_id",$deck_id],["deck_id",$match->deck_id],["type",$event_id],["season_id",$season_id]])->get();
            $reversed_matches=[];
            foreach ($some_matches as $key => $m) {
              $reverse_match=$this->reverseMatch($m);
              array_push(  $reversed_matches, $reverse_match);
            }

            $some=$this->calulatewin(
(object)$reversed_matches
            );

             $opp_deck=Deck::findorfail($match->deck_id);
               $single_match=array_merge($some,["deck"=>$opp_deck->id,"opposing_deck"=>$deck->id]);
            array_push($second_matches,$single_match);
          }
        }

   $sending_array=$this->compareAndCalcualte($first_matches, $second_matches);

    return $sending_array;

 }







/**
 * [calulatewin description]
 * Calculate the overall total callcuation  of the matches
 * @method calulatewin
 * @param  [type]      $matches [A obect of match]
 * @return [type]               [description]
 */
public function calulatewin($matches){

$win=0;
$loss=0;
$match_win=0;
$game_win=0;
$i=0;
$total_win=0;
$total_loss=0;
$play_pre=0;
$draw_pre=0;
$play_post=0;
$draw_post=0;
$g1_play=0;
$g1_draw=0;
$total_g1_play_win=0;
$total_g1_play_loss=0;
$total_g1_draw_win=0;
$total_g1_draw_loss=0;
$total_g2_play_win=0;
$total_g2_play_loss=0;
$total_g2_draw_win=0;
$total_g2_draw_loss=0;
$total_g3_play_win=0;
$total_g3_play_loss=0;
$total_g3_draw_win=0;
$total_g3_draw_loss=0;
$total_win=0;
$total_loss=0;

foreach ($matches as $key => $match) {
  // dd($match);
  if($match->final_result==0)
   {
     $loss++;
   }else{
     $win++;
   }


 if($match->g1_result=='w'){
   $total_win++;
 }else{
   $total_loss++;
 }
 if($match->g2_result=='w'){
   $total_win++;
 }else{
   $total_loss++;
 }

 if($match->g3_result=='w')
   $total_win++;

 if($match->g3_result=="l")
 $total_loss++;


if($match->g1_result=='w'){
  if($match->g1_play_draw=='p'){
   $total_g1_play_win++;
  }else{
 $total_g1_draw_win++;
  }

}else{
  if($match->g1_play_draw=='p'){
   $total_g1_play_loss++;
  }else{
 $total_g1_draw_loss++;
  }
}


if($match->g2_result=='w'){
  if($match->g2_play_draw=='p'){
   $total_g2_play_win++;
  }else{
 $total_g2_draw_win++;
  }

}else{
  if($match->g2_play_draw=='p'){
   $total_g2_play_loss++;
  }else{
 $total_g2_draw_loss++;
  }
}
if($match->g3_result!=null){
  if($match->g3_result=='w'){
   if($match->g3_play_draw=='p'){
    $total_g2_play_win++;

   }else{
  $total_g2_draw_win++;
   }
  }

  if($match->g3_result=='l'){
   if($match->g3_play_draw=='p'){
    $total_g2_play_loss++;
   }else{
  $total_g2_draw_loss++;
   }
  }

}
}
return [
   "win"=>$win,
   "loss"=>$loss,
   "total_win"=>$total_win,
   "total_loss"=>$total_loss,
   "total_g1_play_win"=>$total_g1_play_win,
   "total_g1_play_loss"=>$total_g1_play_loss,
    "total_g1_draw_win"=>$total_g1_draw_win,
   "total_g1_draw_loss"=>$total_g1_draw_loss,
   "total_g2_play_win"=>$total_g2_play_win,
   "total_g2_play_loss"=>$total_g2_play_loss,
   "total_g2_draw_win"=>$total_g2_draw_win,
   "total_g2_draw_loss"=>$total_g2_draw_loss
];
}


/**
 * [compareAndCalcualte description]
 * Compare two array and combines them
 * @method compareAndCalcualte
 * @param  [type]              $first_matches  [description]
 * @param  [type]              $second_matches [description]
 * @return [type]                              [array]
 */
private function compareAndCalcualte($first_matches,$second_matches){
  $first_length=sizeof($first_matches);
  $second_length=sizeof($second_matches);
      $return_array=[];
  if($first_length>=$second_length){

    foreach ($first_matches as $k1 => $match) {
       foreach ($second_matches as $k2 => $m) {
           if($match["opposing_deck"]==$m["deck"]) {
          $first_matches[$k1]["win"]=$match["win"]+$m["win"];
          $first_matches[$k1]["loss"]=$match["loss"]+$m["loss"];
          $first_matches[$k1]["total_win"]=$match["total_win"]+$m["total_win"];
          $first_matches[$k1]["total_loss"]=$match["total_loss"]+$m["total_loss"];
          $first_matches[$k1]["total_g1_play_win"]=$match["total_g1_play_win"]+$m["total_g1_play_win"];
          $first_matches[$k1]["total_g1_play_loss"]=$match["total_g1_play_loss"]+$m["total_g1_play_loss"];
          $first_matches[$k1]["total_g1_draw_win"]=$match["total_g1_draw_win"]+$m["total_g1_draw_win"];
          $first_matches[$k1]["total_g1_draw_loss"]=$match["total_g1_draw_loss"]+$m["total_g1_draw_loss"];
          $first_matches[$k1]["total_g2_play_win"]=$match["total_g2_play_win"]+$m["total_g2_play_win"];
          $first_matches[$k1]["total_g2_play_loss"]=$match["total_g2_play_loss"]+$m["total_g2_play_loss"];
          $first_matches[$k1]["total_g2_draw_win"]=$match["total_g2_draw_win"]+$m["total_g2_draw_win"];
          $first_matches[$k1]["total_g2_draw_loss"]=$match["total_g2_draw_loss"]+$m["total_g2_draw_loss"];
          unset($second_matches[$k2]);
        }

       }
    }
// dd($second_matches);
   foreach ($first_matches as $key => $match) {
     $single_match=$this->getCalcultionDone($match);
    $single_match["deck_name"]=Deck::find($match["deck"])->name;
     $single_match["opposing_deck"]=Deck::find($match["opposing_deck"])->name;
     array_push($return_array,$single_match);

   }

    foreach ($second_matches as $key => $match) {
      $single_match=$this->getCalcultionDone($match);

      $single_match["opposing_deck"]=Deck::find($match["deck"])->name;
      $single_match["deck_name"]=Deck::find($match["opposing_deck"])->name;
      array_push($return_array,$single_match);
    }




  }else{
    foreach ($second_matches as $k1 => $match) {
       foreach ($first_matches as $k2 => $m) {
           if(($match["deck"]==$m["opposing_deck"])){
          $second_matches[$k1]["win"]=$match["win"]+$m["win"];
          $second_matches[$k1]["loss"]=$match["loss"]+$m["loss"];
          $second_matches[$k1]["total_win"]=$match["total_win"]+$m["total_win"];
          $second_matches[$k1]["total_loss"]=$match["total_loss"]+$m["total_loss"];
          $second_matches[$k1]["total_g1_play_win"]=$match["total_g1_play_win"]+$m["total_g1_play_win"];
          $second_matches[$k1]["total_g1_play_loss"]=$match["total_g1_play_loss"]+$m["total_g1_play_loss"];
          $second_matches[$k1]["total_g1_draw_win"]=$match["total_g1_draw_win"]+$m["total_g1_draw_win"];
          $second_matches[$k1]["total_g1_draw_loss"]=$match["total_g1_draw_loss"]+$m["total_g1_draw_loss"];
          $second_matches[$k1]["total_g2_play_win"]=$match["total_g2_play_win"]+$m["total_g2_play_win"];
          $second_matches[$k1]["total_g2_play_loss"]=$match["total_g2_play_loss"]+$m["total_g2_play_loss"];
          $second_matches[$k1]["total_g2_draw_win"]=$match["total_g2_draw_win"]+$m["total_g2_draw_win"];
          $second_matches[$k1]["total_g2_draw_loss"]=$match["total_g2_draw_win"]+$m["total_g2_draw_win"];
            unset($first_matches[$k2]);
          }
       }
    }

    foreach ($second_matches as $key => $match) {
      $single_match=$this->getCalcultionDone($match);

      $single_match["deck_name"]=Deck::find($match["opposing_deck"])->name;
      $single_match["opposing_deck"]=Deck::find($match["deck"])->name;

      array_push($return_array,$single_match);
    }

    foreach ($first_matches as $key => $match) {
      $single_match=$this->getCalcultionDone($match);
     $single_match["deck_name"]=Deck::find($match["deck"])->name;
      $single_match["opposing_deck"]=Deck::find($match["opposing_deck"])->name;
      array_push($return_array,$single_match);

    }



  }

return $return_array;

}

/**
 * [reverseMatch description]
 * Reverses a match
 * @method reverseMatch
 * @param  [type]       $match [array]
 * @return [type]              [object]
 */
 private function reverseMatch($match){
  $reversed_match["g1_play_draw"]=$match->g1_play_draw=='p'?'d':'p';
   $reversed_match["g1_result"]=$match->g1_result=='w'?'l':'w';
   $reversed_match["g2_play_draw"]=$match->g2_play_draw=='p'?'d':'p';
  $reversed_match["g2_result"]=$match->g2_result=='w'?'l':'w';
   if ($match->g3_play_draw!=null) {
     $reversed_match["g3_play_draw"]=$match->g3_play_draw=='p'?'d':'p';
   }else{
      $reversed_match["g3_play_draw"]=null;
   }
   if ($match->g3_result!=null) {
     $reversed_match["g3_result"]=$match->g3_result=='w'?'l':'w';
   }else{
     $reversed_match["g3_result"]=null;
   }
  if($match->final_result==1){
  $reversed_match["final_result"]=0;
  }else{
    $reversed_match["final_result"]=1;
  }
   $temp=$match->match_win;
   $reversed_match["match_win"]=$match->match_loss;
  $reversed_match["match_loss"]=$temp;
  $reversed_match["opponent_name"]=$match->opponent_name;
  $reversed_match["deck_id"]=$match->deck_id;
  $reversed_match["opp_deck_id"]=$match->opp_deck_id;
  return (object)$reversed_match;
 }


 private function getCalcultionDone($match){
   $match_stat["game_win"]= $match["total_win"]/($match["total_win"]+$match["total_loss"]);
   $match_stat["match_win"]=$match["win"]/($match["win"]+$match["loss"]);
   if($match["total_g1_play_win"]==0&&$match["total_g1_play_loss"]==0)
   {
   $match_stat["play_pre"]=0;
   }else{
   $match_stat["play_pre"]=$match["total_g1_play_win"]/($match["total_g1_play_win"]+$match["total_g1_play_loss"]);
   }

   if($match["total_g1_draw_win"]==0&&$match["total_g1_draw_loss"]==0){
 $match_stat["draw_pre"]=0;
   }else{
 $match_stat["draw_pre"]=$match["total_g1_draw_win"]/($match["total_g1_draw_win"]+$match["total_g1_draw_loss"]);
   }
   if($match["total_g2_play_win"]==0&&$match["total_g2_play_loss"]==0){
   $match_stat["play_post"]=0;
   }else{
   $match_stat["play_post"]=$match["total_g2_play_win"]/($match["total_g2_play_win"]+$match["total_g2_play_loss"]);
   }

   if($match["total_g2_draw_win"]==0&&$match["total_g2_draw_loss"]==0){
 $match_stat["draw_post"]=0;
   }else{
 $match_stat["draw_post"]=$match["total_g2_draw_win"]/($match["total_g2_draw_win"]+$match["total_g2_draw_loss"]);
   }
  //
 //  $match_stat["deck"]=Deck::find($match["deck"])->name;
 //  $match_stat["opponent_deck"]=Deck::find($match["opposing_deck"])->name;

  $single_match=[
    "wins"=>$match["win"],
    "loss"=>$match["loss"],
    "match_win"=>round($match_stat["match_win"]*100),
    "game_win"=> round($match_stat["game_win"]*100),
    "play_pre"=>round($match_stat["play_pre"]*100),
    "draw_pre"=>round($match_stat["draw_pre"]*100),
     "play_post"=>round($match_stat["play_post"]*100),
     "draw_post"=>round($match_stat["draw_post"]*100)
  ];

return $single_match;
 }

}
