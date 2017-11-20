<?php

namespace App\Http\Controllers\Admin;



use App\Mail\News as NewsEmail;
use App\Model\News as News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NewsSubscription;
use Mail;
use Session;

use App\User;



class NewsSubScriptionController extends Controller
{


    public function show(){

     return view("admin.news.create");
    }


    /**
     * @param Request $request
     */
    public function store(Request $request){

    $this->validate($request,[
      "subject"=>"required|max:255",
      "message"=>"required"
    ]);

     $news=new News;
     $news->title=$request->get("subject");
     $news->body=$request->get("message");

     $news->save();
    $subscriptionlists=NewsSubscription::where("subscribed",1)->get();
    foreach ($subscriptionlists as $key => $user) {
     $link=base64_encode($user->email);

     Mail::to($user->email)->send(new NewsEmail($request->subject,$request->message,$link));

     return redirect()->route("news.index");
    }




    }

    public function test(){
     $users=User::all();
     foreach ($users as $key => $user) {
       $subscription=new NewsSubscription();
       $subscription->email=$user->email;
       $subscription->save();
     }
    }

    public function unsubscribe($email){
      $user=NewsSubscription::where("email",base64_decode($email))->firstorfail();

      $user->subscribed=0;

      $user->save();
      Session::flash("success","Your email has been  unsubscribed from our news");

      return redirect("/");

    }


    public function index(){
        $news=News::select("id","title","body","created_at")->orderBy("created_at","desc")->get();

        return view("admin.news.index",compact("news"));


    }

    public function view($id){
        $news=News::findorfail($id);
        return view("admin.news.show",compact("news"));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllSubscription(){
      $subscriptions=NewsSubscription::all();

      return view("admin.subscription.view",compact("subscriptions"));
    }

    public function changeSubscription($id){
        $subscription=NewsSubscription::findorfail($id);

        $subscription->subscribed=!$subscription->subscribed;

        $subscription->save();

        Session::flash("success","Subscription changed");

        return redirect()->back();
    }


}
