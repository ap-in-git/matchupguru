@extends('layouts.app')
@section('title')
View Newss
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">

      <a href="{!!route("news.index")!!}" class="btn btn-primary pull-left">Go back</a>

       <div class="card">
           <div class="list-group-item">Subject : {{$news->title}}</div>
           <div class="list-group-item">Message : {!! $news->body !!}</div>
       </div>

      </div>


  </div>


@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'message' );

    </script>

@endsection

