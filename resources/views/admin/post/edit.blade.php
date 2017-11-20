@extends('layouts.app')
@section('title')
  Edit Post
@endsection
@section("style")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-8">

      <a href="{{route("admin.post.index")}}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Edit  Post</h4>

      </div>
      <div class="col-sm-8">
        <form method="post" enctype="multipart/form-data" action="{{route("admin.post.update",$post->id)}}">
          {{csrf_field()}}
          {{method_field("PUT")}}

          <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
              <label for="title" class=" control-label">Title * </label>

                  <input id="title" type="text" class="form-control" value="{{$post->title}}" name="title" value="{{ $title or old('title') }}" required autofocus>

                  @if ($errors->has('title'))
                      <span class="help-block">
                          <strong>{{ $errors->first('title') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label for="slug" class=" control-label">Slug (Unique)</label>

                  <input id="slug" type="text" value="{{$post->slug}}" class="form-control" name="slug" value="{{ $slug or old('slug') }}" required autofocus>

                  @if ($errors->has('slug'))
                      <span class="help-block">
                          <strong>{{ $errors->first('slug') }}</strong>
                      </span>
                  @endif

          </div>
            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                <label for="category" class=" control-label">Category</label>

                <select class="form-control" name="category">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" {!! $post->post_category_id==$category->id?'selected':' ' !!}>{{$category->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('category'))
                    <span class="help-block">
                          <strong>{{ $errors->first('category') }}</strong>
                      </span>
                @endif

            </div>
            <div class="form-group{{ $errors->has('tag') ? ' has-error' : '' }}">
                <label for="tag" class=" control-label">Tags</label>
                <select id="tag" name="tags[]" class="form-control select2-multi" multiple="multiple">

                    @foreach($tags as $tag)
                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                    @endforeach
                </select>


                @if ($errors->has('tag'))
                    <span class="help-block">
                          <strong>{{ $errors->first('tag') }}</strong>
                      </span>
                @endif

            </div>
          <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
              <label for="slug" class=" control-label">File</label>

                <input type="file" class="form-control" name="file"  >
                  @if ($errors->has('file'))
                      <span class="help-block">
                          <strong>{{ $errors->first('slug') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
              <label for="slug" class=" control-label">Post</label>
              @if ($errors->has('post'))
                  <span class="help-block">
                      <strong>{{ $errors->first('post') }}</strong>
                  </span>
              @endif
             <textarea id="texteditor" name="description"  required="" >

               {{$post->content}}
             </textarea>

          </div>

          <input type="submit" value="Edit Post" class="btn btn-primary">

        </form>

      </div>
      <div class="col-sm-4">
        <div class="text-center"><label>Image</label></div>
      <img class="img img-responsive" src="{{$post->display_image}}" >
       <div class="well well-sm"  style="margin-top:4%;">
          <label>Url : </label> <a href="{{route("post.single",$post->slug)}}">{{route("post.single",$post->slug)}}</a>
          <br>
          <label>Created at : &nbsp;</label>{{date("Y-m-d",strtotime($post->created_at))}}
       </div>
       <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Post</button>

   <!-- Modal -->
   <div class="modal fade" id="deleteModal" role="dialog">
     <div class="modal-dialog">

       <!-- Modal content-->
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Delete Post</h4>
         </div>
         <div class="modal-body">
           <p>Are you sure you want to delte the post </p>
         </div>
         <div class="modal-footer">
           <form method="post" action="{{route("admin.post.delete",$post->id)}}">
             {{csrf_field()}}
             {{method_field("DELETE")}}
              <button type="submit" class="btn btn-danger" >Delete</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </form>

         </div>
       </div>

     </div>
   </div>

 </div>
    </div>
  </div>


@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
  <script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
   CKEDITOR.replace( 'description' );

  </script>
  <script>
      $(".select2-multi").select2();
      $(".select2-multi").select2().val({!!json_encode($post->tags->pluck("id"))!!}).trigger("change");
  </script>
@endsection
