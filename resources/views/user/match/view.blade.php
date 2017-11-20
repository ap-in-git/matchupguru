@extends('layouts.app')
@section('title')
View Match
@endsection

@section('content')
<div class="container">

<div class="row">
{{-- <div class="col-sm-12"><a href="#" class="btn btn-primary">Go back</a></div> --}}
<div style="margin:5% 0%;"></div>
<div class="panel panel-primary">
  <div class="panel-heading">Match</div>
  <div class="panel-body">
    <div class="col-sm-8">
    <div class="list-group">
    <div class="list-group-item"><label>Username :</label> &nbsp; {{Auth::user()->magic_name}}</div>
    <div class="list-group-item"><label>Format :</label> &nbsp; {{$match->deck->format->name}}</div>
   <div class="list-group-item"><label>Deck :</label> &nbsp;
  @foreach ($decks as $key => $deck)
    @if ($deck->id==$match->deck_id)
      {{$deck->name}}
    @endif
  @endforeach
   </div>
   <div class="list-group-item"><label>Opponent User Name :</label> &nbsp; {{$match->opponent_name}}</div>
   <div class="list-group-item"><label>Opponent Deck :</label> &nbsp; {{$match->deck->name}}</div>
   <div class="list-group-item"><label>Game 1 Play/draw :</label> &nbsp; {!!$match->g1_play_draw=='p'?'Play':'Draw'!!}</div>
    <div class="list-group-item"><label>Game 1 starting hand size :</label> &nbsp; {{$match->g1_start_size}}</div>
    <div class="list-group-item"><label>Game 1 starting hand size of opponent:</label> &nbsp; {{$match->g1_opp_size}}</div>
     <div class="list-group-item"><label>Game 1 result :</label> &nbsp; {!!$match->g1_result=='w'?'Win':'Loss'!!}</div>
   <div class="list-group-item"><label>Game 2 Play/draw :</label> &nbsp; {!!$match->g2_play_draw=='p'?'Play':'Draw'!!}</div>
    <div class="list-group-item"><label>Game 2 starting hand size :</label> &nbsp; {{$match->g2_start_size}}</div>
    <div class="list-group-item"><label>Game 2 starting hand size of opponent:</label> &nbsp; {{$match->g2_opp_size}}</div>
     <div class="list-group-item"><label>Game 2 result :</label> &nbsp; {!!$match->g2_result=='w'?'Win':'Loss'!!}</div>
   <div class="list-group-item"><label>Game 3 Play/draw :</label> &nbsp; {!!$match->g3_play_draw=='p'?'Play':'Draw'!!}</div>
    <div class="list-group-item"><label>Game 3 starting hand size :</label> &nbsp; {{$match->g3_start_size}}</div>
    <div class="list-group-item"><label>Game 3 starting hand size of opponent:</label> &nbsp; {{$match->g3_opp_size}}</div>
     <div class="list-group-item"><label>Game 3 result :</label> &nbsp; {!!$match->g3_result=='w'?'Win':'Loss'!!}</div>
     <div class="list-group-item"><label>Match   Win :</label> &nbsp; {{$match->match_win}}</div>
     <div class="list-group-item"><label>Match   Loss :</label> &nbsp; {{$match->match_loss}}</div>
     <div class="list-group-item"><label>Final  result :</label> &nbsp; {!!$match->final_result=='1'?'Win':'Loss'!!}</div>
          <div class="list-group-item"><label>Key Cards :</label> &nbsp; {{$match->key_card}}</div>
          <div class="list-group-item"><label>Duds :</label> &nbsp; {{$match->duds}}</div>
          <div class="list-group-item"><label>Note :</label> &nbsp; {{$match->note}}</div>

      {{-- <div class="list-group-item"><label>Created at :&nbsp; </label>{{date("Y-m-d",strtotime($user->created_at))}} </div> --}}
  </div>
  </div>
    {{--  --}}

    <!-- Modal -->




</div>
</div>
</div>
</div>


</div>

@endsection
