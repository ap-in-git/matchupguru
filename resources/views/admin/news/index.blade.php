@extends('layouts.app')
@section('title')
    Manage News
@endsection
@section('content')
      <h2 class="page-header text-center">All news</h2>
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Created at</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($news  as $key => $n)
                        <tr>
                            <td>{{$n->id}}</td>
                            <td>{{$n->title}}</td>
                            <td>{!!date("Y-m-d",strtotime($n->created_at))!!}</td>
                            <td>
                            <a href="{{route("news.show",$n->id)}}" class="btn btn-primary">View </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


        </div>
    </div>


@endsection



