@extends('layouts.app')
@section('title')
  Manage Users
@endsection

@section('content')
  <div class="container" style="margin-bottom:2%;">

      @include('admin.ajax.search',[
        "holder"=>"Search User"
      ])
  </div>

<div class="container">
<div class="row">
  <div class="col-md-12">
    <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created at</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $key => $user)
            <tr>
              <td>{{$user->name}}</td>
              <td>{{$user->email}}</td>
              <td>
               @if ($user->role==1)
               Admin
             @endif
             @if($user->role==3)
               Writer
             @endif
             @if ($user->role==2)
               General User
             @endif
              </td>
                <td>{!!date("Y-m-d",strtotime($user->created_at))!!}</td>
              <td>
                <a href="{{route("admin.users.single",$user->id)}}" class="btn btn-success">Manage User</a>

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
        <div class="text-center">{{$users->links()}}</div>
  </div>


</div>
</div>

@endsection


@section('script')
  <script type="text/javascript">

  var options = {

     url: function(phrase) {
     return "/admin/user/search/"+phrase;
   },


   getValue: "name",
   template: {
   type: "links",
   fields: {
       link: "link"
   }
},

   list: {
     match: {
       enabled: true
     }
   },
 };

 $("#searchuser").easyAutocomplete(options);



  </script>

@endsection
