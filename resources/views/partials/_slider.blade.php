<section id="introduction" class="gradient-violat padding-top-90 home-slider">
  <div id="home-slider" class="owl-carousel">

   {{-- @foreach ($sliders as $key => $slider)
     <div>
       <div class="sliding-card-with-bottom-image text-center padding-top-90">
         <h2 class="cta-heading text-white" style="color:white !important;">{!!stripslashes($slider->top_text)!!}</h2>
         <p class="text-white slider-para" style="color:white !important;">{!!stripslashes($slider->buttom_text)!!}</p>

         <div class="image-container text-center sm-display-none">
           <img class="img-responsive" src="{{$slider->image}}" alt="">
         </div>
       </div>
     </div>
   @endforeach --}}


      @foreach ($sliders as $key => $slider)
        <div class="image-right-slide-bg clearfix" style="background-image:url({{$slider->image}})">
          <div class="col-md-offset-2 col-md-10">
            @if (Auth::check())
              <h2 class="cta-heading text-white" style="color:white !important;">{!!stripslashes($slider->auth_text_top)!!}</h2>
              <p class="text-white slider-para" style="color:white !important;">{!!stripslashes($slider->auth_text_bottom)!!}</p>

          @else

            <h2 class="cta-heading text-white" style="color:white !important;">{!!stripslashes($slider->top_text)!!}</h2>
            <p class="text-white slider-para" style="color:white !important;">{!!stripslashes($slider->buttom_text)!!}</p>

          @endif


          </div>
        </div>
      @endforeach



  </div>
  {{-- {{$sliders}} --}}
</section>
