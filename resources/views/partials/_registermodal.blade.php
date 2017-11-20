
<div class="container" id="registercontainer">
  <div class="modal fade " id="myRegisterModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <br>
          <div class="bs-example bs-example-tabs">
              <ul id="myTab" class="nav nav-tabs">
                <li ><a href="#signinregister" data-toggle="tab">Sign In</a></li>
                <li class="active"><a href="#signupregister" data-toggle="tab">Register</a></li>

              </ul>
          </div>
        <div class="modal-body">
          <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade " id="signinregister">
            <Login></Login>
          </div>
          <div class="tab-pane fade active in" id="signupregister">
         <Register></Register>
        </div>
      </div>
        </div>
        <div class="modal-footer">
        <center>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </center>
        </div>
      </div>
    </div>
  </div>

</div>
