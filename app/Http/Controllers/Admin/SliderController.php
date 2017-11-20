<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Slider;
use Storage;
use Image;
use Session;
class SliderController extends Controller
{


    public function index(){

      return view("admin.slider.index");

    }


    public function data(Request $request){
      if(!$request->ajax())
        abort(404);

          $sliders=Slider::all();
          return response()->json($sliders,200);



    }


    public function store(Request $request){
      // dd($request->all());
  $this->validate($request,[
   "top_text"=>"required|max:255|min:6",
   "buttomtext"=>"required|max:255|min:6",
   "image"=>"required|mimes:jpeg,jpg,png"
  ]);

  $slider=new Slider();
  $slider->top_text=$request->top_text;
  $slider->buttom_text=$request->buttomtext;
  $slider->auth_text_top=$request->toptextlogin;
  $slider->auth_text_bottom=$request->bottomtextlogin;
  $id=time().".".$request->ext;
  $path=public_path()."/images/slider/".$id;
  $dbpath="/images/slider/".$id;
Image::make($request->image)->encode("png")->resize(622,671)->save($path);
$slider->image=$dbpath;
$slider->save();
return response()->json($slider,200);


    }


    public function destroy(Request $request,$id){
      if(!$request->ajax())
      abort(404);

    $slider=Slider::findorfail($id);
   $path=public_path().$slider->image;
   $id=$slider->id;
  if(file_exists($path))
  unlink($path);

  $slider->delete();

return response()->json([
 "id"=>$id
  ],200);

    }

    public function update(Request $request,$id){
      $this->validate($request,[
        "top"=>"required|max:255",
        "bottom"=>"required|max:255" ,
         "Image"=>"nullable|mimes:jpg,jpeg,png"

       ]);
       $slider=Slider::findorfail($id);
       $slider->top_text=$request->top;
       $slider->buttom_text=$request->bottom;
       $slider->auth_text_top=$request->auth_top;
       $slider->auth_text_bottom=$request->auth_bottom;

      if($request->hasFile("Image")){
       $previmage=public_path().$slider->image;

       if(file_exists($previmage))
       unlink($previmage);


      $id=time().".".$request->ext;
        $path=public_path()."/images/slider/".$id;
        $dbpath="/images/slider/".$id;
      Image::make($request->Image)->encode("png")->resize(622,671)->save($path);
      $slider->image=$dbpath;
      }

     $slider->save();
     Session::flash("success","Slider updated successfully");
     return redirect()->back();
    }
}
