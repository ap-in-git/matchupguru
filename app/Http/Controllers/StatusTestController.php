<?php
namespace App\Http\Controllers;

use App\Http\Requests\MatchStatusRequest;
use App\Model\Deck;
use App\Model\Match;
use Auth;
use GuzzleHttp\Psr7\Request;
use Response;
use DB;
use View;

class StatusTestController extends Controller
{

    public function guestData(MatchStatusRequest $request){

        $seasons=$request->get("seasons");
        $events=$request->get("events");
        $versions=$request->get("versions");
        $return_data=$this->getMatchStatusAll($seasons,$events,$versions);
        return response()->json([
                "match_data"=>$return_data,
                "data_type"=>2
            ]
            ,200);

    }

    /**
     * @param MatchStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newData(MatchStatusRequest $request){

     $seasons=$request->get("seasons");
     $events=$request->get("events");
     $versions=$request->get("versions");
     $data_type=$request->get("data_type");
        $return_data=[];
     if($data_type===1) {
         $return_data=$this->getMatchStatusSingle($seasons,$events,$versions);


     }elseif($data_type===2){
         $return_data=$this->getMatchStatusAll($seasons,$events,$versions);



     }elseif ($data_type===3){
         $me_matches__temp_data=$this->getMatchStatusSingle($seasons,$events,$versions);
         if($me_matches__temp_data["status"]===0)
         {
             $return_data=[
                 "status"=>0,
                 "Message"=>"No result found"

             ];
         } else{
             $all_matches_temp_data=$this->getMatchStatusAll($seasons,$events,$versions);

             $me_matches__data=$me_matches__temp_data["data"];

             $all_matches_data=$all_matches_temp_data["data"];

             $return_sending_array=[];

             foreach ($me_matches__data as $key => $m) {

                 if(strtolower($m["opposing_deck_version"])===strtolower($all_matches_data[$key]["opposing_deck_version"]))
                 {

                     $single_match=[
                         "deck"=>$m["deck"],
                         "version"=>$m["version"],
                         "opp_deck_name"=>$m["opposing_deck_name"],
                         "opp_deck_version"=>$m["opposing_deck_version"],
                         "wins"=>$m["wins"],
                         "loss"=>$m["loss"],
                         "match_win_me"=>$m["match_win"],
                         "match_win_all"=>$all_matches_data[$key]["match_win"],
                         "game_win_me"=>$m["game_win"],
                         "game_win_all"=>$all_matches_data[$key]["game_win"],
                         "play_pre_me"=>$m["play_pre"],
                         "play_pre_all"=>$all_matches_data[$key]["play_pre"],
                         "draw_pre_me"=>$m["draw_pre"],
                         "draw_pre_all"=>$all_matches_data[$key]["draw_pre"],
                         "play_post_me"=>$m["play_post"],
                         "play_post_all"=>$all_matches_data[$key]["play_post"],
                         "draw_post_me"=>$m["draw_post"],
                         "draw_post_all"=>$all_matches_data[$key]["draw_post"]
                     ];
                     array_push($return_sending_array,$single_match);
                 }
             }
             usort($return_sending_array,Array($this,"cmp"));

            $return_data=[
                "status"=>1,
                "data"=>$return_sending_array

            ];

         }
     }else{
         abort(404);
     }
     return response()->json([
      "match_data"=>   $return_data,
             "data_type"=>$data_type
         ]
         ,200);



    }


    /**
     * @param $seasons
     * @param $events
     * @param $versions
     * @return array
     */
    public function getMatchStatusSingle($seasons, $events, $versions){

        $matches = Match::where("user_id", Auth::user()->id)
            ->whereIn("season_id", $seasons)
            ->whereIn("type", $events)
            ->whereIn("deck_id", $versions)
            ->groupby("matches.opp_deck_id")
            ->get();


        if ($matches->count() === 0) {
            return[
                "status" => 0,
                "Message" => "No result found"
            ];
        }
        $first_matches = [];

        foreach ($matches as $match) {
            $temp_matches = Match::where([["user_id", Auth::user()->id], ["deck_id", $match->deck_id], ["opp_deck_id", $match->opp_deck_id]])
                ->whereIn("season_id", $seasons)
                ->whereIn("type", $events)
                ->get();
            $temp_data = $this->calculatewin($temp_matches);
            $single_match = array_merge($temp_data, ["deck" => $match->deck_id, "opposing_deck" => $match->opp_deck_id]);
            array_push($first_matches, $single_match);
        }

        $sending_array = $this->compareAndCalculate($first_matches, []);
        usort($sending_array,Array($this,"cmp"));

        return [
            "status" => 1,
            "data" => $sending_array
        ];

    }


