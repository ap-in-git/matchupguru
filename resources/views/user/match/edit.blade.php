@extends('layouts.app')

@section('title')
  Edit Match
@endsection


@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">
  <a href="/game/magic" class="btn btn-primary">Go Back</a>
      </div>
    </div>
  <Edit-Match matchid={{$match->id}}></Edit-Match>
  </div>


@endsection
