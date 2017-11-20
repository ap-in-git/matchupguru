<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Verification;
use App\User;
use Auth;
use Session;
use Mail;
use App\Mail\ResendVerification;

class VerificationController extends Controller
{

    public function verificationDone($token){

    $verify=Verification::where('token',$token)->first();

    if(!$verify)
    abort(404);

   $user=User::findorfail($verify->user_id);
   $user->verified=1;
   $user->save();
   $verify->delete();
  Session::flash("success","Your email has  been verified");
  return redirect("/");
    }

    public function resendToken($token){
      if(Auth::user())
       abort(404);

       $email=base64_decode($token);

      $user=User::where("email",$email)->first();
      if(!$user)
      abort(404);

      $id=uniqid(true);
      $sendtoken=sha1($id);

      Mail::to($user->email)->send(new ResendVerification($sendtoken));

      $old_token=Verification::where("user_id",$user->id)->get();

      if($old_token->count()>0){
        foreach ($old_token as $key => $t) {
        $t->delete();
        }
      }

      $verification=new  Verification();
      $verification->user_id=$user->id;
      $verification->token=$sendtoken;
      $verification->save();

      Session::flash("resend","A verification code was send to your account");

      return redirect("/login");


    }
}
