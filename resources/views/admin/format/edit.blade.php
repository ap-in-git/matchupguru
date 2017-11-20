@extends('layouts.app')
@section('title')
  Edit Post
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-8">

      <a href="{{route("format.index")}}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Edit  Format</h4>

      </div>
      <div class="col-sm-8">
        <form method="post" enctype="multipart/form-data" action="{{route("format.update",$format->id)}}">
          {{csrf_field()}}
          {{method_field("PUT")}}
          <div class="form-group{{ $errors->has('game') ? ' has-error' : '' }}">
              <label for="game" class=" control-label">Game * </label>

                <select class="form-control" required name="game">
              @foreach ($games as $key => $game)
                 <option value="{{$game->id}}" {{$game->id==$format->game_id?'selected':''}}  >{{$game->name}}</option>

              @endforeach
             </select>

                  @if ($errors->has('game'))
                      <span class="help-block">
                          <strong>{{ $errors->first('game') }}</strong>
                      </span>
                  @endif

          </div>

          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class=" control-label">Name </label>

                  <input id="name" type="text" class="form-control" name="name" value="{{$format->name}}" required autofocus>

                  @if ($errors->has('name'))
                      <span class="help-block">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                  @endif

          </div>

          <input type="submit" value="Edit Format" class="btn btn-primary">

        </form>

      </div>
      <div class="col-sm-4">


       </div>
       <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Format</button>

   <!-- Modal -->
   <div class="modal fade" id="deleteModal" role="dialog">
     <div class="modal-dialog">

       <!-- Modal content-->
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Delete Format</h4>
         </div>
         <div class="modal-body">
           <p>Are you sure you want to delte the post </p>
         </div>
         <div class="modal-footer">
           <form method="post" action="{{route("format.destroy",$format->id)}}">
             {{csrf_field()}}
             {{method_field("DELETE")}}
              <button type="submit" class="btn btn-danger" >Delete</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </form>

         </div>
       </div>

     </div>
   </div>

 </div>
    </div>
  </div>


@endsection
