@extends('layouts.app')


@section('content')
<div class="container">
    <a class="btn btn-primary"  href="{!! route("season.create") !!}">Create a new season</a>
    <h2 class="page-header">Season Table</h2>
    <div class="row">
<div class="col-sm-12">
<table class="table table-responsive table-bordered ">

    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Game</th>
        <th>&nbsp;</th>
    </tr>
</thead>
    <tbody>
    @foreach($seasons as $season)
    <tr>
        <td>{{$season->id}}</td>

        <td>{{$season->name}}</td>
        <td>{{$season->game->name}}</td>
        <td><a href="{{route("season.edit",$season->id)}}" class="btn btn-primary">Edit</a> </td>


    </tr>
        @endforeach
    </tbody>
</table>
</div>
</div></div>

@endsection

