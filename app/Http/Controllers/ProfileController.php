<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Model\EmailChange;
use Session;
use Hash;
use App\Mail\EmailChangeSend;
use Illuminate\Support\Str;
use Mail;


class ProfileController extends Controller
{
    public function index(){
      $user=User::find(Auth::user()->id);
      return view("user.profile",compact("user"));
    }

    public function store(Request $request){
$this->validate($request,[
  'email'=>'required|email|max:255',
'name' => 'required|string|max:255',
'gwent_name'=>"nullable|max:255",
"heart_name"=>"nullable|max:255",
"magic_name"=>"nullable|max:255",
"password"=>"nullable|min:8|max:255|confirmed",
]
);
$id=Auth::user()->id;
$user=User::findorfail($id);
if($request->email!=$user->email){
  $this->validate($request,[
    'email'=>'unique:users,email'
  ]);
  $oldverification=EmailChange::where("user_id",$id)->first();

   if($oldverification){
     $oldverification->delete();
   }

   $newverification=new EmailChange();
   $newverification->new_email=$request->email;
   $newverification->old_email=Auth::user()->email;
   $newverification->user_id=$id;
   $newverification->verification_code=Str::random(40);
   $newverification->save();
   Session::flash("email","Check Your email for updating the email !!!");
Mail::to($newverification->new_email)->send(new EmailChangeSend($newverification));
}
$user->name=$request->name;
$user->gwennt_name=$request->gwent_name;
$user->magic_name=$request->magic_name;
$user->heart_name=$request->heart_name;
if($request->has("password")){
  $user->password=Hash::make($request->password);

}

$user->save();
Session::flash("success","Your profile has been updated!!");
return redirect()->back();



    }

    public function changeEmail($old,$token){
      $tk=EmailChange::where("old_email",$old)->first();
      if(!$tk)
      abort(404);

      $user=User::where("email",$old)->first();
      $user->email=$tk->new_email;
      $user->save();
      Session::flash("success","Your Email has been updated");
      return redirect("/");
    }
}
