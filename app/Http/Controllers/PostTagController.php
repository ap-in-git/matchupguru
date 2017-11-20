<?php

namespace App\Http\Controllers;

use App\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags=Tag::orderBy("name","asc")->get();
        if($request->ajax()){
            $filtered_tags=[];
            foreach ($tags as $tag) {
                $temp_tag=[
                    "id"=>$tag->id,
                    "name"=>$tag->name
                ];
                array_push($filtered_tags,$temp_tag);

            }
           return response()->json($filtered_tags,200);
        }else{
            return view("admin.tag.index",compact("tags"));

        }

        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.tag.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|max:255",
            "slug"=>"required|max:255|unique:tags,slug|alpha_dash"
        ]);

        $tag=new Tag();
        $tag->name=$request->get("name");
        $tag->slug=$request->get("slug");
        $tag->save();

        Session::flash("success","Tag added successfully");

        return redirect()->route("post-tag.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag=Tag::findorfail($id);

        return view("admin.tag.edit",compact("tag"));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $tag=Tag::findorfail($id);
        $this->validate($request,[
            "name"=>"required|max:255",
            "slug"=>"required|max:255|alpha_dash",

        ]);
        if($request->slug!=$tag->slug)
            $this->validate($request,[
                "slug"=>"unique:post_categories,slug",
            ]);

        $tag->name=$request->name;
        $tag->slug=$request->slug;

        $tag->save();

        Session::flash("success","Tag updated successfully");

            return redirect()->back();
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag=Tag::findorfail($id);
        $tag->delete();

        Session::flash("success","Tag deleted successfully");

            return redirect()->back();
        //
    }


}
