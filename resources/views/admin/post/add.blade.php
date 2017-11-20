@extends('layouts.app')
@section('title')
  Add Post

@endsection
@section("style")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

    @endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">

      <a href="{{route("admin.post.index")}}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Add Post</h4>

      </div>
      <div class="col-sm-10 col-sm-offset-2">
        <form method="post" enctype="multipart/form-data" action="{{route("admin.post.store")}}">
          {{csrf_field()}}

          <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
              <label for="title" class=" control-label">Title * </label>

                  <input id="title" type="text" class="form-control" name="title" value="{{ $title or old('title') }}" required autofocus>

                  @if ($errors->has('title'))
                      <span class="help-block">
                          <strong>{{ $errors->first('title') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label for="slug" class=" control-label">Slug (Unique)</label>

                  <input id="slug" type="text" class="form-control" name="slug" value="{{ $slug or old('slug') }}" required autofocus>

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
                          <option value="{{$category->id}}">{{$category->name}}</option>
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

                <input type="file" class="form-control" name="file" required >
                  @if ($errors->has('file'))
                      <span class="help-block">
                          <strong>{{ $errors->first('file') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group">
              <label for="publist" class=" control-label">Publish Later</label>

                    <input type="checkbox" name="later" id="publishcheck" onchange="showdate()">

          </div>
          <div class="form-group" id="date">


          </div>




          <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
              <label for="slug" class=" control-label">Post</label>
              @if ($errors->has('post'))
                  <span class="help-block">
                      <strong>{{ $errors->first('post') }}</strong>
                  </span>
              @endif
             <textarea id="texteditor" name="description"  required="" >
               {{ $description or old('description') }}
             </textarea>

          </div>

          <input type="submit" value="Add Post" class="btn btn-primary">

        </form>

      </div>
    </div>
  </div>


@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
  <script>


      $(".select2-multi").select2();
  var i=0;
   var showdate=function(){
if(i==0)
{
  $("#date").html('<label for="slug" class="control-label">Date</label>\
    <input  type="date" class="form-control" name="publish_date"  required autofocus>');
  i=1;
}else{
  $("#date").html(" ");
  i=0;

}
   }

  </script>
  <script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
   CKEDITOR.replace( 'description' );

  </script>

@endsection
