<div class="row">
  @foreach ($posts as $key => $post)
       <div class="panel panel-default">
         <div class="panel-heading" style="background:white;">
           <h3 class="panel-title"><div class="text-center">{{$post->title}}</div></h3>
         </div>
         <div class="panel-body">
           <div class="col-sm-8">
             <p>{{substr(strip_tags($post->content), 0,500)}}{{  strlen(strip_tags($post->content))>500?"...":""}}</p>
             <div class="text-center">
            <a href="{{route("post.single",$post->slug)}}" class="btn btn-primary">View Post</a>
             </div>
           </div>
           <div class="col-sm-4">
               <img src="{{$post->display_image}}" class="img img-responsive">
           </div>

         </div>



       </div>
      @endforeach

 <a href="{{$posts->nextPageUrl()}}">
   </div>
