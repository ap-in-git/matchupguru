<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\League;
use Auth;

class PageController extends Controller
{

    public function getindex($value){
      if($value=='heartstone'){
      return redirect()->route("post.all");
      }
      if($value=='magic')
      {
        if(Auth::user()->magic_name==null||Auth::user()->magic_name=="")
        return redirect("/");

         $leagues=League::where("user_id",Auth::user()->id)->get();

         $activeleagues=League::where([["completed",0],["reseted",0],["user_id",Auth::user()->id]])->get();

        $has_active=  $activeleagues->count()>0?'1':'0';
          return view("user.index",compact("leagues","has_active"));
      }



      if($value=='gwent'){
        return redirect()->route("post.all");
      }
      abort(404);
    }




    public function getMagicGamePage(){

    }
    public function getHeartstoneGamePage(){
     return view("other.soon");
    }
    public function getGwentGamePage(){

     return view("other.soon");
    }

    public function showDataForGuest(){
      if(Auth::check())
      {

      }else{
        return view("pages.game.magic");
      }
    }
}
