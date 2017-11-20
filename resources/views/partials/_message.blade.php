@if (Session::has('success'))
  <script>
  var delay = alertify.get('notifier','delay');
     alertify.set('notifier','delay', 3);
  alertify.set('notifier','position', 'top-right');
  alertify.success("{{Session::get("success")}}");
  </script>

@endif
@if (Session::has('email'))
  <script>
  var delay = alertify.get('notifier','delay');
     alertify.set('notifier','delay', 3);
  alertify.set('notifier','position', 'top-right');
  alertify.success("{{Session::get("email")}}");
  </script>

@endif
@if (Session::has('wrong'))
  <script>
  var delay = alertify.get('notifier','delay');
     alertify.set('notifier','delay', 3);
  alertify.set('notifier','position', 'top-right');
  alertify.error("{{Session::get("wrong")}}");
  </script>

@endif
