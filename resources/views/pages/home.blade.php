@extends('layouts.front')
@section('title')
  Home

@endsection
@section('style')
  <style>
  .text-white a{
    color:white;
  }
  </style>

@endsection
@section('content')
  @include('partials._slider',[
    "sliders"=>$sliders
  ])

  @include('partials._blogcard',[
    "posts"=>$posts
  ])

@endsection
