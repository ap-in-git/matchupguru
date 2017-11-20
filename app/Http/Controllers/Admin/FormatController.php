<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Model\Format;
use App\Model\Game;
use App\Model\Deck;
use Session;

class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return view("admin.format.index");
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games=Game::select("name","id")->get();

        return view("admin.format.create",compact("games"));
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
         "game"=>"required|between:0,4",
         "name"=>"required|max:255"

       ]);

       $format=new Format;
       $format->name=$request->name;
       $format->game_id=$request->game;

       $format->save();
       Session::flash("success","A new Format has been added");
       return redirect()->route("format.index");


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
         $format=Format::findorfail($id);
         $games=Game::all();
         return view("admin.format.edit",compact("format","games"));
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
       "game"=>"required|between:0,4",
       "name"=>"required|max:255"
     ]);
        $format=Format::findorfail($id);
        $format->name=$request->name;
        $format->save();
        Session::flash("success","Your Format has Been Updated");
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
        $format=Format::findorfail($id);
         $decks=Deck::where("format_id",$format->id)->get();
         foreach ($decks as $key => $deck) {
        $deck->delete();
         }
        $format->delete();
        Session::flash("success","Your Format has been deleted");
        return redirect()->route("format.index");
    }

    public function fake(){
      $faker=\Faker\Factory::create();
      for ($i=0; $i <50 ; $i++) {
        $format=new Format();
        $format->name=$faker->name;
        $format->game_id=2;
        $format->save();

      }
    }

    public function search(Request $request,$value){

      if(!$request->ajax())
      abort(404);
      $data=[];
      $formats=Format::where(
        'name', 'LIKE', '%'.$value.'%'
        )->select("name","id")->orderBy("created_at","asc")->get();
   foreach ($formats as $key => $format) {
     $item=[
       "name"=>$format->name,
       "link"=>route("format.edit",$format->id)
     ];
     array_push($data,$item);
   }
   return $data;
    }


    public function getFormatByGame(Request $request,$id){
      if(!$request->ajax())
      abort(404);
    $formats=Format::select("name","id")->where("game_id",$id)->orderBy("name","asc")->get();

    return response()->json($formats,200);

    }


    public function getFormatAjax(Request $request){
      $sort=$request->sort;
      $sort_array=explode("|",$sort);
      if($request->sort!=""){
   $formats=Format::select("id","name","game_id")->with("game")->orderBy($sort_array["0"],$sort_array[1])->paginate(20);
      }else{
     $formats=Format::select("id","name","game_id")->with("game")->paginate(20);
      }
       return $formats;
    }
}
