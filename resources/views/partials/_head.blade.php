<head>
  <!-- Required meta tags always come first -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{asset("images/Logo.ico")}}" />
  <link rel="manifest" href="{{asset("manifest.json")}}"/>

  <title>Matchupguru |  @yield('title') </title>
@include('partials._stylesheet')
@yield('stylesheet')

</head>
