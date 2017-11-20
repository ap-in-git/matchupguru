<nav class="navbar navbar-default navbar-fixed-top gradient-violat colored-nav">
  <div class="container">
  <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><span class="logo-wraper logo-white">
          <img src="/images/Logo.png" alt="">Matchup Guru
        </span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav  navbar-right">
        @if (Auth::check())

          @if (Auth::user()->heart_name!=null||Auth::user()->heart_name!="")
            <li><a href="http://www.matchupguru.com/post/more-games-coming">hearthstone</a></li>
          @else
               <li><a href="#" onclick="openheartname()">Heartstone</a></li>
          @endif
         @if (Auth::user()->gwennt_name!=null||Auth::user()->gwennt_name!="")
          <li><a href="http://www.matchupguru.com/post/more-games-coming">Gwent</a></li>
          @else
          <li><a href="#" onclick="opengwentname()">Gwent</a></li>
          @endif
          @include("partials._magicdropdown")


          <li><a href="/contact"> Contact</a></li>

              @include('partials._dropdown')
        @else
          <li><a href="http://www.matchupguru.com/post/more-games-coming" >Hearthstone</a></li>
          <li><a href="http://www.matchupguru.com/post/more-games-coming"  >Gwent</a></li>
          <li><a href="{{route("guest.magic")}}"  >Magic</a></li>
          <li><a href="/contact">Contact</a></li>
          <li><a href="/login"><i class="fa fa-sign-in"></i>Login</a></li>
          <li><a href="/register" onclick="checkauth(1)"><i class="fa fa-user-plus"></i>Sign up</a></li>
        @endif




      </ul>
    </div><!-- /.navbar-collapse -->
    <hr class="navbar-divider">
  </div><!-- /.container-fluid -->
</nav>
