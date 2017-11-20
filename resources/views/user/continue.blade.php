@extends('layouts.app')
@section('title')
  Game Match
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">

      </div>
    </div>
    <Match deckid={{$deck_id}} leagueid={{$league_id}}></Match>
  </div>


@endsection
