@extends('layouts.app')
@section('title')
  Add Format
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-8">

      <a href="{{route("format.index")}}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Add Format</h4>

      </div>
      <div class="col-sm-10 col-sm-offset-2">
        <form method="post"  action="{{route("format.store")}}">
          {{csrf_field()}}

          <div class="form-group{{ $errors->has('game') ? ' has-error' : '' }}">
              <label for="game" class=" control-label">Game * </label>

                <select class="form-control" required name="game">
                @foreach ($games as $key => $game)
                  <option value="{{$game->id}}">{{$game->name}}</option>
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

                  <input id="name" type="text" class="form-control" name="name" value="{{ $name or old('name') }}" required autofocus>

                  @if ($errors->has('name'))
                      <span class="help-block">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                  @endif

          </div>







          <input type="submit" value="Add Format" class="btn btn-primary">

        </form>

      </div>
    </div>
  </div>


@endsection