    /**
     * @param $seasons
     * @param $events
     * @param $versions
     * @return array
     */
    private  function getMatchStatusAll($seasons, $events, $versions){
        $matches = Match::whereIn("season_id", $seasons)
            ->whereIn("type", $events)
            ->whereIn("deck_id", $versions)
            ->groupby("matches.opp_deck_id")
            ->get();
        $opponent_matches=Match::whereIn("season_id",$seasons)
                          ->whereIn("type",$events)
                          ->whereIn("opp_deck_id",$versions)
                         ->groupby("matches.deck_id")->get();



        if ($matches->count() === 0&&$opponent_matches->count()===0) {
            return[
                "status" => 0,
                "Message" => "No result found"
            ];
        }
        $first_matches = [];

        foreach ($matches as $match) {

            $temp_matches = Match::where([ ["deck_id", $match->deck_id], ["opp_deck_id", $match->opp_deck_id]])
                ->whereIn("season_id", $seasons)
                ->whereIn("type", $events)
                ->get();

            $temp_data = $this->calculatewin($temp_matches);
            $single_match = array_merge($temp_data, ["deck" => $match->deck_id, "opposing_deck" => $match->opp_deck_id]);
            array_push($first_matches, $single_match);
        }
        $second_matches=[];
        if($opponent_matches->count()>0){
            foreach ($opponent_matches as $match) {

                $opponent_temp_matches=Match::where([["deck_id",$match->deck_id],["opp_deck_id",$match->opp_deck_id]])
                                            ->whereIn("season_id",$seasons)
                                             ->whereIn("type",$events)
                                             ->get();

                $reversed_matches=[];
                    foreach ($opponent_temp_matches as $key => $m) {
                        $reverse_match=$this->reverseMatch($m);
                        array_push(  $reversed_matches, $reverse_match);
                    }






                $some=$this->calculatewin(
                    (object)$reversed_matches
                );
                $single_match=array_merge($some,["deck"=>$match->deck_id,"opposing_deck"=>$match->opp_deck_id]);
                array_push($second_matches,$single_match);


            }
        }
        $sending_array = $this->compareAndCalculate($first_matches,$second_matches);
        usort($sending_array,Array($this,"cmp"));
        return [
            "status" => 1,
            "data" => $sending_array
        ];

    }

  private function cmp($a,$b){
    return strcmp($a["opposing_deck_version"], $b["opposing_deck_version"]);

  }










    /**
     * @param $matches
     * @return array
     */
    public function calculatewin($matches){

$win=0;
$loss=0;
$total_g1_play_win=0;
$total_g1_play_loss=0;
$total_g1_draw_win=0;
$total_g1_draw_loss=0;
$total_g2_play_win=0;
$total_g2_play_loss=0;
$total_g2_draw_win=0;
$total_g2_draw_loss=0;
$total_win=0;
$total_loss=0;

foreach ($matches as $key => $match) {
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
 //45(7 match) ,53,65,83(1 match) ,128(1 matches),160(2 matches),169(1 matches),181,183,195,196,203,209
    //214,215,219,222,225,226,228,230,246,251

    /**
     * @param $first_matches
     * @param $second_matches
     * @return array
     */
    private function compareAndCalculate($first_matches, $second_matches){
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
   foreach ($first_matches as $key => $match) {
//        dd()

     $single_match=$this->getCalculationDone($match);
    $single_match["deck"]=Deck::find($match["deck"])->deck;
    $single_match["version"]=Deck::find($match["deck"])->version;
     $single_match["opposing_deck_name"]=Deck::find($match["opposing_deck"])->deck;
     $single_match["opposing_deck_version"]=Deck::find($match["opposing_deck"])->version;
     array_push($return_array,$single_match);

   }

    foreach ($second_matches as $key => $match) {

      $single_match=$this->getCalculationDone($match);

      $single_match["opposing_deck"]=Deck::find($match["deck"])->deck;
      $single_match["opposing_deck_version"]=Deck::find($match["deck"])->version;
      $single_match["deck"]=Deck::find($match["opposing_deck"])->deck;
      $single_match["version"]=Deck::find($match["opposing_deck"])->verison;
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

      $single_match=$this->getCalculationDone($match);

      $single_match["deck"]=Deck::find($match["opposing_deck"])->deck;
      $single_match["version"]=Deck::find($match["opposing_deck"])->version;
      $single_match["opposing_deck_name"]=Deck::find($match["deck"])->deck;
      $single_match["opposing_deck_version"]=Deck::find($match["deck"])->version;

      array_push($return_array,$single_match);
    }

    foreach ($first_matches as $key => $match) {
      $single_match=$this->getCalculationDone($match);
     $single_match["deck"]=Deck::find($match["deck"])->deck;
     $single_match["version"]=Deck::find($match["deck"])->version;
      $single_match["opposing_deck_name"]=Deck::find($match["opposing_deck"])->deck;
      $single_match["opposing_deck_version"]=Deck::find($match["opposing_deck"])->version;
      array_push($return_array,$single_match);

    }



  }

return $return_array;

}

    /**
     * @param $match
     * @return object
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


 private function getCalculationDone($match){
      

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
