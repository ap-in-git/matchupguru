<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{

    public function show($id){
     $comments=Comment::where("post_id",$id)->orderBy("created_at","desc")->get();
     $return_comment=[];
        foreach ($comments as $comment) {
            array_push($return_comment,[
               "name"=>$comment->name,
                "text"=>$comment->text,
                "created"=>$comment->created_at->diffForHumans()
            ]);

     }
     return response()->json([
         "comments"=>$return_comment
     ],200);
    }

    public function store(Request $request){
         $this->validate($request,[
             "name"=>"required|max:255",
             "email"=>"required|email|max:255",
             "comment"=>"required|max:255",
             "post_id"=>"required"
         ]);

      $comment=new Comment();
      $comment->post_id=$request->get("post_id");
      $comment->email=$request->get("email");
      $comment->name=$request->get("name");
      $comment->text=$request->get("comment");

      $comment->save();

      return response()->json([
            "comment"=>[
                "name"=>$comment->name,
                "text"=>$comment->text,
                "created"=>$comment->created_at->diffForHumans()
            ]

      ],200);
    }
}
