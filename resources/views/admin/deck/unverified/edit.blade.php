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
              <label for="name" class=" control-label">Season </label>
              <select name="season" class="form-control">
                  @foreach($seasons as $season)
                      <option value="{{$season->id}}" {!! $season->id==$deck->season_id?'selected':''!!}>{{$season->name}}</option>
                      @endforeach
              </select>


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
              <label for="version" class=" control-label">Version </label>

                  <input id="version" type="text" class="form-control" name="version" value="{{$deck->version}}" required autofocus>

                  @if ($errors->has('version'))
                      <span class="help-block">
                          <strong>{{ $errors->first('version') }}</strong>
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

        <div class="page-header">
          Merge with


        </div>

        <form method="post" action="{{route("merge.unverified.deck")}}">
          {{csrf_field()}}
          <input type="hidden" name="merge_deck_id" value="{{$deck->id}}">
           <unveridefineddeckformat gameurl={{route("games.all")}} formaturl="/gameformat/"
              defaultgame="{{$deck->game_id}}"
             defaultformat="{{$deck->format_id}}"
          ></unveridefineddeckformat>

          <button type="submit" class="btn btn-primary">Merge</button>
        </form>
      </div>

      <div class="col-sm-4">

        <form method="post" action="{!!route("admin.deck.destroy",$deck->id)!!}">
         {{csrf_field()}}
         {{method_field("DELETE")}}
           <a href="{!!route("admin.deck.unverified.approve",$deck->id)!!}" class="btn btn-success btn-">Approve deck</a>
         <button class="btn btn-danger pull-right">Delete</button>
        </form>
      </div>


    </div>
  </div>


@endsection
