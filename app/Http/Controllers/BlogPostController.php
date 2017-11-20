<?php

namespace App\Http\Controllers;

use App\Model\PostCategory;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Model\Post;
class BlogPostController extends Controller
{
    public function getBySlug($slug){
      $post=Post::where('slug',$slug)->first();
      if(!$post)
      abort(404);

    return view("pages.post.single",compact("post"));
    }


    public function getall(Request $request){

      $posts=Post::orderBy("created_at","desc")->paginate(20);
      if($request->ajax())
      return view("ajax.post",compact("posts"));

      return view("pages.post.index",compact("posts"));
    }
    public function getPostWithTags($slug){
        $tag=Tag::where("slug",$slug)->firstorfail();
        $posts=$tag->posts;
      return view("pages.post.index",compact("posts"));
    }

    public function getPostWithCategory($slug){
        $category=PostCategory::where("slug",$slug)->first();
        if($category){
            $posts=Post::where("post_category_id",$category->id)->orderBy("created_at","desc")->paginate(20);
        }else{
            $posts=Post::orderBy("created_at","desc")->paginate(20);
        }
        return view("pages.post.index",compact("posts"));
    }
}
