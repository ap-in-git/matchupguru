<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Game;
use App\Model\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $seasons=Season::all();
        return view("admin.season.index",compact("seasons"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games=Game::select("id","name")->get();
        return view("admin.season.create",compact("games"));
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
            "title"=>"required|max:255",
            "game"=>"required|numeric"
        ]);

        $season=new Season();
        $season->name=$request->title;
        $season->game_id=$request->game;
        if($request->has("default")){

            $previous_seasons=Season::where("game_id",$request->game)->get();
            foreach($previous_seasons as $s){
                $s->is_current=0;
                $s->save();
            }

            $season->is_current=1;

        }

        $season->save();

        Session::flash("success","Season added successfully");
        return redirect()->route("season.index");

        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $season=Season::findorfail($id);
        $games=Game::all();

        return view("admin.season.edit",compact("season","games"));
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
        $this->validate($request,[
            "title"=>"required|max:255",
            "game"=>"required|numeric"
        ]);

        $season=Season::findorfail($id);
        $season->name=$request->title;
        $season->game_id=$request->game;
        if($request->has("default")){

            $previous_seasons=Season::where("game_id",$request->game)->get();
            foreach($previous_seasons as $s){
                $s->is_current=0;
                $s->save();
            }

            $season->is_current=1;

        }

        $season->save();

        Session::flash("success","Season updated successfully");

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public  function ajax(Request $request){
        $game=$request->get("game");

        $game=Game::select("id")->where("name",$game)->firstorfail();

        $seasons=Season::select("id","name","is_current")->where("game_id",$game->id)->get();
        $default_season=0;
        foreach ($seasons as $season){
            if ($season->is_current==1){
                $default_season=$season->id;

            }
        }

        return response()->json([
            "seasons"=>$seasons,
            "default"=>$default_season
        ],200);
    }
}
