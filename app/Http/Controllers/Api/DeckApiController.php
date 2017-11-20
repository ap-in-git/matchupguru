<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Deck;
use App\Model\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profile;

class DeckApiController extends Controller
{
    public function getDeckByFormat(Request $request){
        $this->validate($request,[
            "format"=>"required",
            "season"=>"required"
        ]);

        $format_id=$request->get("format");
        $season_id=$request->get("season");

        $unfiltered_decks=Deck::where([["format_id",$format_id],["season_id",$season_id],["verified",1]])->orderBy("version","asc")->get();

        $filtered_decks=[];

        foreach ($unfiltered_decks as $deck){
            $temp_deck= [
                "name"=>ucfirst($deck->version),
                "slug"=>$deck->slug,

            ];

            array_push($filtered_decks,$temp_deck);
        }

        return response()->json([
            "decks"=>$filtered_decks
        ],200);



    }

    public function getDeckBySlug(Request $request,$slug){

        $unfiltered_deck=Deck::where([["slug",$slug],["verified",1]])->firstorfail();

        if($request->has("detailed")){
            $deck=[
                "name"=>$unfiltered_deck->deck,
                "version"=>ucfirst($unfiltered_deck->version),
                "style"=>$unfiltered_deck->style,
                "format"=>$unfiltered_deck->format->name,
                "season"=>$unfiltered_deck->season->name,
                "description"=>nl2br($unfiltered_deck->description)
            ];

        }else{

            $deck=[
                "name"=>$unfiltered_deck->deck,
                "slug"=>$unfiltered_deck->slug,
                "version"=>ucfirst($unfiltered_deck->version),
                "style"=>$unfiltered_deck->style,
                "format_id"=>$unfiltered_deck->format_id,
                "description"=>$unfiltered_deck->description
            ];


        }

        return response()->json([
            "deck"=>$deck
        ],200);


    }


   public function getDeckByFormatGrouped(Request $request){
       $this->validate($request,[
           "format"=>"required",
           "season"=>"nullable"
       ]);



       $format_id=$request->get("format");
       if($request->has("season")){
           $season_id=$request->get("season");

           $unfiltered_decks=Deck::where([["format_id",$format_id],["season_id",$season_id],["verified",1]])
               ->orderBy("deck","asc")
               ->groupBy("deck")
               ->get();
       }else{
           $unfiltered_decks=Deck::where([["format_id",$format_id], ["verified",1]])
               ->orderBy("deck","asc")
               ->groupBy("deck")
               ->get();

       }


       $filtered_decks=[];

       foreach ($unfiltered_decks as $deck){
           $temp_deck= [
               "name"=>ucfirst($deck->deck),
           ];

           array_push($filtered_decks,$temp_deck);
       }

       return response()->json([
           "decks"=>$filtered_decks
       ],200);


   }


   public function getVersionByGroupedDeckName(Request $request){
       $this->validate($request,[
           "name"=>"required",
           "season"=>"required"
       ]);


       $season_id=$request->get("season");
       $deck_name=strtolower($request->get("name"));



       $unfiltered_decks=Deck::select("id","version","slug")->where([["deck",$deck_name],["season_id",$season_id],["verified",1]])
           ->orderBy("version","asc")
           ->get();

       $filtered_decks=[];

       foreach ($unfiltered_decks as $deck){
           $temp_deck= [
               "name"=>ucfirst($deck->version),
               "slug"=>$deck->slug
           ];

           array_push($filtered_decks,$temp_deck);
       }

       return response()->json([
           "decks"=>$filtered_decks
       ],200);

   }


    public function store(Request $request){

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function suggestBetter(Request $request){

        $this->validate($request, [
            "name"=>"required|max:255",
            "season"=>"required|exists:seasons,id",
            "format"=>"required|exists:formats,id",
            "version"=>"required|max:255",
            "style"=>"nullable|max:255",
            "description"=>"nullable|max:255"
        ]);
        $season=$request->get("season");
        $version=$request->get("version");
        $format=$request->get("format");
        $name=$request->get("name");


       $deck=Deck::where([["verified",1],["season_id",$season],["format_id",$format],["deck",$name],["version",$version]])->first();

       if($deck){
           return response()->json([
               "message"=>"Deck already exists",
               "status"=>0
           ],200);
       }


       $new_deck=new Deck();

       $new_deck->name=$name;

       $new_deck->deck=$name;

       $new_deck->slug=uniqid(true);

       $new_deck->version=$version;

       $new_deck->game_id=1;

       $new_deck->format_id=$format;

       $new_deck->season_id=$season;

       $new_deck->user_id=Auth::user()->id;

       $new_deck->style=$request->get("style");

       $new_deck->description=$request->get("description");
       


       $new_deck->save();

       return  response()->json([
           "message"=>"Deck added successfully",
           "status"=>1
       ]);






    }


    /**
     *
     */
    public function getUnverifiedDecks(Request $request){

        $sort=$request->sort;
        $sort_array=explode("|",$sort);
        if($request->sort!=""){
            $decks=Deck::where("verified",0)->select("id","deck","version","format_id")->orderBy($sort_array["0"],$sort_array[1])->paginate(20);
        }else{
            $decks=Deck::where("verified",0)->select("id","deck","version","format_id")->paginate(20);
        }
        $return_deck=array();
        foreach ($decks as $key => $deck) {
            $tempdeck=[
                "id"=>$deck->id,
                "deck"=>$deck->deck,
                "version"=>$deck->version,
                "format"=>$deck->format->name
            ];
            array_push($return_deck,$tempdeck);
        }

        $ready=[
            "current_page"=>$decks->currentPage(),
            "data"=>$return_deck,
            "from"=>"",
            "last_page"=>$decks->lastPage(),
            "next_page_url"=>$decks->nextPageUrl(),
            "per_page"=>$decks->perPage(),
            "prev_page_url"=>$decks->previousPageUrl(),
            "total"=>$decks->total()
        ];
        return $ready;

    }

    public function getVersionByGroupedNameWithSeason(Request $request){
        $this->validate($request,[
            "name"=>"required",

        ]);



        $deck_name=$request->get("name");

        $seasons=Season::all();

        $decks_with_seasons=[];

        foreach ($seasons as $season){

            $season_decks=[];
            foreach ($season->decks as $deck){
                if(strtolower($deck_name)===strtolower($deck->deck)){
                    if($deck->verified===1){
                        $unfiltered_season_deck=[
                            "id"=>$deck->id,
                            "name"=>$deck->version,


                        ];
                        array_push($season_decks,$unfiltered_season_deck);
                    }
                }


            }

            $season_decks=[
                "season"=>$season->name,
                "decks"=>$season_decks

            ];


            array_push($decks_with_seasons,$season_decks);


        }


        return response()->json([
            "decks"=>$decks_with_seasons
        ],200);
    }


}
