<section id="blog-card" class="padding-top-bottom-90">
  <div class="container">
    <div class="heading-wraper text-center margin-bottom-80">
      <h4>latest published</h4>
      <hr class="heading-devider gradient-orange">
    </div>
    <div class="row">
      @foreach ($posts as $key => $post)
        <div class="col-md-4">
          <div class="card">
            <a href="{{route("post.single",$post->slug)}}">
            <img class="card-img-top img-responsive max-width-100" src="{{$post->display_image}}" alt="Card image cap"></a>
            <div class="card-block">

              <a href="{{route("post.single",$post->slug)}}">  <h4 class="card-title">{{$post->title}}</h4></a>
              <p class="card-text"><small class="text-muted italic">{{date("F j , Y",strtotime($post->created_at))}}</small></p>

              <p class="card-text">{{substr(strip_tags($post->content), 0,100)}}{{  strlen(strip_tags($post->content))>100?"...":""}}</p>
              <a href="{{route("post.single",$post->slug)}}" class="btn btn-link">read more <span><i class="ion-ios-arrow-thin-right"></i></span> </a>
            </div>
          </div>
        </div>
      @endforeach
      <div class="col-md-12">
        <div class="text-center">

          <a href="{{route("post.all")}}" class="btn btn-primary text-center">View All</a>
        </div></div>



      </div>

    </div>

</section>
