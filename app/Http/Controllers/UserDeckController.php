<?php

namespace App\Http\Controllers;

use App\Model\Deck;
use App\Model\Game;
use App\Model\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDeckController extends Controller
{

    public function index(){

        return view("user.deck.index");

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request){
//dd($request->all());


        if(!$request->ajax())
            abort(404);

        $this->validate($request,[
            "format"=>"required|numeric",
            "deck"=>"required|max:255",
            "style"=>"nullable|max:255",
            "description"=>"nullable|max:1000",
            "season"=>"required|exists:seasons,id",
            "version"=>"required|max:255"

        ]);


        $deck_name=strtolower($request->get("deck"));
        $version=strtolower($request->get("version"));
        $deck_exist_1=Deck::where([
            ["season_id",$request->get("season")],
            ["format_id",$request->get("format")],
            ["deck",$deck_name],
            ["version",$version],
            ["verified",1]])->first();
        //Check if verified deck exist of the name
        if($deck_exist_1){
            return response(0);
        }
        //Check if unverified but the deck with user_id exist
        $deck_exist=Deck::where([
            ["season_id",$request->get("season")],
            ["user_id",Auth::user()->id],
            ["format_id",$request->get("format")],
            ["deck",$deck_name],
            ["version",$version]
        ])->first();



        if($deck_exist){
            return response()->json(0);
        }else{
            $deck=new Deck();
            $deck->user_id=Auth::user()->id;
            if(Auth::user()->role==4||Auth::user()->role==2)
            {$deck->verified=$request->verified;}else{$deck->verified=0;}

            $deck->game_id=1;
            $deck->format_id=$request->get("format");
            $deck->deck=$request->deck;
            $deck->name=$request->deck;
            $deck->season_id=$request->season;
            $deck->style=$request->style;
            $deck->description=$request->description;
            $deck->slug=uniqid(true);
            $deck->user_id=Auth::user()->id;
            $deck->version=$version;
            $deck->active=1;
            $deck->save();
            return response()->json([
                "slug"=>$deck->slug,
                "name"=>$deck->deck,
                "version"=>$deck->version,
                "id"=>$deck->id
            ]);
        }



    }

    public function table(Request $request){
//        Name, Genre, Format, Season, and Style

       $format=$request->get("format");

       if($format==0){

           $sort=$request->sort;
           $sort_array=explode("|",$sort);
           $season=$request->get("season");
           if($request->sort!=""){


               $decks=Deck::select("deck","version","format_id","style","season_id","slug")
                   ->where([["season_id",$season],["verified",1]])
                   ->orderBy($sort_array[0],$sort_array[1])
                   ->paginate(20);
           }else{
               $decks=Deck::select("deck","version","format_id","style","season_id","slug")
                   ->where([["season_id",$season],["verified",1]])
                   ->paginate(20);
           }
           $return_deck=array();
           foreach ($decks as $key => $deck) {
               $tempdeck=[
                   "name"=>$deck->deck,
                   "version"=>ucfirst($deck->version),
                   "style"=>$deck->style,
                   "slug"=>$deck->slug,
                   "format"=>$deck->format->name,

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
       }else{

           $sort=$request->sort;
           $sort_array=explode("|",$sort);
           $season=$request->get("season");
           if($request->sort!=""){
               $decks=Deck::select("deck","version","format_id","style","season_id","slug")
                   ->where([["season_id",$season],["format_id",$format]])
                   ->orderBy($sort_array["0"],$sort_array[1])
                   ->paginate(20);
           }else{
               $decks=Deck::select("deck","version","format_id","style","season_id","slug")
                   ->where([["season_id",$season],["format_id",$format],["verified",1]])
                   ->paginate(20);
           }
           $return_deck=array();
           foreach ($decks as $key => $deck) {
               $tempdeck=[
                   "name"=>$deck->deck,
                   "version"=>ucfirst($deck->version),
                   "style"=>$deck->style,
                   "slug"=>$deck->slug,
                   "format"=>$deck->format->name,
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


    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeckWithUserDeck(Request $request, $id){
        if(!$request->ajax())
            abort(404);

        $decks=Deck::where([["season_id",$this->getDefaultSeason("magic")],["format_id",$id],["verified",1]])
            ->orWhere([["season_id",$this->getDefaultSeason("magic")],["format_id",$id],["user_id",Auth::user()->id]])
            ->select("version","slug")->orderBy("version","asc")->get();
        return response()->json([
            "decks"=>$decks
        ],200);
    }


    /**
     * @param $game_name
     * @return mixed
     */
    private function getDefaultSeason($game_name){
        $game=Game::select("id")->where("name",$game_name)->first();
        $season=Season::select("id")->where([
            ["game_id",$game->id],
            ["is_current",1]
        ])->first();


        return $season->id;


    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        return view("user.deck.create");
    }

    public function edit($slug){
        $deck=Deck::where("slug",$slug)->firstorfail();

        return view("user.deck.edit");

    }

}
