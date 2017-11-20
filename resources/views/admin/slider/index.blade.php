@extends('layouts.app')
@section('title')
  Manage Slider
@endsection
@section('content')
<div class="container">
<a href="{{route("admin.users.index")}}" class="btn btn-primary pull-left">Go Back</a><h2 class="text-center">Manage Sliders </h2>
<Slider dataurl={{route("admin.slider.data")}} uploadurl={{route("admin.slider.add")}} deleteurl=" /admin/sliders/" token="{{csrf_token()}}"></Slider>


</div>
@endsection
