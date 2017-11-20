
<div class="col-sm-12">

    @if ($tournaments->count()==0)
        <div class="col-sm-12">
            <div class="alert alert-info alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>Info !&nbsp;</strong>You have no  tournament
            </div>
        </div>
    @endif
    @foreach ($tournaments as $key => $tournament)


            <div class="panel panel-default">
                <div class="panel-heading">
                    Tournament : {{$total}} | Season : {{$tournament->season->name}}
                    @php
                        $total=$total-1;
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

                            <th>Deck</th>
                            <th>Opponent</th>
                            <th>Opponent Deck</th>
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

                                <td>{{$tournament->deck->name}}</td>
                                <td>{{$match->opponent_name}}</td>
                                <td>{{$match->deck->name}}</td>
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

</div>
