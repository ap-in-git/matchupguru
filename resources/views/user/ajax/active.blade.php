
<div class="card">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active" ><a href="#leagueView" aria-controls="home" role="tab" data-toggle="tab">Leagues</a></li>
        <li role="presentation" ><a href="#tournamentView" aria-controls="profile" role="tab" data-toggle="tab">Tournaments</a></li>
    </ul>


    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="leagueView">
            @if($total_leagues==0)
                <div class="alert alert-info">You have no active leagues</div>
            @else
                @foreach ($leagues as $key => $league)


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            League : {{$total_leagues}} | Season : {{$league->season->name}}
                            @php
                                $total_leagues=$total_leagues-1;
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



                @endforeach


            @endif



        </div>
        <div role="tabpanel" class="tab-pane" id="tournamentView">
            @if($total_tournament==0)
                <div class="alert alert-info">You have no active tournament</div>
            @else
                @foreach ($tournaments as $key => $tournament)


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Tournament : {{$total_tournament}}  | {!! $tournament->connection==1?'Online' : 'Offline' !!} | Season : {{$tournament->season->name}}
                            @php
                                $total_tournament=$total_tournament-1;
                            @endphp
                            @if ($tournament->completed==1)
                                <span class="text-primary "  >Completed</span>
                            @else
                                <a class="btn btn-primary" href="{{route("tournament.continue",$tournament->slug)}}">Log a Tournament Match</a>
                                <span class="btn btn-danger" onclick="completeTournament('{{$tournament->slug}}')">Complete tournament</span>
                            @endif


                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th>Date</th>

                                    <th>Deck Name</th>
                                    <th>Deck Version</th>
                                    <th>Opponent Name</th>
                                    <th>Opponent Deck Name</th>
                                    <th>Opponent Deck Version</th>
                                    <th>Match Record</th>
                                    <td>Top 8</td>
                                    <th>Result</th>
                                    <th>&nbsp;&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tournament->matches as $key => $match)
                                    <tr>
                                        <td>{!!date("Y / m /d",strtotime($match->created_at))!!}</td>

                                        <td>{{$tournament->deck->deck}}</td>
                                        <td>{{$tournament->deck->version}}</td>
                                        <td>{{$match->opponent_name}}</td>
                                        <td>{{$match->deck->deck}}</td>
                                        <td>{{$match->deck->version}}</td>
                                        <td>{{$match->match_win}}- {{$match->match_loss}}</td>
                                        <td>{!!$match->top_8==1?'Yes':'No'!!}</td>
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



                @endforeach

            @endif

        </div>



    </div>
</div>
