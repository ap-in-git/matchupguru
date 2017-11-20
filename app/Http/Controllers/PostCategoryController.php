<?php

namespace App\Http\Controllers;

use App\Model\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=PostCategory::orderBy("name","asc")->get();

        return view("admin.category.index",compact("categories"));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.category.create");
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
            "slug"=>"required|max:255|unique:post_categories,slug|alpha_dash"

        ]);

        $category=new PostCategory();
        $category->name=$request->get("name");
        $category->slug=$request->get("slug");
        $category->save();

        Session::flash("success","Category added successfully");

        return redirect()->route("post-category.index");
        //
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
        $category=PostCategory::findorfail($id);

        return view("admin.category.edit",compact("category"));
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
        $category=PostCategory::findorfail($id);

        $this->validate($request,[
            "name"=>"required|max:255",
            "slug"=>"required|max:255|alpha_dash",

        ]);
        if($request->slug!=$category->slug)
            $this->validate($request,[
                "slug"=>"unique:post_categories,slug",
            ]);

        $category->name=$request->name;
        $category->slug=$request->slug;

        $category->save();

        Session::flash("success","Category updated successfully");

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=PostCategory::findorfail($id);
        $category->delete();
        Session::flash("success","Category deleted successfully");

        return redirect()->back();
    }


}
