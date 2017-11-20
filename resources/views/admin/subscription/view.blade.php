@extends('layouts.app')
@section('title')
    Manage subsciptions
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
                        <th>Email</th>
                        <th>Subscribed</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($subscriptions  as $key => $sub)
                        <tr>
                            <td>{{$sub->id}}</td>
                            <td>{{$sub->email}}</td>
                            <td>{!! $sub->subscribed==1?'Subscribed':'Unsubscribed' !!}</td>
                            <td>
                                @if($sub->subscribed==1)
                                     <a href="{!! route("subscription.change",$sub->id)!!}" class="btn btn-primary">Unsubscribe</a>
                                    @else
                                    <a href="{!! route("subscription.change",$sub->id)!!}" class="btn btn-primary">Subscribe</a>


                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


        </div>
    </div>


@endsection



