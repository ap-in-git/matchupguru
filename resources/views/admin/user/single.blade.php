@extends('layouts.app')
@section('title')
  Manage Users
@endsection

@section('content')
<div class="container">

<div class="row">
<div class="col-sm-12"><a href="{!!url()->previous()!!}" class="btn btn-primary">Go back</a></div>
<div style="margin:5% 0%;"></div>
<div class="panel panel-primary">
  <div class="panel-heading">User : {{$user->name}}</div>
  <div class="panel-body">
    <form method="post" action="{{route("user.verified")}}">
      {{csrf_field()}}
      <input type="hidden" value="{{$user->id}}" name="user_id">

    <div class="col-sm-8">
    <div class="list-group">
      <div class="list-group-item"><label>Name : </label> {{$user->name}}  </div>
      <div class="list-group-item"><label>Email : </label> {{$user->email}}  </div>
      <div class="list-group-item"><label>Verified </label>:&nbsp;<input type="checkbox" {{$user->verified==1?'checked':''}} name="verified"> </div>
      @if ($user->gwennt_name==null)
        <div class="list-group-item"><label>Gwent Username : </label> Not Activated</div>
      @else
        <div class="list-group-item"><label>Gwent Username :  </label>{{$user->gwennt_name}}  </div>
      @endif
      @if ($user->heart_name==null)
        <div class="list-group-item"><label>Heartstone Username : </label> Not Activated</div>
      @else
        <div class="list-group-item"><label>Heartstone Username :  </label>{{$user->heart_name}}  </div>
      @endif
      @if ($user->magic_name==null)
        <div class="list-group-item"><label>Magic Game Username : </label> Not Activated</div>
      @else
        <div class="list-group-item"><label>Magic Game Username :  </label>{{$user->magic_name}}  </div>
      @endif

      <div class="list-group-item"><label>Created at :&nbsp; </label>{{date("Y-m-d",strtotime($user->created_at))}} </div>
  </div>
  </div>
  <div class="col-sm-4">
    <button type="submit" class="btn btn-primary btn-block">Update</button>
      </form>
@if (Auth::user()->role==4)

    @if ($user->role==1)
  <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myAdminModal">Remove Admin</button>
      @else
  <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myAdminModal">Make  Admin</button>
      @endif

      @if ($user->role==3)
  <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myAuthorModal">Remove Author</button>
      @else
  <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myAuthorModal">Make  Author</button>
      @endif





  <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#myModal">Delete  User</button>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="background:red; ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color:white;">Delete  User  {{$user->name}}</h4>
          </div>
          <div class="modal-body">
            This will delete all the data related to the user also .
          </div>
          <div class="modal-footer">
            <form method="post" action="{{route("admin.user.delete",$user->id)}}">
              {{csrf_field()}}
              {{method_field("DELETE")}}
     <button type="submit" class="btn btn-danger">Delete</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>

          </div>
        </div>

      </div>
    </div>
  @if ($user->role==3)
    <div class="modal fade" id="myAuthorModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="background:blue; ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color:white;">Remove  Author  {{$user->name}}</h4>
          </div>
          <div class="modal-body">
          This will remove this user as author
          </div>
          <div class="modal-footer">
            <form method="post" action="{{route("make.user.author")}}">
              {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}"  name="user_id">
           <button type="submit" class="btn btn-danger">Remove</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>


          </div>
        </div>

      </div>
    </div>

  @else
    <div class="modal fade" id="myAuthorModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="background:blue; ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color:white;">Make   Author  {{$user->name}}</h4>
          </div>
          <div class="modal-body">
          This will make user author
          </div>
          <div class="modal-footer">
            <form method="post" action="{{route("make.user.author")}}">
              {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}" name="user_id">
         <button type="submit" class="btn btn-success">Make</button>
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>

          </div>
        </div>

      </div>
    </div>
  @endif
  @if ($user->role==1)
    <div class="modal fade" id="myAdminModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="background:blue; ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color:white;">Remove  Admin  {{$user->name}}</h4>
          </div>
          <div class="modal-body">
          This will remove this user as admin
          </div>
          <div class="modal-footer">
            <form method="post" action="{{route("make.user.admin")}}">
              {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}"  name="user_id">
           <button type="submit" class="btn btn-danger">Remove</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>


          </div>
        </div>

      </div>
    </div>

  @else
    <div class="modal fade" id="myAdminModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="background:blue; ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color:white;">Make   Admin  {{$user->name}}</h4>
          </div>
          <div class="modal-body">
          This will make user admin
          </div>
          <div class="modal-footer">
            <form method="post" action="{{route("make.user.admin")}}">
              {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}" name="user_id">
         <button type="submit" class="btn btn-success">Make</button>
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>

          </div>
        </div>

      </div>
    </div>
  @endif
  @endif
</div>
</div>
</div>


</div>

@endsection
