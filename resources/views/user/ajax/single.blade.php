<div class="col-sm-12">

<div class="panel panel-default">
    <div class="panel-heading">Matches</div>
    <div class="panel-body">
        <table class="table table-responsive table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Opponent</th>
            <th>Opponent Deck</th>
            <th>Result</th>
            <th>&nbsp;</th>

        </tr>
        </thead>
            <tbody>
             @foreach($matches as $match)
                 <tr>
                     <td>{!!date("Y / m /d",strtotime($match->created_at))!!}</td>
                     <td>{{$match->opponent_name}}</td>
                     <td>{{$match->deck->name}}</td>
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
</div>