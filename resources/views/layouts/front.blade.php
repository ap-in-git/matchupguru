<!DOCTYPE html>
<html lang="en">
@include('partials._head')
  <body>
    <header id="home" class="gradient-violat">
@include('partials._navbar')
    </header>

<div id="app">
  @if (!Auth::check())
    @include('partials._loginmodal')
    @include('partials._registermodal')
  @endif
    @yield('content')


@include('partials._footer')
</div>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
@include("partials._script")
  </body>
</html>
