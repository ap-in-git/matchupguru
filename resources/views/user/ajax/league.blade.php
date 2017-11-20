@if($leagues->count()>0)
<div class="col-sm-12">
@foreach ($leagues as $key =>$league)

                @if ($league->completed!=1&&$league->reseted!=1)
                  <div class="panel panel-default">
              <div class="panel-heading">
                  League : {{$total}} | Season : {{$league->season->name}}
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
                <span class="pull-right">League Record :  <span class="text-primary">{{$league->league_win}} - {{$league->league_loss}}</span></span>

              </div>
              <div class="panel-body">
              <table class="table table-responsive table-bordered">
                <thead>
                   <tr>
                      <th>Date</th>
                      <th>Deck Name</th>
                      <th>Deck Version</th>
                      <th>Opponent</th>
                      <th>Opponent Deck  Name</th>
                      <th>Opponent Deck  Version</th>
                      <th>Match Record</th>
                      <th>Result</th>
                      <th>&nbsp;</th>

                  </tr>
                </thead>
                <tbody>
                  @foreach ($league->matches as $key => $match)
                    <tr>
                      <td>{!!date("Y / m /d",strtotime($match->created_at))!!}</td>
                      <td>{{$league->deck->deck}}</td>
                        <td>{{$league->deck->version}}</td>
                      <td>{{$match->opponent_name}}</td>
                      <td>{{$match->deck->deck}}</td>
                      <td>{{$match->deck->version}}</td>
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
          @else
            <div class="panel panel-default">
        <div class="panel-heading">
            League : {{$key+1}} | Season: {{$league->season->name}} |
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
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Deck Name</th>
                    <th>Deck Version</th>
                    <th>Opponent</th>
                    <th>Opponent Deck  Name</th>
                    <th>Opponent Deck  Version</th>
                    <th>Match Record</th>
                    <th>Result</th>
                    <th>&nbsp;</th>

                </tr>
                </thead>
                <tbody>
                @foreach ($league->matches as $key => $match)
                    <tr>
                        <td>{!!date("Y / m /d",strtotime($match->created_at))!!}</td>
                        <td>{{$league->deck->deck}}</td>
                        <td>{{$league->deck->version}}</td>
                        <td>{{$match->opponent_name}}</td>
                        <td>{{$match->deck->deck}}</td>
                        <td>{{$match->deck->version}}</td>
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

</div>

@endif
