@extends('layouts.app')
@section('title')
  Manage decks
@endsection
@section('content')

  <div class="container">
    <div>
    <div class="text-center">All decks</div>

  <a href="{{route("admin.deck.create")}}" class="btn btn-primary pull-right" style="margin-bottom:2%;">Add a deck</a>
  <div style="margin-bottom:2%;">@include('admin.ajax.search',[
    "holder"=>"Search deck by name"
  ])
</div>

  </div>

<Deck-table></Deck-table>

{{-- <Admin-Deck></Admin-Deck> --}}
{{-- <div id="err">
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                    <th>Game</th>
                <th>Format</th>

                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($decks as $key => $deck)
                <tr id="deck_{{$deck->id}}">
                  <td>{{$deck->id}}</td>
                  <td>{{$deck->name}}</td>
                  <td>{{$deck->game->name}}</td>
                  <td>{{$deck->format->name}}</td>
                    <td>
                  <a href="{{route("admin.deck.edit",$deck->id)}}" class="btn btn-success">Manage deck</a>
                  <a href="#" class="btn btn-danger" onclick="deletedeck({{$deck->id}})">Delete deck</a>
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>

      </div>
      <div class="col-sm-12"><div class="text-center">{{$decks->links()}}</div></div>

    </div>
  </div> --}}
  </div>


@endsection


@section('script')
  <script type="text/javascript">
  var options = {
   url: function(phrase) {
     return "/admin/deck/search/"+phrase;
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


var deletedeck=function(id){
  var closable = alertify.alert().setting('closable');
  alertify.confirm().setHeader('Delete deck');
  alertify.confirm()
    .setting({
      'label':'Agree',
      'message': 'Are you sure you want to delete ??' ,
      'onok': function(){

    $.ajax({
      url: '/admin/deck/'+id,
      type: 'Delete',
      data:{
        _token:"{{csrf_token()}}",
      }

    })
    .done(function(id) {
      alertify.set('notifier','delay', 3);

   alertify.set('notifier','position', 'top-right');
      alertify.success("Deck Has been deleted");
    $("#deck_"+id).remove();
    })
    }
    }).show();
}




  </script>

@endsection
