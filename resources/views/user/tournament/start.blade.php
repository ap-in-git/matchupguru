@extends('layouts.app')
@section('title')
    Game Match
@endsection
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-sm-12">

                <a href="/game/magic/match" class="btn btn-primary pull-left">Change Format or deck</a>

            </div>
        </div>


        <tournament deck_id="{{$deck_id}}"
               event_type="{{$event_type}}"
               tournament_name="{{$tournament_name}}"
               tournament_size="{{$tournament_size}}"
        ></tournament>
    </div>




@endsection
