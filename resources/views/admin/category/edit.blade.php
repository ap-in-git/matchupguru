@extends('layouts.app')
@section('title')
Edit Category
@endsection
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-sm-12">

                <a href="{{route("post-category.index")}}" class="btn btn-primary pull-left">Go back</a>
                <h4 style="color:black; " class="text-center">Edit Category</h4>

            </div>
            <div class="col-sm-10 col-sm-offset-2">
                <form method="post" enctype="multipart/form-data" action="{{route("post-category.update",$category->id)}}">
                    {{csrf_field()}}
                    {{method_field("PUT")}}

                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="name" class=" control-label">Name * </label>

                        <input id="name" type="text" class="form-control" name="name" value="{{$category->name }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                        @endif

                    </div>
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                        <label for="slug" class=" control-label">Slug (Unique)</label>

                        <input id="slug" type="text" class="form-control" name="slug" value="{{ $category->slug }}" required autofocus>

                        @if ($errors->has('slug'))
                            <span class="help-block">
                          <strong>{{ $errors->first('slug') }}</strong>
                      </span>
                        @endif

                    </div>


                    <input type="submit" value="Update Category" class="btn btn-primary">

                </form>

            </div>
        </div>
    </div>


@endsection

