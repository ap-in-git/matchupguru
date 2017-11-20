<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function authenticated(Request $request, $user)
{
    if($user->verified==0){
      Auth::logout();
      $url=base64_encode($user->email);
      Session::flash("resend","Your email is not verified.
       If you didn't get the code click the link below to resend it
      <a href='/resend/".$url."/'>Resend</a>");
      // return response()->json([
      //   "login"=>0
      // ],200);
      return redirect()->route("login");
    }
    Session::flash("success","Welcome back ".$user->name);
    return redirect("/");
  //  return response()->json([
  //  "login"=>1
  //  ],200);
}


}
