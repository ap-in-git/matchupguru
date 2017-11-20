@extends('layouts.app')
@section('title')
  Post | {{$post->title}}
@endsection
@section('stylesheet')
  <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">


@endsection
@section('content')
  <div class="container">

    <div class="row">
     <div class="col-sm-12">

       <div class="single-post-meta">
         <div class="col-sm-6">
           <div class="meta-link"><i class="fa fa-tags"></i> &nbsp;
               @foreach($post->tags as $tag)
                   <a href="{!! route("blog.post.tag",$tag->slug) !!}">{{$tag->name}},</a>
                   @endforeach

           </div>
         </div>
         <div class="col-sm-6">
           <span class="pull-right">


           <div class="meta-link"><i class="fa fa-calendar"></i>&nbsp; {{date("F j , Y",strtotime($post->created_at))}}</div>
           {{--<div class="meta-link"><a class="scroll-to" href="blog-single-ns.html#comments"><i class="icon-speech-bubble"></i>3</a></div>--}}
                </span>
         </div>
       </div>
     </div>
      <div class="col-sm-12">
        <img class="post-image" src="{{$post->display_image}}"/>
      </div>
      <div class="col-sm-12">
        <h3 class="pull-left m-all-20">{{$post->title}}</h3>
      </div>

      {{--<div class="col-md-12"><a href="{{route("post.all")}}" class="btn btn-primary">View All Posts</a></div>--}}
      {{--<div class="text-center" id="posttitle">{{$post->title}}</div>--}}

      <div class="col-md-12  ">
        {!!$post->content!!}
      </div>
      <div class="col-sm-12">
        <div class="post-comment">
          Comments
        </div>
      </div>

            <comment post_id="{{$post->id}}">

            </comment>






    </div>



  </div>

@endsection
