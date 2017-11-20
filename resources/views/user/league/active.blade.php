@extends('layouts.app')
@section('title')
Active Leagues
@endsection
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-sm-2 col-sm-offset-10">
      <a href="{{route("game.match.start",Request::segment(2))}}" class="btn btn-primary ">Log a League Match</a>
      </div>
      <h2 class="text-center">
       Magic Statistic
      </h2>
      </div>
      @foreach ($leagues as $key => $league)

        @if ($league->completed!=1&&$league->reseted!=1)
          <div class="panel panel-default">
      <div class="panel-heading">
        League : {{$total}}
        @php
        $total=$total-1;
        @endphp
       @if ($league->completed==1)
          <span class="text-primary "  >Completed</span>
        @elseif ($league->reseted==1)
            <span class="text-primary ">Reseted</span>
        @else
          <a class="btn btn-primary" href="{{route("league.continue",$league->id)}}">Log a League Match</a>
         <span class="btn btn-danger" onclick="resetleague({{$league->id}})">Drop from League</span>
        @endif
        <span class="pull-right">League Record : <span class="text-primary">{{$league->league_win}} - {{$league->league_loss}}</span></span>

      </div>
      <div class="panel-body">
      <table class="table table-bordered">
        <thead>
           <tr>
              <th>Date</th>
              <th>Format</th>
              <th>Deck</th>
              <th>Opponent</th>
              <th>Opponent Deck</th>
              <th>Match Record</th>
              <th>Result</th>
              <th>&nbsp;</th>

          </tr>
        </thead>
        <tbody>
          @foreach ($league->matches as $key => $match)
            <tr>
              <td>{!!date("Y / m /d",strtotime($match->created_at))!!}</td>
              <td>{!!$league->format->name!!}</td>
              <td>{{$league->deck->name}}</td>
              <td>{{$match->opponent_name}}</td>
              <td>{{$match->deck->name}}</td>
              <td>{{$match->match_win}}- {{$match->match_loss}}</td>
              <td>
               @if ($match->final_result==1)
                  Win
               @else
                 Loss
               @endif
              </td>
              <td>
                <a href="{!!route("match.edit",$match->id)!!}" class="btn btn-success btn-xs">View/Edit Match</a>
              </td>
      </tr>
                    @endforeach
        </tbody>
      </table>

      </div>
      </div>
        @endif


      @endforeach


  <div class="modal fade" id="LeagueResetModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background:red; ">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color:white;">Drop from League</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to drop from League .</p>
        </div>
        <div class="modal-footer">
          <form method="post" action="{{route("league.reset")}}" >
            {{csrf_field()}}
            <input type="hidden" value="#" id="leagueresetform" name="id">
            <button type="submit" class="btn btn-danger">Confirm</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </form>

        </div>
      </div>

    </div>
  </div>
  </div>


@endsection

@section('script')
<script>
var resetleague=function(id)
{

  $("#leagueresetform").val(id);
  $("#LeagueResetModal").modal("show");

}

</script>

@endsection
