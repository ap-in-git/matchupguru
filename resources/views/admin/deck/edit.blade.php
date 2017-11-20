@extends('layouts.app')
@section('title')
Edit Deck
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">

      <a href="{!!url()->previous()!!}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Edit Deck</h4>

      </div>

      <div class="col-sm-6 col-sm-offset-2">
        <form method="post"  action="{{route("admin.deck.update",$deck->id)}}">
          {{csrf_field()}}
          {{method_field("PUT")}}
           <format-change gameurl={{route("games.all")}} formaturl="/gameformat/"

               defaultgame="{{$deck->game_id}}"
              defaultformat="{{$deck->format_id}}"


           ></format-change>
            <div class="form-group{{ $errors->has('season') ? ' has-error' : '' }}">
                <label for="season" class=" control-label">Season </label>

                <select class="form-control" name="season">
                    @foreach($seasons as $season)
                        <option value="{{$season->id}}" {!! $season->id==$deck->season_id?'selected':'' !!}>{{$season->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('season'))
                    <span class="help-block">
                          <strong>{{ $errors->first('season') }}</strong>
                      </span>
                @endif

            </div>
            <div class="form-group{{ $errors->has('deck') ? ' has-error' : '' }}">
                <label for="deck" class=" control-label">Deck </label>

                <input id="deck" type="text" class="form-control" name="deck" value="{{$deck->deck}}" required autofocus>

                @if ($errors->has('deck'))
                    <span class="help-block">
                          <strong>{{ $errors->first('deck') }}</strong>
                      </span>
                @endif

            </div>
            <div class="form-group{{ $errors->has('version') ? ' has-error' : '' }}">
                <label for="version" class=" control-label">Name/Version </label>

                <input id="version" type="text" class="form-control" name="version" value="{{ $deck->version }}" required autofocus>

                @if ($errors->has('version'))
                    <span class="help-block">
                          <strong>{{ $errors->first('version') }}</strong>
                      </span>
                @endif
                @if (Session::has("deck_error"))
                    <span class="help-block">
                          <strong>{{ Session::get("deck_error")}}</strong>
                      </span>
                @endif

            </div>
          <div class="form-group{{ $errors->has('style') ? ' has-error' : '' }}">
              <label for="style" class=" control-label">Style </label>

                  <input id="style" type="text" class="form-control" name="style" value="{{$deck->style}}"  autofocus>

                  @if ($errors->has('style'))
                      <span class="help-block">
                          <strong>{{ $errors->first('style') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">

              <label for="active" class=" control-label">Active </label>
              @if ($deck->active==1)
                <input type="checkbox" name="active" checked>
              @else
                <input type="checkbox" name="active" >
              @endif

           </div>
          <div class="form-group{{ $errors->has('descripition') ? ' has-error' : '' }}">
              <label for="description" class=" control-label">Desciption </label>

                <textarea class="form-control" name="description">{{$deck->description}}</textarea>

                  @if ($errors->has('description'))
                      <span class="help-block">
                          <strong>{{ $errors->first('description') }}</strong>
                      </span>
                  @endif

          </div>
      <input type="submit" value="Edit Deck" class="btn btn-primary">

        </form>

      </div>
    </div>
  </div>


@endsection
