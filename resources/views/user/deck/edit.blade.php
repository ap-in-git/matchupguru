@extends('layouts.app')
@section('title')
    View Deck
@endsection
@section('content')
    <div class="container">
        <deck-details slug="{{Request::segment(3)}}" token="{{csrf_token()}}"></deck-details>
    </div>


@endsection
