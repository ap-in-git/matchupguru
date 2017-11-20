@extends('layouts.app')


@section('content')


  <div class="container">
  <div class="row">
    <div class="col-md-12">
      <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Seen</th>
              <th>&nbsp;</th>

            </tr>
          </thead>
          <tbody>
            @foreach ($messages as $key => $message)
              <tr id="messsage_{{$message->id}}">
                <td>{{$message->name}}</td>
                <td>{{$message->email}}</td>
                @if ($message->seen==1)
                <td id="seen_{{$message->id}}">Seen</td>
                @else
                  <td id="seen_{{$message->id}}">Not Seen</td>

                @endif
                <td>
                  <a href="#" class="btn btn-primary" onclick="showmesage('{{$message->id}}','{{$message->name}}','{{$message->subject}}','{{$message->message}}','{{$message->created_at->diffForHumans()}}')">View  Message</a>
                  <a href="#" class="btn btn-danger" onclick="deletemessage({{$message->id}})">Delete Message</a>


                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
          <div class="text-center">{{$messages->links()}}</div>

          <div class="modal fade" id="messageModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" > <div id="MessageHeader"></div></h4>
        </div>
        <div class="modal-body">
          <h4 id="MessageTitle"></h4>
          <p id="MessageText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
    </div>


  </div>
  </div>

@endsection


@section('script')
  <script>


var deletemessage=function(id){
  var closable = alertify.alert().setting('closable');
  alertify.confirm()
    .setting({
      'label':'Agree',
      'message': 'Are you sure you want to delete ??' ,
      'onok': function(){

    $.ajax({
      url: '/admin/message/'+id,
      type: 'Delete',
      data:{
        _token:"{{csrf_token()}}",
      }

    })
    .done(function(id) {
      alertify.set('notifier','delay', 3);
   alertify.set('notifier','position', 'top-right');
      alertify.success("Message Has been deleted");
    $("#messsage_"+id).remove();
    })
    }
    }).show();
}


var showmesage=function(id,name,subject,message,time){
$("#MessageHeader").html('Sent By :  ' +name +'('+time+')');
$("#MessageText").html(message);
$("#MessageTitle").html('Subject : '+subject);
$("#messageModal").modal("show");
$("#seen_"+id).html('Seen');


$.ajax({
  url: '/admin/message/'+id,
})
.done(function() {

})



}

  </script>
@endsection
