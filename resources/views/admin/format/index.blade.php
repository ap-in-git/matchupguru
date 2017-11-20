@extends('layouts.app')
@section('title')
  Manage Format
@endsection
@section('content')

  <div class="container">
    <div>
    <div class="text-center">All Games Format</div>

  <a href="{{route("format.create")}}" class="btn btn-primary pull-right" style="margin-bottom:2%;">Add a new Format</a>
  <div style="margin-bottom:2%;">@include('admin.ajax.search',[
    "holder"=>"Search Format"
  ])
</div>

  </div>
    <div class="row">
      <div class="col-sm-12">
        <format-table></format-table>

      </div>
    </div>
  </div>


@endsection


@section('script')
  <script type="text/javascript">

  var options = {

     url: function(phrase) {
     return "/admin/format/search/"+phrase;
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
