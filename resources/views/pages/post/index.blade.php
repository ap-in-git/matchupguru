@extends('layouts.front')
@section('title')
  Posts

@endsection
@section('content')
<div class="container"  style="padding-top:10%;">
    <div class="row">

        <div class="col-sm-10">
            @foreach($posts as $post)

                <div  class="row">
                    <div class="col-sm-3">

                        <ul class="list-group post-meta">

                            <li class="list-group-item"><i class="fa fa-calendar"></i>&nbsp;{{date("F j , Y",strtotime($post->created_at))}}</li>
                            <li class="list-group-item">
                                <i class="fa fa-tags"></i>
                                @foreach($post->tags as $tag)
                                    <a href="{!! route("blog.post.tag",$tag->slug) !!}">{{$tag->name}} ,</a>
                                    @endforeach
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-comment-o"></i>  &nbsp; <label>{{$post->comments->count()}}</label> Comments
                            </li>

                        </ul>
                    </div>

                    <div class="col-sm-9 blog-post">
                        <a class="post-thumb" href="{{route("post.single",$post->slug)}}">
                            <img class="post-image" src="{{$post->display_image}}"/>
                        </a>
                        <h4 class="post-title"><a href="{{route("post.single",$post->slug)}}">{{$post->title}}</a> </h4>
                        <p>{{substr(strip_tags($post->content), 0,500)}}{{  strlen(strip_tags($post->content))>500?"...":""}}</p>
                        <a href="{{route("post.single",$post->slug)}}">Read More</a>
                    </div>

                </div>


            @endforeach
        </div>
    </div>

 </div>
</div>







@endsection
