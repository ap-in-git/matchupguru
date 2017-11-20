<!DOCTYPE html>
<html lang="en">
@include('partials._head')
  <body>
    <header id="home" class="gradient-violat">
@include('partials._nav')
    </header>

<div id="app">


@yield('content')
</div>

@include('partials._message')

@include("partials._script")
  </body>
</html>
