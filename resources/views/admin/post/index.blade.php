@extends('layouts.app')
@section('title')
  Manage Posts
@endsection
@section('content')

  <div class="container">
    <div>
    <div class="text-center">All Posts</div>

  <a href="{{route("admin.post.add")}}" class="btn btn-primary pull-right" style="margin-bottom:2%;">Add a post</a>
  <div style="margin-bottom:2%;">@include('admin.ajax.search',[
    "holder"=>"Search post by title"
  ])
</div>

  </div>
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Created at</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($posts as $key => $post)
                <tr>
                  <td>{{$post->id}}</td>
                  <td>{{$post->title}}</td>
                  <td>{{$post->slug}}</td>
                  <td>{!!date("Y-m-d",strtotime($post->created_at))!!}</td>
                  <td>
                  <a href="{{route("admin.post.edit",$post->id)}}" class="btn btn-danger">Manage Post</a>

                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

      </div>
      <div class="col-sm-12"><div class="text-center">{{$posts->links()}}</div></div>

    </div>
  </div>


@endsection


@section('script')
  <script type="text/javascript">

  var options = {

     url: function(phrase) {
     return "/admin/post/search/"+phrase;
   },


   getValue: "name",
   template: {
   type: "links",
   fields: {
       link: "link"
   }
},

   list: {
     match: {
       enabled: true
     }
   },
 };

 $("#searchuser").easyAutocomplete(options);



  </script>

@endsection
