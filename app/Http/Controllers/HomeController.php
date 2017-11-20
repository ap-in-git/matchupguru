<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Slider;
use App\Model\Post;
use App\Model\Message;
use Session;
use App\Mail\MessageMail;
use Mail;
use Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sliders=Slider::all();
      $posts=Post::orderBy("created_at","desc")->take(3)->get();
       return view('pages.home',compact("sliders","posts"));
    }


   public function contact(){
     return view("pages.contact");
   }


 public function storeContact(Request $request){
$this->validate($request,[
  "name"=>"required|max:255",
  "email"=>"required|max:255",
  "subject"=>"required|max:255",
  "message"=>"required|max:255"
]);

$message=new Message();

$message->name=$request->name;
$message->email=$request->email;
$message->subject=$request->subject;
$message->message=$request->message;

$message->save();
Mail::to("matthewpincus@gmail.com")->send(new MessageMail($message));

Session::flash("success","Your message was sent !!");
return redirect()->back();


 }

 public function checklogin(){
  return response()->json(Auth::check(),200);
 }



}
