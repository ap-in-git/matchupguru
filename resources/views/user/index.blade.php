@extends('layouts.app')
@section('title')
  Magic Status
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-5 col-sm-offset-7">
      <a href="{{route("game.match.start",Request::segment(2))}}" class="btn btn-primary pull-right">Log an Event</a>
      </div>
      <h2 class="text-center">
        Magic Statistics
      </h2>
      </div>
    <test-table></test-table>

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
  <div class="modal fade" id="TournamentCompleteModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" >Complete tournament</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to complete the tournament ?</p>
        </div>
        <div class="modal-footer">
          <form method="post" action="{{route("tournament.complete")}}" >
            {{csrf_field()}}
            <input type="hidden" value="#" id="tournamentResetForm" name="id">
            <button type="submit" class="btn btn-danger">Confirm</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </form>

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

var completeTournament=function(slug) {
$("#tournamentResetForm").val(slug);
$("#TournamentCompleteModal").modal("show");
}
</script>

@endsection
