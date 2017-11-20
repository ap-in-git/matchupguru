<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Message;

class MessageController extends Controller
{
    public function index(){
      $messages=Message::orderBy("created_at","desc")->paginate(40);
      return view("admin.message.index",compact("messages"));

    }

    public function fake(){
      $faker=\Faker\Factory::create();
      for ($i=0; $i <1000 ; $i++) {
        $message=new Message();
        $message->name=$faker->name;
        $message->email=$faker->email;
        $message->subject=$faker->sentence($nbWords = 6, $variableNbWords = true);
        $message->message=$faker->text($maxNbChars = 200);
        $message->seen=$faker->numberBetween($min = 0, $max = 1);
        $message->save();


      }
    }


    public function delete($id){
      $sendid=$id;
     $message=Message::findorfail($id);
       $message->delete();
       return response()->json($sendid);



    }


    public function seen(Request $request,$id){

      if(!$request->ajax())
      abort(404);

    $message=Message::findorfail($id);

    $message->seen=1;
    $message->save();
    return response()->json(true,200);


    }
}
