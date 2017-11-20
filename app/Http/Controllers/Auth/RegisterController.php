<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Mail;
use App\Mail\Activation;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Model\Verification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

      $messages = [
           'gwennt_name.min' => 'This name must be between 4 and 255 character',
           'gwennt_name.max' => 'This name must be between 4 and 255 character',
           'heart_name.min' => 'This name must be between 4 and 255 character',
           'heart_name.max' => 'This name must be between 4 and 255 character',
           'magic_name.min' => 'This name must be between 4 and 255 character',
           'magic_name.max' => 'This name must be between 4 and 255 character',

       ];
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gwennt_name'=>"nullable|min:4|max:255",
            "heart_name"=>"nullable|min:4|max:255",
            "magic_name"=>"nullable|min:4|max:255"
        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $token=uniqid(true);
        $token=sha1($token);

      $mailData=array_merge($data,['token'=>$token]);
       Mail::to($data['email'])->send(new Activation((object)$mailData));
       $user= User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => bcrypt($data['password']),
           'gwennt_name'=>$data["gwennt_name"],
           'heart_name'=>$data["heart_name"],
           'magic_name'=>$data["magic_name"],
       ]);

  
    $verification=new  Verification();
    $verification->user_id=$user->id;
    $verification->token=$token;
    $verification->save();

        return $user;
    }



    protected function verify(){

    }





    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);

        Session::flash("success","Check your email for the verification");
        return redirect("/");
    }

}
