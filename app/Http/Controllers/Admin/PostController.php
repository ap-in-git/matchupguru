<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Model\PostCategory;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Model\Post;
use Storage;
use Session;
use Image;

class PostController extends Controller
{
    public function index(){
     $posts=Post::select("id","title","slug","created_at")->paginate(20);
     return view("admin.post.index",compact("posts"));
    }

    public function add(){
        $tags=Tag::orderBy("name","asc")->get();
        $categories=PostCategory::orderBy("name","asc")->get();
      return view("admin.post.add",compact("categories","tags"));
    }

    public function store(Request $request){
$this->validate($request,[
  "title"=>"required|max:255",
  "slug"=>"required|max:255|unique:posts,slug|alpha_dash",
  "file"=>"required|mimes:jpeg,jpg,png",
  "description"=>"required",
    "category"=>"required"
]);

$status=new Post();
$status->title=$request->title;
$status->slug=str_slug($request->slug,"-");
$status->content=$request->description;
$status->post_category_id=$request->category;

if($request->has("later")){
$status->publish_date=date("Y-m-d",strtotime($request->publish_date));
}else{
$status->publish_date=date("Y-m-d",time());;
}
$file=$request->file;
$id=time().".png";
$path=public_path()."/images/post/".$id;
$dbpath="/images/post/".$id;
Image::make($file)->encode("png")->save($path);
$status->display_image=$dbpath;
        $status->save();
        if(isset($request->tags)){
            $status->tags()->sync($request->tags,false);
        }else{
            $status->tags()->sync([]);
        }

Session::flash("success","A new post has been added");
return redirect()->route("admin.post.index");


  }

public function edit($id){
  $post=Post::findorfail($id);
  $categories=PostCategory::all();
  $tags=Tag::all();
  return view("admin.post.edit",compact("post","tags","categories"));
}

public function update(Request $request,$id){
$post=Post::findorfail($id);

  $this->validate($request,[
    "title"=>"required|max:255",
    "slug"=>"required|max:255|alpha_dash",
    "file"=>"nullable|mimes:jpeg,jpg,png",
    "category"=>"required",
    "description"=>"required"
  ]);
  if($request->slug!=$post->slug)
  $this->validate($request,[
    "slug"=>"unique:posts,slug",
  ]);

  $post->title=$request->title;
  $post->slug=$request->slug;
  $post->content=$request->description;

    $post->post_category_id=$request->category;

  if($request->hasFile("file")){
    $filepath=public_path().$post->display_image;
    if(file_exists($filepath))
    unlink($filepath);


    $file=$request->file;
    $id=time().".png";
    $path=public_path()."/images/post/".$id;
    Image::make($file)->encode("png")->save($path);
    $dbpath="/images/post/".$id;
    $post->display_image=$dbpath;
  }
    $post->tags()->sync([]);
    if(isset($request->tags)){
        $post->tags()->sync($request->tags,false);
    }else{
        $post->tags()->sync([]);
    }

  $post->save();
  Session::flash("success","Your post was updated");
  return redirect()->back();
}

public function  getBySlug($slug){

}

public function delete($id){
  $post=Post::findorfail($id);

   $filepath=public_path().$post->display_image;
   if(file_exists($filepath))
   unlink($filepath);

   $post->delete();
   Session::flash("success","Your post was deleted");
   return redirect()->route("admin.post.index");

}
public function search(Request $request,$title){
  if(!$request->ajax())
  abort(404);
    $data=[];
    $posts=Post::where(
      'title', 'LIKE', '%'.$title.'%'
      )->select("title","id")->orderBy("created_at","desc")->get();
 foreach ($posts as $key => $post) {
   $item=[
     "name"=>$post->title,
     "link"=>route("admin.post.edit",$post->id)
   ];
   array_push($data,$item);
 }
 return $data;

}


public function  fake(){
    $faker=\Faker\Factory::create();
   $post=new Post();
    for ($i=0; $i <100 ; $i++) {
        $sentence=$faker->sentence( 6,true);
        $post->title=$sentence;
        $post->slug=str_slug($sentence,"-");
        $post->content=$faker->text( 3000);
        $post->display_image=$faker->imageUrl($width = 640, $height = 480);

        $post->created_at=$faker->dateTime($max = 'now', $timezone = date_default_timezone_get());
        $post->save();
    }
}



}
