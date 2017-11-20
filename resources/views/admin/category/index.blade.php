@extends('layouts.app')
@section('title')
    Manage Category
@endsection
@section('content')

    <div class="container">
        <div>
            <div class="text-center">All Category</div>

            <a href="{{route("post-category.create")}}" class="btn btn-primary pull-right" style="margin-bottom:2%;">Add a Category</a>


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
                    @foreach ($categories as $key => $category)
                        <tr>

                            <td>{{$category->name}}</td>
                            <td>{{$category->slug}}</td>
                            <td>


                                <form method="POST" action="{!! route("post-category.destroy",$category->id) !!}">
                                    {{csrf_field()}}
                                    {{method_field("DELETE")}}
                                    <a href="{{route("post-category.edit",$category->id)}}" class="btn btn-success">Edit Category</a>
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
