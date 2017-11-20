<?php

namespace App\Http\Controllers\Admin;

use App\Model\Game;
use App\Model\Season;
use App\Model\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Deck;
use Auth;
use Session;
use App\Model\Format;
use App\Model\League;
use App\Model\Match;


class DeckController extends Controller
{



   public function fake(){
     $faker=\Faker\Factory::create();
     $name=strtolower($faker->name);
     for ($i=0; $i <10 ; $i++) {
       $deck=new Deck();
       $deck->season_id=$faker->numberBetween(1,2);

       $deck->name=$name;
       $deck->user_id=1;
       $deck->game_id=1;
       $deck->slug=uniqid(true);
       $deck->verified=1;

       $deck->deck=$name;
       $deck->version=strtolower($faker->name);

       $deck->format_id=4;
       $deck->save();

     }
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $decks=Deck::select("name","id","format_id","game_id")->orderBy("created_at","desc")->paginate(50);
    return view("admin.deck.index",compact("decks"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seasons=Season::select("id","name")->get();
        return view("admin.deck.create",compact("seasons"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    $this->validate($request,[
      "game"=>"required|digits_between:0,6",
      "format"=>"required|numeric",
      "deck"=>"required|max:255",
      "version"=>"required|max:255",
      "style"=>"nullable|max:255",
      "description"=>"nullable|max:1500",
            "season"=>"required|exists:seasons,id"
            ]
    );
    $name=$request->get("version");
    $deck_name=$request->get("deck");
    $format_id=$request->get("format");

    $prev_deck=Deck::where([
        ["season_id",$request->get("season")],
        ["version",$name],
        ["format_id",$format_id],
        ["deck",$deck_name],
        ["verified",1]
    ])->first();

    if($prev_deck){
        Session::flash("deck_error","Deck is not unique");
        return redirect()->back();
    }

    $deck=new Deck();
    $deck->user_id=Auth::user()->id;
    $deck->game_id=$request->game;
    $deck->format_id=$request->get("format");
    $deck->name=$name;

    $deck->deck=$deck_name;

    $deck->version=$name;

    $deck->style=$request->style;
    $deck->description=$request->description;
    $deck->season_id=$request->get("season");
    $deck->verified=1;
    $deck->slug=uniqid(true);
    if ($request->has("active")) {
      $deck->active=1;
  }else{
    $deck->active=0;
  }

    $deck->save();

    Session::flash("success","A new deck has been added");


    return redirect()->route("admin.deck.index");


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
     $deck=Deck::where("slug",$slug)->firstorfail();
      $seasons=Season::select("id","name")->get();

      return view("admin.deck.edit",compact("deck","seasons"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)

    {


        $deck=Deck::findorfail($id);

        $this->validate($request,[
                "game"=>"required|digits_between:0,6",
                "format"=>"required|numeric",
                "deck"=>"required|max:255",
                "version"=>"required|max:255",
                "style"=>"nullable|max:255",
                "description"=>"nullable|max:1500",
                "season"=>"required|exists:seasons,id"
            ]
        );



//        $version=strtolower($request->get("version"));
//        $deck_name=strtolower($request->get("deck"));

        $prev_deck=Deck::where([
            ["season_id",$request->get("season")],
            ["version",$version],
            ["deck",$deck_name],
            ["verified",1]
        ])->first();

        if($prev_deck){
            Session::flash("deck_error","Deck is not unique");
            return redirect()->back();
        }
       $deck->game_id=$request->game;
       $deck->format_id=$request->get("format");
       $deck->name=$version;
       $deck->deck=$deck_name;
       $deck->version=$version;
       $deck->style=$request->style;
       $deck->description=$request->description;
       $deck->save();
           Session::flash("success","Your deck has been updated");
       return redirect()->back();


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$slug)
    {

        $deck=Deck::where("slug",$slug)->firstorfail();
        $returnid=$slug;

         $leagues=League::where("deck_id",$deck->id)->get();

         if($leagues->count()>0){
           foreach ($leagues as $key => $league) {
             $league->delete();
           }
         }
  $matches=Match::where("deck_id",$deck->id)->get();
  $opponent_matches=Match::where("opp_deck_id",$deck->id)->get();
        foreach ($opponent_matches as $opponent_match) {
            $opponent_match->delete();
  }
  $tournaments=Tournament::where("deck_id",$deck->id)->get();
        foreach ($tournaments as $index => $tournament) {
            $tournament->delete();
        }
if($matches->count()>0){
  foreach ($matches as $key => $match) {
    $match->delete();
  }
}
        $deck->delete();

        if($request->ajax()){
            return response()->json($returnid,200);
        }else{
          Session::flash("success","Deck Deleted successfully");
          return redirect()->route("admin.deck.index");
        }

    }


    public function search(Request $request,$value){
      if(!$request->ajax())
      abort(404);
      $data=[];
      $decks=Deck::where(
        'name', 'LIKE', '%'.$value.'%'
        )->select("name","id")->orderBy("created_at","asc")->get();
   foreach ($decks as $key => $deck) {
     $item=[
       "name"=>$deck->name,
       "link"=>route("admin.deck.edit",$deck->id)
     ];
     array_push($data,$item);
   }
   return $data;
    }

/**
 * [getDecksByFormat description]
 * @method getDecksByFormat
 * @param  Request          $request [description]
 * @param  [int]           $id      [description]
 * @return [json]                    [description]
 */
    public function getDecksByFormat(Request $request,$id){
      if(!$request->ajax())
      abort(404);

    $decks=Deck::where("format_id",$id)->select("name","id")->orderBy("name","asc")->get();

   return response()->json([
     "decks"=>$decks
   ],200);
    }


    public function getDeckByFormatAdmin(Request $request,$id){
    $format=Format::Findorfail($id);



   if($request->ajax()){
     $decks=Deck::select("name","id")->where([["format_id",$format->id],["verified",1]])->orderBy("name","asc")->get();
     return $decks;
   }else{
       $decks=Deck::select("name","id","format_id","game_id")->where("format_id",$format->id)->orderBy("name","asc")->paginate(40);

   }
    return view("admin.deck.format",compact("decks"));

    }





    public function getDeckByFormatAd(Request $request,$id){
      if(!$request->ajax())
      abort(404);

      $decks=Deck::select("name","id","format_id","game_id")->where([["format_id",$id],["verified",1]])->orderBy("name","asc")->paginate(5);

      if($decks->count()==0)
      {
        return response()->json(0,200);
      }
      return view("admin.ajax.deck",compact("decks"));
    }




    public function getUnverifiedDeck(){
      return view("admin.deck.unverified.index");
    }

    public function getUnverifiedDeckData(Request $request){
   $sort=$request->sort;
   $sort_array=explode("|",$sort);
   if($request->sort!=""){
$decks=Deck::select("id","name","format_id")->with("format")->orderBy($sort_array["0"],$sort_array[1])->where("verified",0)->paginate(20);
   }else{
  $decks=Deck::select("id","name","format_id")->with("format")->where("verified",0)->paginate(20);
   }
    return $decks;
    }

    public function approveUnverifiedDeck(Request $request,$id){
    $deck=Deck::findorfail($id);
    $deck->verified=1;
    $deck->user_id=1;

    $deck->save();
    if($request->ajax()){
      return response($id);
    }

    return redirect()->route("admin.deck.unverified");

    }


  public function deckAjax(Request $request){

    $sort=$request->sort;
    $sort_array=explode("|",$sort);
    if($request->sort!=""){
 $decks=Deck::where("verified",1)->select("id","name","deck","version","game_id","format_id","created_at")->orderBy($sort_array["0"],$sort_array[1])->paginate(20);
    }else{
   $decks=Deck::where("verified",1)->select("id","name","deck","version","game_id","format_id","created_at")->paginate(20);
    }
$return_deck=array();
    foreach ($decks as $key => $deck) {
      $tempdeck=[
        "id"=>$deck->id,
        "name"=>$deck->name,
        "game"=>$deck->game->name,
          "deck"=>$deck->deck,
          "version"=>$deck->version,
        "format"=>$deck->format->name,
        "created_at"=>date("Y-m-d",strtotime($deck->created_at))
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


public function editUnverifiedDeck($id){
    $deck=Deck::findorfail($id);
    $seasons=Season::select("name","id")->get();

return view("admin.deck.unverified.edit",compact("deck","seasons"));
}


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function mergeUnverifiedDeck(Request $request){




    if($request->get("version")==0){
        return redirect()->back();
    }


   $deck=Deck::where("slug",$request->get("version"))->firstorfail();



   $merge_deck=Deck::findorFail($request->merge_deck_id);



  $leagues=League::where("deck_id",$merge_deck->id)->get();
  $matches=Match::where("deck_id",$merge_deck->id)->get();
  $opponent_matches=Match::where("opp_deck_id",$merge_deck->id)->get();

    foreach ($opponent_matches as $opponent_match) {
        $opponent_match->opp_deck_id=$deck->id;
        $opponent_match->save();
  }

  $tournaments=Tournament::where("deck_id",$merge_deck->id)->get();


  if($leagues->count()>0){
    foreach ($leagues as $key => $league) {
      $league->deck_id=$deck->id;
      $league->format_id=$deck->format_id;
      $league->game_id=$deck->game_id;
       $league->save();
  }


  }
  if($tournaments->count()>0){
    foreach ($tournaments as $key => $tournament) {
      $tournament->deck_id=$deck->id;
       $tournament->save();
  }


  }

    foreach ($matches as $key => $match) {
        $match->format_id=$deck->format_id;
        $match->deck_id=$deck->id;
        $match->save();
        # code...
    }





  $merge_deck->delete();
  Session::flash("success","The deck has been merged");
  return redirect()->route("admin.deck.unverified");
}


public function getDeckBySeason(Request $request){
    $season=$request->get("season");
   $decks= Deck::select("id","name")->where("season_id",$season)->get();
    return response()->json($decks,200);
}




}
