@extends('layouts.app')
@section('title')
    Manage Tags
@endsection
@section('content')

    <div class="container">
        <div>
            <div class="text-center">All Tags</div>

            <a href="{{route("post-tag.create")}}" class="btn btn-primary pull-right" style="margin-bottom:2%;">Add a Tag</a>


        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tags as $key => $tag)
                        <tr>

                            <td>{{$tag->name}}</td>
                            <td>{{$tag->slug}}</td>
                            <td>


                                <form method="POST" action="{!! route("post-tag.destroy",$tag->id) !!}">
                                    {{csrf_field()}}
                                    {{method_field("DELETE")}}
                                    <a href="{{route("post-tag.edit",$tag->id)}}" class="btn btn-success">Edit Tag</a>
                                    <button type="submit" class="btn btn-danger"  onclick="return confirm('Are you sure')" >Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


        </div>
    </div>


@endsection


@section('script')

@endsection
