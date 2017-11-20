@extends('layouts.front')
@section('title')
  Contact

@endsection
@section('style')
  <style>
  .jumbotron {
  background: #358CCE;
  color: #FFF;
  border-radius: 0px;
  }
  .jumbotron-sm { padding-top: 24px;
  padding-bottom: 24px; }
  .jumbotron small {
  color: #FFF;
  }
  .h1 small {
  font-size: 24px;
  }
  </style>
@endsection
@section("content")
<div style="margin-top:5%;">
  <div class="jumbotron jumbotron-sm">
      <div class="container">
          <div class="row">
              <div class="col-sm-12 col-lg-12">
                  <h1 class="h1">
                      Contact us <small>Feel free to contact us</small></h1>
              </div>
          </div>
      </div>
  </div>
  <div class="container">
      <div class="row">
          <div class="col-md-8">
              <div class="well well-sm">
                  <form  method="post" action="{{route("contact.post")}}">
                    {{csrf_field()}}
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name">
                                  Name</label>
                              <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" required="required" />
                          </div>
                          <div class="form-group">
                              <label for="name">
                                  Email</label>
                              <input type="email" name="email" class="form-control" id="name" placeholder="Enter email" required="required" />
                          </div>


                          <div class="form-group">
                              <label for="name">
                                  Subject</label>
                              <input type="text"  name="subject" class="form-control" id="name" placeholder="Enter subject" required="required" />
                          </div>

                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name">
                                  Message</label>
                              <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                  placeholder="Message"></textarea>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary pull-right" id="btnContactUs">
                              Send Message</button>
                      </div>
                  </div>
                  </form>
              </div>
          </div>
          <div class="col-md-4">
              <form>
              <legend><span class="glyphicon glyphicon-globe"></span> Our office</legend>
              <address>
                  <strong>Matchup Guru HQ</strong><br>
                  The Cloud<br>
                  USA<br>
                  <abbr title="Phone">
                      P:</abbr>
                  (949) 555-7890
              </address>
              <address>
                  <strong>Full Name</strong><br>
                  <a href="mailto:#">contact@matchupguru.com</a>
              </address>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection
