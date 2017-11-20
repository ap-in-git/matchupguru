<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Hash;
use Session;
use Auth;
use App\Model\Verification;
class UserController extends Controller
{

    public function fake(){
      $faker=\Faker\Factory::create();
      for ($i=0; $i <50 ; $i++) {
        $user=new User();
        $user->name=$faker->name;
        $user->email=$faker->email;
        $user->password=Hash::make("12345678");
        $user->gwennt_name=$faker->name;
        $user->heart_name=$faker->name;
        $user->magic_name=$faker->name;
        $user->verified=$faker->boolean;
        $user->role=2;
        $user->created_at=$faker->date($format = 'Y-m-d', $max = 'now');
        $user->save();
      }
    }
    public function getUsers(){
     $users=User::where("role","!=",4)->orderBy("created_at","desc")->paginate(40);
     return view("admin.user.index",compact("users"));
    }

    public function getSingleUser($id){
      $user=User::findorfail($id);
      return view("admin.user.single",compact("user"));

    }

    public function deleteUser($id){
      $user=User::findorfail($id);
      $user->delete();
      Session::flash("success","User Deleted Successfully");
      return redirect()->route("admin.users.index");

    }

    public function searchuser(Request $request,$name){
      if(!$request->ajax())
      abort(404);
      $data=[];
      $users=User::where(
        'name', 'LIKE', '%'.$name.'%'
        )->select("name","id")->orderBy("created_at","asc")->get();
   foreach ($users as $key => $user) {
     $item=[
       "name"=>$user->name,
       "link"=>route("admin.users.single",$user->id)
     ];
     array_push($data,$item);
   }
   return $data;
    }



    public function setname(Request $request){
      if(!$request->ajax())
      abort(404);


      $this->validate($request,[
        "name"=>"required",
        "value"=>"required|max:255"
      ]);
      $user=User::Find(Auth::user()->id);

  if($request->name=="magic"){
$user->magic_name=$request->value;
  }
  if($request->name=="gwent"){
$user->gwennt_name=$request->value;
  }
  if($request->name=="heart"){
$user->heart_name=$request->value;
  }
  Session::flash("success","Your name has been updated!!!");

  $user->save();


  return response()->json(true,200);

    }


    public function makeuserauthor(Request $request){
    $user=User::findorfail($request->user_id);
    if($user->role==2||$user->role==1){
      $user->role=3;
    }else{
     $user->role==2;
    }

    $user->save();


    Session::flash("success","User has been updated");
    return redirect()->back();

    }
    public function makeuseradmin(Request $request){
    $user=User::findorfail($request->user_id);
    if($user->role==2||$user->role==3){
      $user->role=1;

    }else{
     $user->role=2;
    }

    $user->save();


    Session::flash("success","User has been updated");
    return redirect()->back();

    }




    public function showalladmin(){
      $users=User::where("role","=",3)->orWhere("role","=",1)->orderBy("created_at","desc")->paginate(40);
      return view("admin.user.index",compact("users"));
    }

    public function changeVerified(Request $request){
      $user=User::find($request->user_id);
      if($request->has("verified")){
        $remaining_token=Verification::where("user_id",$user->id)->get();
        foreach ($remaining_token as $key => $token) {
          $token->delete();
        }
        $user->verified=1;
      }else{

        $user->verified=0;
      }
     Session::flash("email","User has been updated");
      $user->save();

      return redirect()->back();
      // dd($request->all());
    }


}
