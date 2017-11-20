@extends('layouts.app')
@section('title')
    Game Match
@endsection
@section('content')
    <div class="container">

        <tournament
                deck_id="{{$deck_id}}"
                event_type="{{$event_type}}"
                    tournament_name="{{$tournament_name}}"
                    tournament_size="{{$tournament_size}}"
                    slug="{{$slug}}"
        ></tournament>
    </div>


@endsection
