<script src="{{asset("js/app.js")}}"></script>
<script src="{{asset("js/jquery.meanmenu.min.js")}}"></script>
<script src="//cdn.jsdelivr.net/alertifyjs/1.10.0/alertify.min.js"></script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="{{asset("js/owl.carousel.min.js")}}"></script>
<script src="{{asset("auto/easy.min.js")}}"></script>
<script src="{{asset("js/script.js")}}"></script>

{{-- <script>
jQuery(document).ready(function () {
    jQuery('header nav').meanmenu();
});
</script> --}}
@yield('script')

@include('partials._message')
<script type="text/javascript">
  alertify.prompt().setHeader("Matchupguru");

var openheartname=function(event){
  alertify.prompt('Enter Your Heartstone Username', ''
                 , function(evt, value) {
                  savename("heart",value)
                  });
}

var opengwentname=function(){
  alertify.prompt('Enter Your Gwent Username', ''
                 , function(evt, value) {
                  savename("gwent",value)
                  });
}


var openmagicname=function(){

  alertify.prompt('Enter Your Magic Username', ''
                 , function(evt, value) {
                  savename("magic",value)
                  });

}

var savename=function(name,value){
  var _token="{{csrf_token()}}";
$.ajax({
  url: '{{route("game.name.set")}}',
  type: 'POST',
data: {
    _token,
    name,
  value
  }
})
.done(function(data) {
location.reload();
})
.fail(function() {
  console.log("error");
})



}

var checkauth=function(id){
  $.ajax({
    url: '{{route("auth.check")}}',
  })
  .done(function(data) {
    if(data==false){
      if(id==0){
        $("#myModal").modal("show");
        return;
      }
      if(id==1){
        $("#myRegisterModal").modal("show");
        return;
      }
    }
    alertify.set('notifier','position', 'top-right');
  alertify.success("You are already logged in ");
  return;


  })
}




</script>
